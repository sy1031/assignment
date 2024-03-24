<?php include('Includes/header.php'); ?>

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
                <h4 class="mb-0">Orders List</h4>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>
                <?php
                $orders = getOrderAll('order'); // Fetch orders from the 'order' table
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
                                    <th>Order ID</th>
                                    <th>User ID</th>
                                    <th>Order Date</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order) : ?>
                                    <tr>
                                        <td><?= $order['order_ID'] ?></td>
                                        <td><?= $order['user_ID'] ?></td>
                                        <td><?= $order['order_date'] ?></td>
                                        <td><?= $order['total_amount'] ?></td>
                                        <td>
                                            <a href="order_details.php?order_id=<?= $order['order_ID']; ?>" class="btn btn-success btn-sm">Details</a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php
                        } else {
                            ?>
                                <h4 class="mb-0"> No Records Found</h4>
                            <?php
                        }
                            ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php include('includes/footer.php'); ?>