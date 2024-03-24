<?php 
include 'config/function.php';

$user_id = $_SESSION['loggedInUser']['user_ID'];

//Update query
if(isset($_POST['update_product_quantity'])){
    $update_value=$_POST['update_quantity'];
    $update_id=$_POST['update_quantity_id'];

    $update_quantity_query = mysqli_query($conn, "UPDATE `order_item` SET quantity = $update_value WHERE order_item_ID = $update_id");

    if (!$update_quantity_query) {
        echo "Error updating quantity: " . mysqli_error($conn);
    } else {
        header('location: cart.php');
    }
}

//Remove query
if(isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    $delete_query = mysqli_query($conn, "DELETE FROM `order_item` WHERE order_item_ID = $remove_id");
    header('location: cart.php');
}

//Remove all query
if(isset($_GET['delete_all'])){
    // Get the customer's order_ID from the order table
    $get_order_id_query = mysqli_query($conn, "SELECT order_ID FROM `order` WHERE user_ID = '$user_id'");
    $order_row = mysqli_fetch_assoc($get_order_id_query);
    $order_id = $order_row['order_ID'];

    mysqli_query($conn, "DELETE FROM `order_item` WHERE user_ID = '$user_id'");
    header('location:cart.php');
}

// Checkout
if(isset($_POST['checkout'])){
    // Calculate grand total
    $grand_total = $_POST['grand_total'];

    // Insert order into order table
    $insert_order_query = mysqli_query($conn, "INSERT INTO `order` (user_ID, order_date, total_amount, order_status) VALUES ('$user_id', NOW(), '$grand_total', 'pending')");
    if($insert_order_query) {
        // Get the order ID of the inserted order
        $order_id = mysqli_insert_id($conn);
        
        // Fetch cart data from the database or any other source
        $select_cart_products = mysqli_query($conn, "SELECT oi.order_item_ID, oi.product_ID, oi.quantity, oi.price, p.productName, p.productPrice, p.productImage 
        FROM order_item oi 
        JOIN product p ON oi.product_ID = p.product_ID 
        WHERE oi.user_ID = $user_id");
        
        // Initialize an empty array to store cart data
        $cart_data = array();
        
        // Fetch each row of cart data and store it in the array
        while($fetch_cart_products = mysqli_fetch_assoc($select_cart_products)) {
            $cart_data[] = $fetch_cart_products;
        }
        
        // Store the cart data in a session variable
        $_SESSION['cart'] = $cart_data;

        // Insert order items into order_paid table
        $select_cart_products = mysqli_query($conn, "SELECT * FROM `order_item` WHERE user_ID = $user_id");
        while($fetch_cart_products=mysqli_fetch_assoc($select_cart_products)) {
            $product_ID = $fetch_cart_products['product_ID'];
            $quantity = $fetch_cart_products['quantity'];
            $price = $fetch_cart_products['price'];

            mysqli_query($conn, "INSERT INTO `order_paid` (order_ID, product_ID, quantity, price) VALUES ('$order_id', '$product_ID', '$quantity', '$price')");
        }
        
        // Redirect to checkout page with order ID
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
    <title>Cart Page</title>

    <!-- CSS file -->
    <link rel="stylesheet" href="css/style.css">
    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- include header -->
    <?php include 'header.php'?>

    <div class="container">
        <section class="shopping_cart">
            <h1 class="heading">My Cart</h1>

            <table>
                <?php
                $select_cart_products = mysqli_query($conn, "SELECT oi.order_item_ID, oi.product_ID, oi.quantity, oi.price, p.productName, p.productPrice, p.productImage 
                FROM order_item oi 
                JOIN product p ON oi.product_ID = p.product_ID 
                WHERE oi.user_ID = $user_id");
                
                $num = 1;
                $grand_total = 0;
                if(mysqli_num_rows($select_cart_products) > 0){
                    ?>
                    <a href="cart.php?delete_all" class="delete_all_btn" 
                        onclick = "return confirm('Are you sure you want to delete all items?')">
                        <i class="fas fa-trash"></i>Delete All
                    </a>
                    <?php
                    echo "<thead>
                            <th>No</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Price (RM)</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Action</th>
                          </thead>
                          <tbody>";
                    while($fetch_cart_products=mysqli_fetch_assoc($select_cart_products)) {
                        ?>
                        <tr>
                            <td><?php echo $num ?></td>
                            <td><?php echo $fetch_cart_products['productName']?></td>
                            <td><img src="images/<?php echo $fetch_cart_products['productImage']?>" alt="<?php echo $fetch_cart_products['productName']?>" style="width: 200px; height: auto;"></td>                            
                            <td><?php echo $fetch_cart_products['productPrice']?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" value="<?php echo $fetch_cart_products['order_item_ID']?>" name="update_quantity_id">
                                <div class="quantity_box">
                                <input type="number" min="1" value="<?php echo $fetch_cart_products['quantity'] ?>" name="update_quantity">                                
                                <input type="submit" class="update_quantity" value="Update" name="update_product_quantity">
                                </div>
                                </form>
                            </td>
                            <td>RM<?php echo $subtotal=($fetch_cart_products['productPrice']
                            * $fetch_cart_products['quantity'])?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $fetch_cart_products['order_item_ID']?>"
                                onclick = "return confirm('Are you sure you want to delete?')">                           
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    $grand_total=$grand_total+($fetch_cart_products['price']*$fetch_cart_products['quantity']);
                    $num++;
                    }
                    ?>
                    
                </tbody>
            </table>

            <!-- bottom area -->
            <form action="cart.php" method="post">
                <div class="table_bottom">
                    <h3 class="bottom_txt">Grand total: RM<span><?php echo $grand_total?></span></h3>
                    <div class="line"></div>
                    <input type="hidden" name="grand_total" value="<?php echo $grand_total; ?>">
                    <button type="submit" class="bottom_btn" name="checkout">Proceed to checkout</button>
                </div>
            </form>

            <?php
            } else {
                echo "<div class='empty_text'>Cart is Empty</div>";
            }
            ?>
        </section>
    </div>        
</body>
</html>