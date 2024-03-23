<?php

require '../config/function.php';

$paraResultId = checkParamId('category_ID');
if(is_numeric($paraResultId)){

    $categoryId = validate($paraResultId);
    
    $category = getById('category', $categoryId);

    if($category['CategoryStatus'] == 200)
    {
        $response = delete('category', $categoryId);
        if($response){
            redirect('category.php', 'Category Deleted Successfully.');
        }else{
            redirect('category.php', 'Something Went Wrong!');
        }
    }
    else{
        redirect('category.php', $category['message']);
    } 
}
else{
    redirect('category.php', 'Something Went Wrong!');
}

?>