<?php
require '../config/function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_paid_id']) && isset($_POST['order_id']) && isset($_POST['quantity'])) {
    $order_paid_id = $_POST['order_paid_id'];
    $order_id = $_POST['order_id'];
    $new_quantity = $_POST['quantity'];

    // Update quantity of the order item
    $update_query = mysqli_query($conn, "UPDATE order_paid SET quantity = $new_quantity WHERE order_paid_ID = $order_paid_id");

    if ($update_query) {
        // Calculate the new total amount
        $total_amount_query = mysqli_query($conn, "SELECT SUM(quantity * price) AS total_amount FROM order_paid WHERE order_ID = $order_id");
        $total_amount_result = mysqli_fetch_assoc($total_amount_query);
        $total_amount = $total_amount_result['total_amount'];

        // Update the total amount
        $update_order_query = mysqli_query($conn, "UPDATE `order` SET total_amount = $total_amount WHERE order_ID = $order_id");

        if ($update_order_query) {
            header("Location: order_details.php?order_id=$order_id");
            exit();
        } else {
            echo "Error updating total amount.";
        }
    } else {
        echo "Error updating quantity.";
    }
} else {
    header("Location: order_details.php?order_id=$order_id");
    exit();
}
?>
