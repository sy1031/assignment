<?php
include 'config/function.php';
include 'config/dbcon.php';

$display_all_products = true; // Flag to determine whether to display all products or just search results

if(isset($_POST['add_to_cart'])){
    // Your existing code for adding to cart
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Products</title>

    <!-- CSS file -->
    <link rel="stylesheet" href="css/style.css">
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
        <form method="POST">
            <div class="search_bar">
                <input type="text" name="search" placeholder="Search products..." style="width: 250px;">
                <input type="submit" name="searchProduct" value="Search">
            </div>
        </form>
        <?php
        if(isset($_POST['searchProduct']))
        {
            $search = $_POST['search'];
            $query = "SELECT * FROM product where productName LIKE '%$search%'";
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) > 0){

                while($row = mysqli_fetch_array($result)){
                    ?>
                    <form action="" method="POST">
                        <div class="edit_form">
                            <img src="<?php echo $row['productImage'] ?>" alt="" style="width: 180px; height: auto;">
                            <a href="shop_products_details.php?product_id=<?php echo $row['product_ID'] ?>" style="color: inherit;">
                                <h4><?php echo $row['productName'] ?></h4>
                            </a>
                            <div class="price">Price: RM<?php echo $row['productPrice'] ?></div>
                            <div class="availability">Availability: <?php echo $row['productAvailability'] ? 'Out of Stock' : 'In Stock'?></div>
                            <input type="hidden" name="product_id" value="<?php echo $row['product_ID'] ?>">
                            <input type="hidden" name="product_price" value="<?php echo $row['productPrice'] ?>">
                            <input type="hidden" name="product_image" value="images/<?php echo $row['productImage'] ?>">
                            <input type="submit" class="submit_btn cart_btn" value="Add to Cart" name="add_to_cart">
                        </div>
                    </form>
                    <?php
                }
                $display_all_products = false; // Set flag to false to only display search results
            } else {
                echo "NO PRODUCTS FOUND.";
            }
        }

        ?>
        <?php
        if($display_all_products){
            ?>
            <section class="products">
                <div class="product_container" id="post_list">
                    <?php
                    $select_products=mysqli_query($conn, "SELECT p.*, c.categoryStatus FROM product p JOIN category c ON p.category_ID = c.category_ID");
                    if(mysqli_num_rows($select_products) > 0){
                        while ($fetch_product=mysqli_fetch_assoc($select_products)){
                            if ($fetch_product['productAvailability'] != 1 && $fetch_product['categoryStatus'] != 1) { // Check if product is available, if = 0 only will show products, if = 1 skip retrieve product info
                                ?>
                                <form method="post" action="shop_products.php">
                                    <div class="edit_form">

                                        <img src="<?php echo $fetch_product['productImage'] ?> " alt="" style="width: 180px; height: auto;">
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
                        }
                    } else {
                        echo "No Products Available";
                    }
                    ?>
                </div>
            </section>
            <?php
        }
        ?>
    </div>
    <?php include 'footer.php'?>
</body>
</html>
