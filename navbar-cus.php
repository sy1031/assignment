<?php

if(isset($_SESSION['loggedInUser']['user_ID'])) {
    $user_id = $_SESSION['loggedInUser']['user_ID'];

    $select_product = mysqli_query($conn, "SELECT * FROM `order_item` WHERE user_ID = '$user_id'") or die('query failed');
    $row_count = mysqli_num_rows($select_product);
} else {
    header("Location: login.php");
    exit;
}
?>
        
<nav class="navbar navbar-expand-lg bg-warning shadow">
  <div class="container">
    <a class="navbar-brand ps-5 text-dark" href="shop_products.php">
        <h2><span style="font-size: 24px;"><strong>L L B C</strong></span></h2>
    </a>
    <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-md bg-warning justify-content-end">
                    <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="order.php">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop_products.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="payment_history.php">Payment</a> 
                    </li>
                    <li class="nav-item logout">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                    <li class="nav-item cart">
                        <a href="cart.php" class="nav-link">
                            <i class="fa-solid fa-cart-shopping"></i><span><sup><?php echo $row_count?></sup></span>
                        </a>
                    </li>
                    </ul>
                </nav>
            </div>
        </div>
  </div>
</nav>
