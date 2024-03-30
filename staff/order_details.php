<?php include('Includes/header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Admin</title>
</head>
<body>
    <div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Order Details
                    <a href="orders.php" class="btn btn-primary float-end">Back</a>
                </h4>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>
                <?php
                // Check if the order_ID is provided in the URL parameter
                if(isset($_GET['order_id'])) {
                    $order_id = $_GET['order_id'];

                    $orders = mysqli_query($conn, "SELECT * FROM order_paid WHERE order_ID = $order_id");

                    if(!$orders){
                        echo '<h4>Something Went Wrong!</h4>';
                        return false;
                    }

                    // Fetch order details from order table
                    $order_query = mysqli_query($conn, "SELECT * FROM `order` WHERE order_ID = $order_id");
                    $order_data = mysqli_fetch_assoc($order_query);

                    ?>
                     <h5 style="margin:10px 10px 10px 5px">Order ID: <?php echo $order_id; ?> </h5>
                    <?php

                    if (mysqli_num_rows($orders) > 0) {
                ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Product Image</th>
                                    <th>Quantity</th>
                                    <th>Unit Price (RM)</th>
                                    <th>Total Price (RM)</th>
                                    <th>Delivery Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                while($order = mysqli_fetch_assoc($orders)) {
                                    // Fetch product details based on product_ID
                                    $product_id = $order['product_ID'];
                                    $product_query = mysqli_query($conn, "SELECT * FROM product WHERE product_ID = $product_id");
                                    $product = mysqli_fetch_assoc($product_query);
                                ?>
                                    <tr>
                                        <td><?= $product['productName'] ?></td>
                                        <td><img style="width:100px" src="../<?php echo $product['productImage']; ?>" /></td>
                                        <td><?= $order['quantity'] ?></td>
                                        <td><?= $order['price'] ?></td>
                                        <td><?= $order['price']*$order['quantity'] ?></td>
                                        <td><?= $order_data['delivery_status'] ?></td>
                                        <td style="width: 300px">
                                            <!-- Delete -->
                                            <form class="d-inline" method="post" action="order_delete.php" onsubmit="return confirm('Are you sure you want to delete?')">
                                                <input type="hidden" name="order_paid_id" value="<?= $order['order_paid_ID'] ?>">
                                                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                            <!-- Edit -->                                            
                                            <a href="order_edit.php?order_id=<?= $order_id ?>" class="btn btn-warning">Edit</a>                         
                                        </td>
                                    </tr>
                                <?php 
                                } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                    } else {
                        echo '<h4 class="mb-0"> No Records Found</h4>';
                    }
                } else {
                    echo '<h4 class="mb-0">No order ID provided in URL</h4>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php include('includes/footer.php'); ?>
