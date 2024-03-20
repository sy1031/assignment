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



if (isset($_POST['saveCategory'])) {
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status
    ];
    $result = insert('categories', $data);
    if ($result) {
        redirect('categories.php', 'Category Created Successfully!');
    } else {
        redirect('categories_create.php', 'Something Went Wrong!');
    }
}

if (isset($_POST['updateCategory'])) {
    $categoryId = validate($_POST['categoryId']);

    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1 : 0;
    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status
    ];
    $result = update('categories', $categoryId, $data);
    if ($result) {
        redirect('categories_edit.php?id=' . $categoryId, 'Category Updated Successfully!');
    } else {
        redirect('categories_edit.php?id=' . $categoryId, 'Something Went Wrong!');
    }
}

if (isset($POST['saveProduct'])) {
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time() . '.' . $image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);

        $finalImage = "assets/uploads/products/" . $filename;
    } else {

        $finalImage = '';
    }

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status
    ];
    $result = insert('products', $data);

    if ($result) {
        redirect('products.php', 'Product Created Successfully!');
    } else {
        redirect('products_create.php', 'Something Went Wrong!');
    }
}

if (isset($_POST['updateProduct'])) {
    $product_id = validate($_POST['product_id']);

    $productData = getById('products', $product_id);
    if (!$productData) {
        redirect('products.php', 'No such product found.');
    }

    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);

    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time() . '.' . $image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);

        $finalImage = "assets/uploads/products/" . $filename;
        $deleteImage = "../" . $productData['data']['image'];
        if (file_exists($deleteImage)) {
            unlink($deleteImage);
        }
    } else {

        $finalImage = $productData['data']['image'];
    }

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status
    ];

    $result = insert('products', $product_id, $data);

    if ($result) {
        redirect('products_edit.php?id=' . $product_id, 'Product Updated Successfully!');
    } else {
        redirect('products_edit.php?id=' . $product_id, 'Something Went Wrong!');
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