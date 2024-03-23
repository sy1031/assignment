<?php
require_once 'config/function.php';
require_once __DIR__ . "/vendor/autoload.php";

// Retrieve order ID from URL parameter
$order_id = $_GET['order_id'] ?? null;

// Check if the order ID is provided
if (!$order_id) {
    // Handle the case where order ID is not provided
    echo "Error: Order ID is missing.";
    exit;
}

// Check if the cart data is available in the session
if (!isset ($_SESSION['cart']) || empty ($_SESSION['cart'])) {
    // Handle the case where cart data is missing or empty
    echo "Error: Cart data is missing or empty.";
    exit;
}

// Initialize total amount
$total_amount = 0;

// Iterate through each item in the cart to calculate the total amount
foreach ($_SESSION['cart'] as $item) {
    // Ensure that productPrice and quantity keys are set for each item
    if (isset ($item['productPrice'], $item['quantity'])) {
        $total_amount += $item['productPrice'] * $item['quantity'];
    } else {
        // Handle the case where productPrice or quantity keys are missing
        echo "Error: Missing product price or quantity.";
        exit;
    }
}

// Set your Stripe secret key
\Stripe\Stripe::setApiKey("sk_test_51KA3yrDKdTwGg1g3k6jPrfkpvZ7P4QdfLoLQQrKbaHptXbr1mDIyTwt3eb9yHHPt6laaweaIQwTxrkTk3Mqex8al000djCqimv");

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
    $stmt->bind_param("di", $total_amount, $order_id);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Payment information saved successfully

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

        echo "Payment information saved successfully and cart items deleted.";
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