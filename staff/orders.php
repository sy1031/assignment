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
                <!-- Add filter dropdown -->
                <form method="GET" action="" style="margin: 20px 5px">
                    <label for="status" style="font-size:18px; font-weight:bold; margin-right:10px">Status:</label>
                    <select name="status" id="status" style="height:38px; width:200px; border-radius:5px">
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="success">Success</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="margin-bottom:4px; width:100px; border-radius:5px">Filter</button>
                </form>

                <?php
                // Check if filter is applied
                $filterStatus = isset($_GET['status']) ? $_GET['status'] : 'all';

                // Modify the SQL query based on the selected status
                $orders = ($filterStatus === 'all') ? getOrderAll('order') : getOrderFilteredByStatus('order', $filterStatus);

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
                                    <th>Username</th>
                                    <th>Order Date/Time</th>
                                    <th>Total Amount (RM)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order) : ?>
                                    <?php
                                    // Fetch username based on user_ID
                                    $user = getUserByID($order['user_ID']);
                                    $username = $user['username'] ?? 'Unknown';
                                    ?>
                                    <tr>
                                        <td><?= $order['order_ID'] ?></td>
                                        <td><?= $order['user_ID'] ?></td>
                                        <td><?= $username ?></td>
                                        <td><?= $order['order_date'] ?></td>
                                        <td><?= $order['total_amount'] ?></td>
                                        <td><?= $order['order_status'] ?></td>
                                        <td>
                                            <a href="order_details.php?order_id=<?= $order['order_ID']; ?>" class="btn btn-success">Details</a>
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
