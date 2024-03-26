<?php
include 'config/function.php';

// Fetch user's ID of the logged-in user from the session data
$user_id = $_SESSION['loggedInUser']['user_ID'];

// Fetch user's address from the customer table
$address_query = mysqli_query($conn, "SELECT address FROM customer WHERE user_ID = $user_id");
$user_address = mysqli_fetch_assoc($address_query)['address'];

// Determine the filter condition based on the button clicked
$filter_condition = isset($_GET['filter']) ? $_GET['filter'] : 'to_receive';

// Fetch order details based on the filter condition
$order_query = mysqli_query($conn, "SELECT order_ID, total_amount, order_date, order_status, delivery_status FROM `order` WHERE user_ID = $user_id AND order_status = 'success' AND delivery_status " . ($filter_condition === 'to_receive' ? "!= 'delivered'" : "= 'delivered'"));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>

    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <!-- include header -->
    <?php include 'header.php'?>

    <div class="order">
        <h1 class="block-heading">Order Tracking</h1>
        <!-- Choose display ToRecieve/Completed order list -->
        <div class="choose_btn">
            <a href="?filter=to_receive" class="single-choose-btn"><i class="fas fa-spinner fa-spin"></i> To Receive</a>
            <a href="?filter=completed" class="single-choose-btn">Completed <i class="fas fa-check-square"></i></a>
        </div>
        <div >
            <!-- Check if there are order -->
            <?php if(mysqli_num_rows($order_query) == 0): ?>
                <h3 style="text-align:center; margin-bottom:800px; margin-top:50px">Empty</h3>
            <?php else: 
                $num = 1;
                // Display order list
                while ($order_row = mysqli_fetch_assoc($order_query)) {?>
                    <div class="card px-2 ">
                        <div class="card-header bg-white ">
                            <!-- Order info -->
                            <div class="row align-items-center ">
                                <div class="col">
                                    <p class="text-muted mb-0">Order ID: <span class="font-weight-bold text-dark"><?php echo $order_row['order_ID']; ?></span></p> 
                                    <p class="text-muted mb-0">Place On: <span class="font-weight-bold text-dark"><?php echo $order_row['order_date']; ?></span></p>
                                    <p class="text-muted mb-0">Address: <span class="font-weight-bold text-dark"><?php echo $user_address; ?></span></p> <!-- Display user's address -->
                                </div>
                                <!-- Button to view more details of that order -->
                                <div class="col-auto">
                                    <h6 class="mb-0">
                                        <form action="order_details.php" method="get">
                                            <input type="hidden" name="order_id" value="<?php echo $order_row['order_ID']; ?>">
                                            <button class="btn btn-link detail_btn" type="submit"><i class="fas sfa-list"></i></button>
                                        </form>                                       
                                    </div>
                                </div>
                            </div>
                            <!-- Order tracking -->
                            <div class="card-body">
                                <div class="media flex-column flex-sm-row">
                                    <div class="media-body ">
                                        <h4 class="mt-3 mb-4 bold"> <span class="mt-5">RM</span><?php echo $order_row['total_amount']; ?></h4>
                                        <p class="text-muted">Tracking Status:</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row px-3">
                                <div class="col">
                                    <ul id="progressbar">
                                        <?php
                                        // Determine the step based on delivery status
                                        $delivery_status = $order_row['delivery_status'];
                                        $step1_class = ($delivery_status === 'paid' || $delivery_status === 'processing' || $delivery_status === 'shipped' || $delivery_status === 'delivered') ? 'step0 active' : 'step0';
                                        $step2_class = ($delivery_status === 'shipped' || $delivery_status === 'delivered') ? 'step0 active text-center' : 'step0';
                                        $step3_class = ($delivery_status === 'delivered') ? 'step0 active text-right' : 'step0';
                                        ?>
                                        <!-- Display the delivery status in progress bar-->
                                        <li class="<?php echo $step1_class; ?>" id="step1">PAID</li>
                                        <li class="<?php echo $step2_class; ?>" id="step2">SHIPPED</li>
                                        <li class="<?php echo $step3_class; ?>" id="step3">DELIVERED</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            endif; ?>
        </div>
    </div>

    <!-- include footer -->
    <?php include 'footer.php'?>
</body>
</html>
