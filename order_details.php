<?php
include 'config/function.php';

// Fetch order details
if(isset($_GET['order_id'])) {
    // Retrieve order ID from the URL parameter
    $order_id = $_GET['order_id'];

    // Fetch order details based on the order ID
    $order_query = mysqli_query($conn, "SELECT op.product_ID, op.quantity, op.price, p.productName, p.productImage
    FROM order_paid op 
    INNER JOIN product p ON op.product_ID = p.product_ID
    INNER JOIN `order` o ON op.order_ID = o.order_ID
    WHERE op.order_ID = $order_id");

} else {
    // Display if no order ID in the URL
    echo '<h4 class="mb-0">No order ID provided in URL</h4>';
}

$discounted_total_amount = $_SESSION['discounted_total_amount'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>

    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
    
<body class="od_body">
    <!-- include header -->
    <?php include 'header.php'?>

    <!-- Display order details based on order ID-->
    <div class="od_card mt-50 mb-50">
        <div class="col d-flex"><span class="text-muted" id="orderno">order #<?php echo $order_id; ?></span></div>
        <div class="gap">
            <div class="col-2 d-flex mx-auto"> </div>
        </div>
        <div class="main"><span id="sub-title"><p><b>Order Summary</b></p></span>
            <!-- Check if there are product -->
            <?php if(mysqli_num_rows($order_query) == 0): ?>
                <h3 style="text-align:center; margin-bottom:800px;">No products found for this order</h3>
            <?php else: ?>
                <?php
                // Display all order's details
                while ($order_row = mysqli_fetch_assoc($order_query)) {
                ?>
                    <div class="row row-main">
                        <div class="col-3"><img class="img-fluid" src="images/<?php echo $order_row['productImage']; ?>"></div>
                        <div class="col-6">
                            <div class="row d-flex">
                                <p><b><?php echo $order_row['productName']; ?></b></p>
                            </div>
                            <div class="row d-flex">
                                <p class="text-muted">Quantity: <?php echo $order_row['quantity']; ?></p>
                            </div>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <p><b><?php echo $order_row['price']; ?></b></p>
                        </div> 
                    </div>
                <?php 
                }
                endif; 
                ?>
            </div>
        </div>
    </div>

    <!-- include footer -->
    <?php include 'footer.php'?>
</body>
</html>


