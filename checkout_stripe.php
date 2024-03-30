<!-- This is the payment checkout page (Associated with Stripe Payment Page) after user click 'Proceed to  Payment Checkout' button in checkout.php -->
<?php
require_once 'config/function.php';
require_once __DIR__ . "/vendor/autoload.php";

// Retrieve order ID from URL parameter
$order_id = $_GET['order_id'] ?? null;

// Check if the order ID is provided (Verification)
if (!$order_id) {
    // Handle the case where order ID is not provided
    echo "Error: Order ID is missing.";
    exit;
}

// Ensure that the order ID is a valid integer
if (!is_numeric($order_id) || intval($order_id) <= 0) {
    echo "Error: Invalid order ID.";
    exit;
}

// Retrieve the original total amount from the session
$total_amount = $_SESSION['total_amount'] ?? 0;

// Ensure that the original total amount is provided and valid
if (!is_numeric($total_amount) || floatval($total_amount) <= 0) {
    // Handle the case where the original total amount is missing or invalid
    echo "Error: Invalid original total amount.";
    exit;
}

// Retrieve the discounted total amount from the session
$discounted_total_amount = $_SESSION['discounted_total_amount'] ?? null;

// Calculate the payment amount (unit amount * quantity)
// If there is not discounted amount, then use the total_amount (original price)
$payment_amount = $discounted_total_amount !== null ? $discounted_total_amount : $total_amount;


// Set Stripe secret key 
\Stripe\Stripe::setApiKey("sk_test_51KA3yrDKdTwGg1g3k6jPrfkpvZ7P4QdfLoLQQrKbaHptXbr1mDIyTwt3eb9yHHPt6laaweaIQwTxrkTk3Mqex8al000djCqimv");

// Check if the cart data is available in the session
if (isset ($_SESSION['cart']) && !empty ($_SESSION['cart'])) {
    // Retrieve the cart data
    $cart_data = $_SESSION['cart'];

    try {
        // Create a Stripe Checkout Session
        $checkout_session = \Stripe\Checkout\Session::create([
            "payment_method_types" => ["card"],
            "line_items" => [
                [
                    "price_data" => [
                        "currency" => "MYR",
                        //Why need to * 100 ?
                        //This is the conversion from the base currency unit to the smallest currency unit required by Stripe
                        //which is 'cents' for most currencies
                        "unit_amount" => $payment_amount * 100, // Use payment amount based on discount 
                        "product_data" => [
                            "name" => "Order Summary", // Change this to dynamically fetch product name
                        ],
                    ],
                    "quantity" => 1,
                ],
            ],
            "mode" => "payment",
            "success_url" => "http://localhost:8080/Assignment_server-side/assignment/success.php?order_id={$order_id}&payment_amount={$payment_amount}",
            "cancel_url" => "http://localhost:8080/Assignment_server-side/assignment/index.php",
        ]);

        // Redirect the user to the checkout page URL
        header("Location: " . $checkout_session->url);
        exit;
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle Stripe API errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // If cart data is not available or empty, display an error message
    echo "Error: Cart is empty.";
    exit;
}


// This part is additional part that can't be used in our localhost environment 
// (It only can retrive the payment_intent_id in Stripe if we have a public url and host our server in public server)
// (URL should be public url in order to capture the 'Webhook' in Stripe)

// Handle the payment intent ID after the payment is completed
if (isset ($_GET['payment_intent'])) {
    // Retrieve the payment intent ID from the URL parameter
    $payment_intent_id = $_GET['payment_intent'];

    try {
        // Retrieve the payment intent details
        $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

        // Extract payment information
        $payment_status = $payment_intent->status;

        // Handle payment status accordingly
        if ($payment_status === 'succeeded') {
            // Payment succeeded, update your database and fulfill the order
            echo "Payment succeeded. Order ID: $order_id";
            // Perform database update and order fulfillment

            // Reset the discounted total amount session variable
            unset($_SESSION['discounted_total_amount']);
        } else {
            // Payment failed or has a different status, handle accordingly
            echo "Payment failed or has a different status: $payment_status";
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle Stripe API errors
        echo "Error: " . $e->getMessage();
    }
}
?>