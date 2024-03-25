<?php
require '../config/function.php';

$paramId = checkParamId('category_ID');
if(is_numeric($paramId)){

    $category_id = validate($paramId);
    $category = getByCategoryId("category", $category_id);

    if($category['status'] == 200)
    {
        $result = deleteCategory('category', $category_id);

        if($result) {
            redirect('category.php', 'Category deleted successfully.');
        } else {
            redirect('category.php', 'Failed to delete category.');
        }
    } else {
        redirect('category.php', $category['message']);
    }
} else {
    redirect('category.php', 'Something Went Wrong!');
}

?>
