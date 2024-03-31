<?php
require '../config/function.php'; 

# To delete category

# Validate parameter ID for category deletion
$paramId = checkParamId('category_ID'); 
if(is_numeric($paramId)){

    $category_id = validate($paramId); // Sanitize the category ID
    $category = getByCategoryId("category", $category_id); // Retrieve category data by ID

    if($category['status'] == 200) // Check if category data retrieval was successful
    {
        $result = deleteCategory('category', $category_id); // Delete the category

        if($result) {
            // Redirect with success message if category deleted successfully
            redirect('category.php', 'Category deleted successfully.');
        } elseif($result == false) {
            // Redirect with error message if unable to delete due to associated product records
            redirect('category.php', 'Unable to delete category. There are associated Product records.');
        } else {
            // Redirect with error message if delete operation failed for other reasons
            redirect('category.php', 'Failed to delete category.');
        }
    } else {
        // Redirect with error message if category data retrieval failed
        redirect('category.php', $category['message']);
    }
} else {
    // Redirect with error message if parameter ID is not numeric
    redirect('category.php', 'Something Went Wrong!');
}

?>
