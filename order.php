<?php
include 'config/function.php';

$user_id = $_SESSION['loggedInUser']['user_id'];

// Fetch order details
$order_query = mysqli_query($conn, "SELECT order_ID, total_amount, order_date FROM `order` WHERE user_ID = $user_id");
$num = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>

    <!-- CSS file -->
    <link rel="stylesheet" href="css/style.css">
    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <!-- include header -->
    <?php include 'header.php'?>

    <div class="container">
        <h1 class="heading">Orders Tracking</h1>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Order ID</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
            while ($order_row = mysqli_fetch_assoc($order_query)) {
                ?>
                <tr>
                    <td><?php echo $num++; ?></td>
                    <td><?php echo $order_row['order_ID']; ?></td>
                    <td>RM<?php echo $order_row['total_amount']; ?></td>
                    <td><?php echo $order_row['order_date']; ?></td>
                    <td>
                        <form action="order_details.php" method="get">
                            <input type="hidden" name="order_id" value="<?php echo $order_row['order_ID']; ?>">
                            <button class="detail_btn" type="submit">Details</button>
                        </form>
                    </td>
                </tr>
                <?php
            }
                ?>
        </table>
    </div>
</tbody>
</body>
</html>
