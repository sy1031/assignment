<?php
require_once 'config/function.php';
require_once __DIR__ . "/vendor/autoload.php";

// Retrieve order ID from URL parameter
$order_id = $_GET['order_id'] ?? null;
$payment_amount = $_GET['payment_amount'] ?? null;


// Check if the order ID and payment amount are provided
if (!$order_id || !$payment_amount) {
    // Handle the case where order ID or payment amount is missing
    echo "Error: Order ID or payment amount is missing.";
    exit;
}

// Ensure that the necessary session variables are set
// if (!isset ($_SESSION['total_amount']) || !isset ($_SESSION['discounted_total_amount'])) {
//     echo "Error: Total amount information is missing.";
//     exit;
// }

// Retrieve total amount and discounted total amount from session
// $total_amount = $_SESSION['total_amount'];
// $discounted_total_amount = $_SESSION['discounted_total_amount'];

// Check if the cart data is available in the session
if (!isset ($_SESSION['cart']) || empty ($_SESSION['cart'])) {
    // Handle the case where cart data is missing or empty
    echo "Error: Cart data is missing or empty.";
    exit;
}


// Iterate through each item in the cart to calculate the total amount
// foreach ($_SESSION['cart'] as $item) {
//     // Ensure that productPrice and quantity keys are set for each item
//     if (isset ($item['productPrice'], $item['quantity'])) {
//         $total_amount += $item['productPrice'] * $item['quantity'];
//     } else {
//         // Handle the case where productPrice or quantity keys are missing
//         echo "Error: Missing product price or quantity.";
//         exit;
//     }
// }

// Set your Stripe secret key
\Stripe\Stripe::setApiKey("sk_test_51KA3yrDKdTwGg1g3k6jPrfkpvZ7P4QdfLoLQQrKbaHptXbr1mDIyTwt3eb9yHHPt6laaweaIQwTxrkTk3Mqex8al000djCqimv");

// Calculate the payment amount (unit amount * quantity)
//$payment_amount = $discounted_total_amount !== null ? $discounted_total_amount : $total_amount;

try {
    // Connect to your MySQL database
    $conn = new mysqli('localhost', 'root', '', 'assignment');

    // Check connection
    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    }

    // Start a transaction
    $conn->begin_transaction();

    // Prepare SQL statement to insert payment data
    $sql = "INSERT INTO payment (payment_Date, payment_Amount, payment_Status, order_ID) VALUES (NOW(), ?, 'success', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $payment_amount, $order_id);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Payment information saved successfully

        // Update order status to 'success'
        $update_order_status_sql = "UPDATE `order` SET order_status = 'success', delivery_status = 'paid' WHERE order_ID = ?";
        $update_order_status_stmt = $conn->prepare($update_order_status_sql);
        $update_order_status_stmt->bind_param("i", $order_id);
        $update_order_status_stmt->execute();
        $update_order_status_stmt->close();

        // Clear cart data from session
        unset($_SESSION['cart']);

        // Delete cart items from the database for the corresponding user ID
        $user_id = $_SESSION['loggedInUser']['user_ID'];
        $delete_cart_sql = "DELETE FROM order_item WHERE user_ID = ?";
        $delete_cart_stmt = $conn->prepare($delete_cart_sql);
        $delete_cart_stmt->bind_param("i", $user_id);
        $delete_cart_stmt->execute();
        $delete_cart_stmt->close();

        // Commit the transaction
        $conn->commit();

        //echo "Payment information saved successfully and cart items deleted.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close database connection
    $stmt->close();
    $conn->close();

} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle Stripe API errors
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 50px 0;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: #fff;
        }

        .btn-return {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1 class="mb-4">Payment Success</h1>
            <p>Your payment has been processed successfully.</p>
            <p>Thank you for your purchase!</p>
            <a href="cart.php" class="btn btn-primary btn-return">Return to Cart</a>
            <a href="payment_history.php?user_id=<?php echo $_SESSION['loggedInUser']['user_ID']; ?>" class="btn btn-primary btn-return">View Your Payment History</a>

        </div>
    </div>
</body>


<style>
    
</style>
</html>