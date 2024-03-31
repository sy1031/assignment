<?php

include('../config/function.php');

// Check if the 'saveCategory' form is submitted
if (isset($_POST['saveCategory'])) {
    // Validate and retrieve category name, description, and status from the form
    $name = validate($_POST['categoryName']);
    $description = validate($_POST['categoryDescription']);
    $status = isset($_POST['categoryStatus']) == true ? 1 : 0;

    // Prepare category data
    $data = [
        'categoryName' => $name,
        'categoryDescription' => $description,
        'categoryStatus' => $status
    ];

    // Insert category data into the database
    $result = insert('category', $data);

    // Redirect with success or error message
    if ($result) {
        redirect('category.php', 'Category Created Successfully!');
    } else {
        redirect('category_create.php', 'Something Went Wrong!');
    }
}

// Check if the 'updateCategory' form is submitted
if (isset($_POST['updateCategory'])) {
    
     // Validate and retrieve category ID, name, description, and status from the form
    $categoryId = validate($_POST['category_ID']);
    $name = validate($_POST['categoryName']);
    $description = validate($_POST['categoryDescription']);
    $status = isset($_POST['categoryStatus']) == true ? 1 : 0;
    
     // Prepare updated category data
    $data = [
        'categoryName' => $name,
        'categoryDescription' => $description,
        'categoryStatus' => $status
    ];

    // Update category data in the database
    $result = updateCategory('category', $categoryId, $data);
    
    // Redirect with success or error message
    if ($result) {
        redirect('category.php?category_ID=' . $categoryId, 'Category Updated Successfully!');
    } else {
        redirect('category.php?category_ID=' . $categoryId, 'Something Went Wrong!');
    }
}

// Check if the 'saveProduct' form is submitted
if (isset($_POST['saveProduct'])) {

    // Validate and retrieve product details from the form
    $category_id = validate($_POST['category_ID']);
    $name = validate($_POST['productName']);
    $description = validate($_POST['productDescription']);
    $brand = validate($_POST['productBrand']);
    $price = validate($_POST['productPrice']);
    $quantity = validate($_POST['productQuantity']);
    $status = isset($_POST['productAvailability']) == true ? 1 : 0;

    // If product out of stock, automatically hide the product
    if ($quantity == 0) {
        $status = 1;
    }

    // Upload product image and save image path
    if ($_FILES['productImage']['size'] > 0) {
        $path = "../images/";
        $image_ext = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;

        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $path . "/" . $filename)) {
            $finalImage = "images/" . $filename;
            
        }else{
            // Debugging message
            redirect('product.php', "Failed to move uploaded file to destination directory."); 
        $finalImage = '';
        }
    } else {
    // No Product Image if no image is uploaded
    $finalImage = '';
    }

    // Prepare product data
    $data = [

        'category_ID' => $category_id,
        'productName' => $name,
        'productDescription' => $description,
        'productBrand' => $brand,
        'productPrice' => $price,
        'productQuantity' => $quantity,
        'productImage' => $finalImage,
        'productAvailability' => $status,
    ];

     // Insert product data into the database
    $result = insert('product', $data);

    // Redirect with success or error message
    if ($result) {
        redirect('product.php', 'Product Created Successfully!');
    } else {
        redirect('product_create.php', 'Something Went Wrong!');
    }
}

// Check if the 'updateProduct' form is submitted
if (isset($_POST['updateProduct'])) {

    // Validate and retrieve product ID, category ID, name, description, price, quantity, status, and image from the form
    $product_ID = validate($_POST['product_ID']);

    $productData = getByProductId('product', $product_ID);
    if (!$productData) {
        redirect('product.php', 'No such product found.');
    }

    $category_id = validate($_POST['category_ID']);
    $name = validate($_POST['productName']);
    $description = validate($_POST['productDescription']);
    $price = validate($_POST['productPrice']);
    $quantity = validate($_POST['productQuantity']);
    $status = isset($_POST['productAvailability']) == true ? 1 : 0;

    // If product out of stock, automatically hide the product
    if ($quantity == 0) {
        redirect('product.php'. $item[$product_ID], 'Unable to Display Product, Product is Out of Stock!');
        $status = 1;
    }

    // Upload updated product image and save image path
    if ($_FILES['productImage']['size'] > 0) {
        $path = "../images/";
        $image_ext = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;

        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $path . "/" . $filename)) {
            $finalImage = "images/" . $filename;
            
        }else{
            // Debugging message
            redirect('product.php', "Failed to move uploaded file to destination directory."); 
        $finalImage = '';
        }

    } elseif (isset($_POST['oldProductImage'])) {
        // Use the old image if no new image was uploaded
        $finalImage = $_POST['oldProductImage'];
        
    } else {

    $finalImage = '';
    }

    // Prepare updated product data
    $data = [
        'category_ID' => $category_id,
        'productName' => $name,
        'productDescription' => $description,
        'productPrice' => $price,
        'productQuantity' => $quantity,
        'productImage' => $finalImage,
        'productAvailability' => $status
    ];

    // Update product data in the database
    $result = updateProduct('product', $product_ID, $data);

    // Redirect with success or error message
    if ($result) {
        redirect('product.php', 'Product Updated Successfully!');
    } else {
        redirect('product.php', 'Something Went Wrong!');
    }
}

?>