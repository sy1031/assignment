<header class="header">
    <div class="header_body">
        <a href="index.php" class="logo">TechnoBling</a>
        <nav class="navbar">
            <a href="order.php">Order Details</a>
            <a href="view_products.php">View Product</a>
            <a href="shop_products.php">Shop</a>
        </nav>

        <!-- select query -->
        <?php
        include 'auth.php';
        
        $customer_id = $_SESSION['customer_id'];
        // $get_order_id_query = mysqli_query($conn, "SELECT order_ID FROM `order` WHERE customer_ID = '$customer_id'");
        // $order_row = mysqli_fetch_assoc($get_order_id_query);
        // $order_id = $order_row['order_ID'];

        $select_product=mysqli_query($conn, "SELECT * FROM `order_item` WHERE customer_ID = '$customer_id'") or die('query failed');
        $row_count=mysqli_num_rows($select_product);
        ?>

        <!-- Shopping cart icon -->
        <a href="cart.php" class="cart"><i class="fa-solid fa-cart-shopping"></i><span><sup><?php echo $row_count?></sup></span></a>
        <!-- <div id="menu-btn" class="fas fa-bars"></div> -->

    </div>
</header>