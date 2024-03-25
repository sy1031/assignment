<?php

require '../config/function.php';

$paramId = checkParamId('product_ID');

if(is_numeric($paramId)){

    $productId = validate($paramId);
    $product = getByProductId('product', $productId);

    if($product['status'] == 200)
    {
        $result = deleteProduct('product', $productId);

        if($result){
            $deleteImage = "../".$product['data']['image'];
            if(file_exists($deleteImage)){
                unlink($deleteImage);
            }
            redirect('product.php', 'Product Deleted Successfully.');
        }else{
            redirect('product.php', 'Something Went Wrong1!');
        }
    }
    else{
        redirect('product.php', $product['message']);
    } 
}
else{
    redirect('product.php', 'Something Went Wrong2!');
}

?>