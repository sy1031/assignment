<?php
include 'config/function.php';

//Add to cart
if(isset($_POST['add_to_cart'])){
    // Get product ID and price
    $product_id = $_POST['product_id'];
    $product_price = $_POST['product_price'];

    // Get user ID from session
    $user_id = $_SESSION['loggedInUser']['user_ID'];

    // Check if the product already exists
    $existing_item_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_ID = '$user_id' AND product_ID = '$product_id'");
    if(mysqli_num_rows($existing_item_query) > 0) {
        // Product already exists, update the quantity
        $existing_item_row = mysqli_fetch_assoc($existing_item_query);
        $existing_quantity = $existing_item_row['quantity'];
        $new_quantity = $existing_quantity + 1;

        $update_query = mysqli_query($conn, "UPDATE cart SET quantity = '$new_quantity' WHERE user_ID = '$user_id' AND product_ID = '$product_id'");

        if($update_query) {
            $display_message = "Quantity updated successfully.";
        } else {
            $display_message = "Error updating quantity: " . mysqli_error($conn);
        }
    } else {
        // Product does not exist, add a new entry
        $insert_query = mysqli_query($conn, "INSERT INTO cart (user_ID, product_ID, quantity, price) VALUES ('$user_id', '$product_id', 1, '$product_price')");

        if($insert_query) {
            $display_message = "Product added to cart successfully.";
        } else {
            $display_message = "Error adding product to cart: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Products</title>

    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <?php include 'header.php'?>

    <div class="container">
        <?php
        if(isset($display_message)){
            echo "<div class='display_message'>
            <span>$display_message</span>
            <i class='fas fa-times' onClick='this.parentElement.style.display=`none`';></i>
            </div>";
        }
        ?>
            <section class="products">
            <div class="product_container">
                <?php
                $select_products=mysqli_query($conn, "Select * from `product`");
                if(mysqli_num_rows($select_products) > 0){
                    while ($fetch_product=mysqli_fetch_assoc($select_products)){
                    ?>
                        <form method="post" action="shop_products.php">
                        <div class="edit_form">
                            <img src="images/<?php echo $fetch_product['productImage'] ?> " alt="" style="width: 180px; height: auto;">
                            <a href="shop_products_details.php?product_id=<?php echo $fetch_product['product_ID'] ?>" style="color: inherit;">
                                <h4><?php echo $fetch_product['productName'] ?></h4>
                            </a>
                            <div class="price">Price: RM<?php echo $fetch_product['productPrice'] ?></div>
                            <div class="availability">Availability: <?php echo $fetch_product['productAvailability'] ? 'Out of Stock' : 'In Stock'?></div>
                            <input type="hidden" name="product_id" value="<?php echo $fetch_product['product_ID'] ?>"> 
                            <input type="hidden" name="product_price" value="<?php echo $fetch_product['productPrice'] ?>"> 
                            <input type="hidden" name="product_image" value="images/<?php echo $fetch_product['productImage'] ?> "> 
                            <input type="submit" class="submit_btn cart_btn" value="Add to Cart" name="add_to_cart">
                        </div>
                        </form>
                <?php
                    }
                } else {
                    echo "No Products Available";
                }
                ?>
            </div>
        </section> 
    </div>
    <!-- include footer -->
    <?php include 'footer.php'?>
</body>
</html>