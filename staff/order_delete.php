<?php
require '../config/function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_paid_id']) && isset($_POST['order_id'])) {
    $order_paid_id = $_POST['order_paid_id'];
    $order_id = $_POST['order_id'];
    
    // Get quantity and price
    $order_detail_query = mysqli_query($conn, "SELECT quantity, price FROM order_paid WHERE order_paid_ID = $order_paid_id");
    $order_detail = mysqli_fetch_assoc($order_detail_query);
    $quantity = $order_detail['quantity'];
    $price = $order_detail['price'];

    // Delete order details based on order_paid_ID
    $delete_order_query = mysqli_query($conn, "DELETE FROM order_paid WHERE order_paid_ID = $order_paid_id");

    if ($delete_order_query) {
        // Calculate the new total amount
        $total_amount_query = mysqli_query($conn, "SELECT SUM(quantity * price) AS total_amount FROM order_paid WHERE order_ID = $order_id");
        $total_amount = mysqli_fetch_assoc($total_amount_query)['total_amount'];

        // Update the total amount
        $update_order_query = mysqli_prepare($conn, "UPDATE `order` SET total_amount = ? WHERE order_ID = ?");
        mysqli_stmt_bind_param($update_order_query, "di", $total_amount, $order_id);

        if (mysqli_stmt_execute($update_order_query)) {
            // Check if this was the last order_paid_ID for this order_ID
            $check_last_order_query = mysqli_query($conn, "SELECT * FROM order_paid WHERE order_ID = $order_id");
            if (mysqli_num_rows($check_last_order_query) == 0) {
                $delete_order_query = mysqli_query($conn, "DELETE FROM `order` WHERE order_ID = $order_id");
                header("Location: orders.php");
            } else {
                header("Location: order_details.php?order_id=$order_id");
            }
            exit();
        } else {
            echo "Error updating total amount.";
        }
    } else {
        echo "Error deleting order details.";
    }
} else {
    header("Location: order_details.php");
    exit();
}
?>
