<?php
include 'config/function.php';

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    exit();
}

// Fetch order ID from URL parameter
if(isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $order_query = mysqli_query($conn, "SELECT op.product_ID, op.quantity, op.price, p.productName, p.productImage 
                                        FROM order_paid op 
                                        INNER JOIN product p ON op.product_ID = p.product_ID
                                        WHERE op.order_ID = $order_id");

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Details</title>
        <!-- CSS file -->
        <link rel="stylesheet" href="css/style.css">
        <!-- font awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <!-- include header -->
        <?php include 'header.php'?>

        <div class="container">
            <h1 class="heading">Order Details</h1>
            <?php if(mysqli_num_rows($order_query) == 0): ?>
                <h3 style="text-align:center">No products found for this order</h3>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product Name</th>
                            <th>Product Image</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Price (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $num = 1;
                        while ($order_row = mysqli_fetch_assoc($order_query)) {
                        ?>
                            <tr>
                                <td><?php echo $num++; ?></td>
                                <td><?php echo $order_row['productName']; ?></td>
                                <td><img style="width:100px" src="images/<?php echo $order_row['productImage']; ?>" /></td>
                                <td><?php echo $order_row['quantity']; ?></td>
                                <td><?php echo $order_row['price']; ?></td>
                                <td><?= $order_row['price']*$order_row['quantity'] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </body>
    </html>

<?php
} else {
    echo '<h4 class="mb-0">No order ID provided in URL</h4>';
}
?>
