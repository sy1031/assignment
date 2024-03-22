<?php 
include('Includes/header.php'); 
?>

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
                    ?>
                     <h6 style="margin:5px">Order ID: <?php echo $order_id; ?> </h6>
                    <?php

                    if (mysqli_num_rows($orders) > 0) {
                ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
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
                                        <td><?= $order['quantity'] ?></td>
                                        <td><?= $order['price'] ?></td>
                                        <td style="width: 500px">
                                            <form class="d-inline" method="post" action="order_delete.php" onsubmit="return confirm('Are you sure you want to delete?')">
                                                <input type="hidden" name="order_paid_id" value="<?= $order['order_paid_ID'] ?>">
                                                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                            <button class="btn btn-warning btn-sm edit-btn d-inline" data-order-id="<?= $order_id ?>" data-order-paid-id="<?= $order['order_paid_ID'] ?>">Edit</button>
                                            <form class="edit-form" method="post" action="order_edit.php" style="display: none;">
                                                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                                                <input type="hidden" name="order_paid_id" value="<?= $order['order_paid_ID'] ?>">
                                                <label for="quantity">Quantity:</label>
                                                <input type="number" id="quantity" name="quantity" value="<?= $order['quantity'] ?>" min="1" style="margin-top:20px; width: 100px;">
                                                <button class="btn btn-success btn-sm edit-btn" type="submit">Update Quantity</button>
                                            </form>                                     
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

<script>
    // JavaScript code to handle edit button click event
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const orderId = button.getAttribute('data-order-id');
            const orderPaidId = button.getAttribute('data-order-paid-id');
            const editForm = button.nextElementSibling;
            const isDisplayed = editForm.style.display === 'block';
            const otherEditForms = document.querySelectorAll('.edit-form');
            otherEditForms.forEach(form => {
                form.style.display = 'none';
            });
            editForm.style.display = isDisplayed ? 'none' : 'block';
        });
    });
</script>