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
        } elseif($result == false) {
            redirect('category.php', 'Unable to delete category. There are associated Product records.');
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
