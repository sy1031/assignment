<?php

if(isset($_SESSION['loggedInUser']['user_ID'])) {
    $user_id = $_SESSION['loggedInUser']['user_ID'];

    // Fetch username from the database
    $select_user = mysqli_query($conn, "SELECT username FROM user WHERE user_ID = '$user_id'") or die('query failed');
    $user_row = mysqli_fetch_assoc($select_user);
    $username = $user_row['username'];

    //Fetch products in cart
    $select_product = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_ID = '$user_id'") or die('query failed');
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
                    <li class="nav-item search" style="margin-top: -4px;">
                        <!-- <form action="search.php" method="GET" class="nav-link" style="display: flex;">
                            <input type="text" name="query" placeholder="Search products..." class="search-input" style="padding: 3px; border-radius: 5px 0 0 5px; border: 1px solid #ccc;">
                            <button type="submit" class="search-button" style="background-color: #fff; color: #000; border: none; border-radius: 0 5px 5px 0; padding: 3px 5px; width: 40px;">
                                <i class="fa-solid fa-magnifying-glass" style="color: #000; font-size: 1rem; transition: transform 0.3s;"></i>
                            </button>
                            <div id="searchResults" class="search-results" style="display: none;"></div>
                        </form> -->
                        <style>
                            .search-button:hover i {
                                transform: scale(1.1); /* Enlarge the icon by 10% on hover */
                            }
                        </style>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop_products.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order.php">Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="payment_history.php">Payment</a> 
                    </li>
                    <li class="nav-item logout">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                    <li class="nav-item cart">
                        <a href="cart.php" class="nav-link">
                            <?php echo $username." 's "; ?>
                            <i class="fa-solid fa-cart-shopping"></i><span><sup><?php echo $row_count?></sup></span>
                        </a>
                    </li>
                    </ul>
                </nav>
            </div>
        </div>
  </div>
</nav>
