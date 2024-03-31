<?php

// Including the required functions file
require '../config/function.php';

// Validating and getting the product ID from the URL parameter
$paramId = checkParamId('product_ID');

// Checking if the parameter ID is numeric
if(is_numeric($paramId)){

    // Validating the product ID
    $productId = validate($paramId);

    // Getting the product details by product ID
    $product = getByProductId('product', $productId);

    // Checking if the product status is 200 (OK)
    if($product['status'] == 200)
    {
        // Deleting the product from the database
        $result = deleteProduct('product', $productId);

        // Checking if the product was deleted successfully
        if($result){
            // Deleting the product image file
            $deleteImage = "../".$product['data']['image'];
            if(file_exists($deleteImage)){
                unlink($deleteImage);
            }
            redirect('product.php', 'Product Deleted Successfully.');
        } elseif ($result == false) {
            redirect('product.php', 'Unable to delete product. There are associated Order records.');
        } else {
            redirect('product.php', 'Something Went Wrong!');
        }
    } else {
        redirect('product.php', $product['message']);
    } 
} else {
    redirect('product.php', 'Something Went Wrong!');
}

?>