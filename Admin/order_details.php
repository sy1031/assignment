<?php 
include('Includes/header.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Admin</title>
</head>
<body>
    <div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Orders List
                    <a href="javascript:history.back()" class="btn btn-primary float-end">Back</a>
                </h4>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>
                <?php
                // Check if the order_ID is provided in the URL parameter
                if(isset($_GET['order_id'])) {
                    $order_id = $_GET['order_id'];

                    // Fetch order details based on the specific order_ID
                    $orders = mysqli_query($conn, "SELECT * FROM order_paid WHERE order_ID = $order_id");

                    if(!$orders){
                        echo '<h4>Something Went Wrong!</h4>';
                        return false;
                    }

                    if (mysqli_num_rows($orders) > 0) {
                ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
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
                                        <td><?= $order['quantity'] ?></td>
                                        <td><?= $order['price'] ?></td>
                                    </tr>
                                <?php 
                                } // end while
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
