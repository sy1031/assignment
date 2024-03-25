<?php
include('Includes/header.php');

// Check if order_id is provided in the URL
if(isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details including product name
    $fetch_order_details_sql = "SELECT op.product_ID, op.quantity, op.price, p.productName
                                FROM order_paid op 
                                INNER JOIN product p ON op.product_ID = p.product_ID
                                WHERE op.order_ID = ?";
    $stmt = $conn->prepare($fetch_order_details_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($product_ID, $quantity, $price, $productName);
    $stmt->fetch();
    $stmt->close();

    // Update quantity
    // Fetch the order_paid_ID associated with the order_id
    $fetch_order_paid_id_sql = "SELECT order_paid_ID FROM order_paid WHERE order_ID = ?";
    $stmt = $conn->prepare($fetch_order_paid_id_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($order_paid_id);
    $stmt->fetch();
    $stmt->close();

    // Check if the form is submitted
    if(isset($_POST['update_quantity'])) {
        $error_message = "";
        $quantity = $_POST['quantity'];

        // Update quantity in order_paid table
        $update_quantity_sql = "UPDATE order_paid SET quantity = ? WHERE order_paid_ID = ?";
        $stmt = $conn->prepare($update_quantity_sql);
        $stmt->bind_param("ii", $quantity, $order_paid_id);

        if($stmt->execute()) {
            $success_message = "Quantity updated successfully.";
        } else {
            $error_message = "Error updating quantity: " . $conn->error;
        }

        $stmt->close();
    }

    // Fetch the current quantity from order_paid table
    $fetch_quantity_sql = "SELECT quantity FROM order_paid WHERE order_paid_ID = ?";
    $stmt = $conn->prepare($fetch_quantity_sql);
    $stmt->bind_param("i", $order_paid_id);
    $stmt->execute();
    $stmt->bind_result($current_quantity);
    $stmt->fetch();
    $stmt->close();

    // Update delivery status
    // Check if the form is submitted
    if(isset($_POST['update_delivery_status'])) {
        // Define $error_message variable
        $error_message = "";
        
        $delivery_status = $_POST['delivery_status'];

        $update_status_sql = "UPDATE `order` SET delivery_status = '$delivery_status' WHERE order_ID = ?";
        $stmt = $conn->prepare($update_status_sql);
        $stmt->bind_param("i", $order_id);
        
        if($stmt->execute()) {
            $success_message = "Delivery status updated successfully.";
        } else {
            $error_message = "Error updating delivery status: " . $conn->error;
        }

        $stmt->close();
    }

    // Fetch the current delivery status of the order
    $fetch_status_sql = "SELECT delivery_status FROM `order` WHERE order_ID = ?";
    $stmt = $conn->prepare($fetch_status_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($current_status);
    $stmt->fetch();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Delivery Status</title>
</head>
<body>
    <div class="container-fluid px-4">
        <h5 style="margin:30px 0px 0px 10px">Product Name: <?php echo $productName; ?> </h5>
            <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Update Quantity
                    <a href="order_details.php?order_id=<?php echo $_GET['order_id']; ?>" class="btn btn-primary float-end">Back</a>
                </h4>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>
                <!-- Form to update quantity -->
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="<?php echo $current_quantity; ?>">
                    </div>
                    <input type="hidden" name="order_paid_id" value="<?php echo $order_paid_id; ?>">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <button type="submit" name="update_quantity" class="btn btn-success">Update Quantity</button>
                </form>
            </div>
        </div>
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Update Delivery Status</h4>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>
                <!-- Form to update delivery status -->
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="delivery_status" class="form-label">Delivery Status</label>
                        <select class="form-select" id="delivery_status" name="delivery_status">
                            <option value="paid" <?php if($current_status == "paid") echo "selected"; ?>>PAID</option>
                            <option value="shipped" <?php if($current_status == "shipped") echo "selected"; ?>>SHIPPED</option>
                            <option value="delivered" <?php if($current_status == "delivered") echo "selected"; ?>>DELIVERED</option>
                        </select>
                    </div>
                    <input type="hidden" name="order_id" value="<?php echo $_GET['order_id']; ?>">
                    <button type="submit" name="update_delivery_status" class="btn btn-success">Update Delivery Status</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php include('Includes/footer.php'); ?>

