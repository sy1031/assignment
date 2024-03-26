<?php 
include 'config/function.php';

// Fetch user's ID of the logged-in user from the session data
$user_id = $_SESSION['loggedInUser']['user_ID'];

//Update query
if(isset($_POST['update_product_quantity'])){
    // Retrieve the updated quantity and cart ID
    $update_value=$_POST['update_quantity'];
    $update_id=$_POST['update_quantity_id'];

    // Update the quantity in the cart table
    $update_quantity_query = mysqli_query($conn, "UPDATE `cart` SET quantity = $update_value WHERE cart_ID = $update_id");

    if (!$update_quantity_query) {
        echo "Error updating quantity: " . mysqli_error($conn);
    } else {
        header('location: cart.php');
    }
}

//Remove query
if(isset($_GET['remove'])){
    // Retrieve the cart ID of the product to be removed
    $remove_id = $_GET['remove'];

    // Remove the product from the cart table
    $delete_query = mysqli_query($conn, "DELETE FROM `cart` WHERE cart_ID = $remove_id");
    header('location: cart.php');
}

// Checkout
if(isset($_POST['checkout'])){
    // Get grand total
    $grand_total = $_POST['grand_total'];

    // Insert order into order table
    $insert_order_query = mysqli_query($conn, "INSERT INTO `order` (user_ID, total_amount, order_status) VALUES ('$user_id', '$grand_total', 'pending')");
    if($insert_order_query) {
        // Get the order ID of the inserted order
        $order_id = mysqli_insert_id($conn);
        
        // Fetch cart data
        $select_cart_products = mysqli_query($conn, "SELECT oi.cart_ID, oi.product_ID, oi.quantity, oi.price, p.productName, p.productPrice, p.productImage 
        FROM cart oi 
        JOIN product p ON oi.product_ID = p.product_ID 
        WHERE oi.user_ID = $user_id");
        
        // Initialize an empty array to store cart data
        $cart_data = array();
        
        // Fetch each row of cart data and store it in the array
        while($fetch_cart_products = mysqli_fetch_assoc($select_cart_products)) {
            $cart_data[] = $fetch_cart_products;
        }
        
        $_SESSION['cart'] = $cart_data;

        // Insert order items into order_paid table
        $select_cart_products = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_ID = $user_id");
        while($fetch_cart_products=mysqli_fetch_assoc($select_cart_products)) {
            $product_ID = $fetch_cart_products['product_ID'];
            $quantity = $fetch_cart_products['quantity'];
            $price = $fetch_cart_products['price'];

            mysqli_query($conn, "INSERT INTO `order_paid` (order_ID, product_ID, quantity, price) VALUES ('$order_id', '$product_ID', '$quantity', '$price')");
        }
        
        header("Location: checkout.php?order_id=$order_id");
        exit();
    } else {
        echo "Error creating order: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>

    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- include header -->
    <?php include 'header.php'?>

    <div class="container_cart">
        <section class="shopping-cart">
            <h1 class="block-heading">Shopping Cart</h1>

            <div class="content">
                <?php
                    // Fetch the cart details from table
                    $select_cart_products = mysqli_query($conn, "SELECT oi.cart_ID, oi.product_ID, oi.quantity, oi.price, p.productName, p.productPrice, p.productImage, c.categoryName
                    FROM cart oi 
                    JOIN product p ON oi.product_ID = p.product_ID 
                    JOIN category c ON p.category_ID = c.category_ID
                    WHERE oi.user_ID = $user_id");

                    // Check if there are products in the cart
                    if(mysqli_num_rows($select_cart_products) > 0){
                ?>
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <?php
                            $num = 1;
                            $grand_total = 0.00;
                            // Display all products
                            while($fetch_cart_products=mysqli_fetch_assoc($select_cart_products)) {
                        ?>
                        <div class="items">
                            <div class="product">
                                <div class="row">
                                    <!-- Product Image -->
                                    <div class="col-md-4">
                                        <img src="images/<?php echo $fetch_cart_products['productImage']?>" alt="<?php echo $fetch_cart_products['productName']?>" style="width: 180px; height: auto;">
                                    </div>
                                    <!-- Product info -->
                                    <div class="col-md-8">
                                        <div class="info">
                                            <div class="row">
                                                <div class="col-md-5 product-name">
                                                    <div class="product-name">
                                                        <a href=""><?php echo $fetch_cart_products['productName']?></a>
                                                        <div class="product-info">
                                                            <div>Category: <span class="value"><?php echo $fetch_cart_products['categoryName']?></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Update quantity of product -->
                                                <div class="col-md-4 quantity">
                                                    <form action="cart.php" method="post">
                                                        <label for="quantity" class="input-group-prepend">Quantity:</label>
                                                        <div class="input-group">
                                                            <input id="quantity" type="number" name="update_quantity"  min=1 value="<?php echo $fetch_cart_products['quantity'] ?>" class="form-control quantity-input">
                                                            <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart_products['cart_ID'] ?>">
                                                            <div class="input-group-append">
                                                                <button type="submit" name="update_product_quantity" class="btn">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-3 price">
                                                    <span>RM<?php echo $fetch_cart_products['productPrice']?></span>
                                                </div>
                                                <!-- Remove product from cart -->
                                                <div class="col-md-3 remove">
                                                    <a href="cart.php?remove=<?php echo $fetch_cart_products['cart_ID']?>"
                                                    onclick = "return confirm('Are you sure you want to delete?')">                           
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                $grand_total += ($fetch_cart_products['productPrice'] * $fetch_cart_products['quantity']);
                                $num++;
                            }
                        ?>
                    </div>
                    <!-- Summary price display  -->
                    <div class="col-md-12 col-lg-4">
                        <div class="summary">
                            <h3>Summary</h3>
                            <div class="summary-item"><span class="text">Grand total</span><span class="price">RM<?php echo $grand_total?></span></div>
                            <div class="summary-item"><span class="text">Shipping</span><span class="price">RM0</span></div>
                            <div class="summary-item"><span class="text">Total</span><span class="price" style="font-weight:bold">RM<?php echo $grand_total?></span></div>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="grand_total" value="<?php echo $grand_total; ?>">
                                <button type="submit" class="btn btn-primary btn-lg" name="checkout">Proceed to checkout</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        echo "<h3 style='text-align: center; margin-bottom:800px; padding:0px 300px'>Your shopping cart is empty.</h3>";
                    }
                ?>
            </div>
        </section>
    </div>

    <!-- include footer -->
    <?php include 'footer.php'?>
</body>

</html>