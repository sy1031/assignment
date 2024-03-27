<?php
include 'config/function.php';

// Check if product ID is provided in the URL
if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];

    // Fetch product details based on the product ID
    $product_query = mysqli_query($conn, "SELECT * FROM product WHERE product_ID = '$product_id'");
    $product = mysqli_fetch_assoc($product_query);

    if(!$product){
        echo "Product not found.";
        exit();
    }
}else{
    echo "Product ID not provided.";
    exit();
}

if(isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    $product_price = $_POST['product_price'];

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
    <title><?php echo $product['productName'] ?> Details</title>

    <!-- CSS file -->
    <link rel="stylesheet" href="css/style.css">
    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .edit_form {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .edit_form button {
            flex: 1;
            margin: 5px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }
        .edit_form button:hover {
            background-color: #45a049;
        }
    </style>

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
        <div class="product_details">
            <img src="<?php echo $product['productImage'] ?>" alt="<?php echo $product['productName'] ?>" style="width: 420px; height: auto;">
            <h2><?php echo $product['productName'] ?></h2>
            <h6><?php echo $product['productDescription'] ?></h6>
            <div class="price">Price: RM<?php echo $product['productPrice'] ?></div>
            <div class="brand">Brand: <?php echo $product['productBrand'] ?></div>
            <div class="availability">Availability: <?php echo $product['productAvailability'] ? 'Out of Stock' : 'In Stock'?></div>
            <div class="stock">Stock: <?php echo $product['productQuantity'] ?></div>
            <form method="post" action="shop_products_details.php?product_id=<?php echo $product['product_ID'] ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_ID'] ?>"> 
                <input type="hidden" name="product_price" value="<?php echo $product['productPrice'] ?>"> 
                <input type="hidden" name="product_image" value="images/<?php echo $product['productImage'] ?>">
                <input type="submit" class="submit_btn cart_btn" value="Add to Cart" name="add_to_cart">
            </form>
        </div>
    </div>
</body>
</html>
