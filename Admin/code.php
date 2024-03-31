<?php

include('../config/function.php');

if (isset($_POST['saveStaff'])) {
    $first_name = validate($_POST['first_name']);
    $last_name = validate($_POST['last_name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $username = validate($_POST['username']);
    $staff_ID = validate($_POST['staff_ID']); // Make sure you have the staff_id available

    if ($email != '' && $password != '') {
        $emailCheck = mysqli_query($conn, "SELECT * FROM staff WHERE email='$email'");
        if ($emailCheck) {
            if (mysqli_num_rows($emailCheck) > 0) {
                redirect('staff_create.php', 'Email Already used by another user.');
            }
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into staff table
        $staff_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone,
            'username' => $username
        ];
        $staff_result = insert('staff', $staff_data);

        if ($staff_result) {
            // Get the auto-generated staff_id from the inserted staff record
            $new_staff_id = mysqli_insert_id($conn);

            // Insert into user table with the retrieved staff_id
            $user_data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $bcrypt_password,
                'phone' => $phone,
                'username' => $username,
                'usertype' => 'staff', // Assuming 'staff' is the usertype for staff members
                'staff_ID' => $new_staff_id // Assign the staff_id
            ];
            $user_result = insert('user', $user_data);

            if ($user_result) {
                redirect('staff.php', 'Staff Created Successfully!');
            } else {
                redirect('staff_create.php', 'Something Went Wrong while inserting into the user table');
            }
        } else {
            redirect('staff_create.php', 'Something Went Wrong while inserting into the staff table');
        }
    } else {
        redirect('staff_create.php', 'Please fill required fields.');
    }
}


if (isset($_POST['updateStaff'])) {

    $staffId = validate($_POST['staff_ID']);

    // Retrieve existing staff data
    $staffData = getById('staff', $staffId);
    if ($staffData['status'] != 200) {
        redirect('staff_edit.php?id=' . $staffId, 'Staff not found.');
    }

    $first_name = validate($_POST['first_name']);
    $last_name = validate($_POST['last_name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $username = validate($_POST['username']);

    if ($password != '') {
        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);
    } else {
        $bcrypt_password = $staffData['data']['password'];
    }

    if ($username != '' && $email != '') {
        // Prepare data for updating staff
        $staff_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username
        ];

        // Update staff record
        $result_staff = update('staff', $staffId, $staff_data);

        // Prepare data for updating user
        $user_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
            'usertype' => 'staff', // Assuming 'staff' is the usertype for staff members
        ];

        // Update user record using staff_id
        $result_user = update('user', $staffId, $user_data);

        if ($result_staff && $result_user) {
            redirect('staff_edit.php?id=' . $staffId, 'Staff Updated Successfully!');
        } else {
            redirect('staff_edit.php?id=' . $staffId, 'Something Went Wrong');
        }
    } else {
        redirect('staff_edit.php?id=' . $staffId, 'Please fill required fields.');
    }
}

if (isset($_POST['updateUser'])) {
    $userId = validate($_POST['user_ID']);

    // Retrieve existing user data
    $userData = getById('user', $userId);
    if ($userData['status'] != 200) {
        redirect('user_edit.php?id=' . $userId, 'User not found.');
    }

    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    // Prepare data for updating user
    $user_data = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT) // Hash the new password
    ];

    // Update user record using user_id
    $result_user = update_user_pass('user', $userId, $user_data);

    if ($result_user) {
        // Update customer record with the same username
        $customer_data = [
            'username' => $username
        ];
        $result_customer = update_user_pass('customer', $userData['data']['user_ID'], $customer_data);

        // Update staff record with the same username (assuming you have a staff_ID available)
        $staff_data = [
            'username' => $username
        ];
        $result_staff = update_user_pass('staff', $userData['data']['staff_ID'], $staff_data);

        // Check if all updates were successful
        if ($result_customer && $result_staff) {
            redirect('user_edit.php?id=' . $userId, 'Username and Password Updated Successfully!');
        } else {
            redirect('user_edit.php?id=' . $userId, 'Something Went Wrong');
        }
    } else {
        redirect('user_edit.php?id=' . $userId, 'Error updating username and password.');
    }
}




if (isset($_POST['updateCustomer'])) {

    $customerId = validate($_POST['customer_ID']);

    // Retrieve existing customer data
    $customerData = getById('customer', $customerId);
    if ($customerData['status'] != 200) {
        redirect('customer_edit.php?id=' . $customerId, 'Customer not found.');
    }

    // Retrieve user ID associated with the customer ID
    $userId = $customerData['data']['user_ID'];

    $first_name = validate($_POST['first_name']);
    $last_name = validate($_POST['last_name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $username = validate($_POST['username']);
    $address = validate($_POST['address']);

    if ($username != '' && $email != '') {
        // Prepare data for updating customer
        $customer_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
            'address' => $address
        ];

        // Update customer record
        $result_customer = update('customer', $customerId, $customer_data);

        // Prepare data for updating user
        $user_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
        ];

        // Update user record using user_id
        $result_user = update('user', $userId, $user_data);

        if ($result_customer && $result_user) {
            redirect('customer_edit.php?id=' . $customerId, 'Customer Updated Successfully!');
        } else {
            redirect('customer_edit.php?id=' . $customerId, 'Something Went Wrong');
        }
    } else {
        redirect('customer_edit.php?id=' . $customerId, 'Please fill required fields.');
    }
}

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

if(isset($_POST['addCustomer']))
{
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) ? 1:0;
    
    if($name != ' ')
    {
        $emailCheck = mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('customers.php', 'Email Already Exists.');

            }
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status
        ];

        $result = insert('customers', $data); //help to insert record in customer table
        if($result){
            redirect('customers.php', 'Account Created Successfully!');
        }else{
            redirect('customers.php', 'Something Went Wrong!');
        }
    }
    else
    {
        redirect('customers.php', 'Please fill required fields.');
    }
}

if (isset($_POST['saveCustomer'])) {

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) ? 1:0;
    
    if($name != ' ')
    {
        $emailCheck = mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('customers.php', 'Please fill in required fields.');

            }
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status
        ];

        $result = insert('customers', $data); //help to insert record in customer table
        if($result){
            redirect('customers.php', 'Account Created Successfully!');
        }else{
            redirect('customers.php', 'Something Went Wrong!');
        }
    }
    else
    {
        redirect('customers.php', 'Please fill required fields.');
    }
}

if(isset($_POST['updateCustomer'])){

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) ? 1:0;
    
    if($name != ' ')
    {
        $emailCheck = mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email' AND id!='$customerId'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('customers_edit.php?id='.$customerId, 'Email Already Exists.');

            }
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status
        ];

        $result = update('customers', $customerId, $data); //help to insert record in customer table

        if($result){
            redirect('customers_edit.php?id='.$customerId, 'Profile Updated Successfully!');
        }else{
            redirect('customers_edit.php?id='.$customerId, 'Something Went Wrong!');
        }
    }
    else
    {
        redirect('customers_edit.php?id='.$customerId, 'Please fill required fields.');
    }

}


?>