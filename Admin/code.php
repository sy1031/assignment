<?php

include('../config/function.php');

if (isset($_POST['saveAdmin'])) {
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);

    if ($name != '' && $email != '' && $password != '') {
        $emailCheck = mysqli_query($conn, "SELECT * FROM staff WHERE email='$email'");
        if ($emailCheck) {
            if (mysqli_num_rows($emailCheck) > 0) {
                redirect('admins_create.php', 'Email Already used by another user.');
            }
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone
        ];
        $result = insert('staff', $data);
        if ($result) {
            redirect('admins.php', 'Staff Created Successfully!');
        } else {
            redirect('admins_create.php', 'Something Went Wrong!');
        }
    } else {
        redirect('admins_create.php', 'Please fill required fields.');
    }
}

if (isset($_POST['updateStaff'])) {

    $staffId = validate(($_POST['staffId']));

    $staffData = getById('staff', $staffId);
    if ($staffData['status'] != 200) {
        redirect('staff_edit.php?id=' . $staffId, 'Please fill required fields.');
    }

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);

    if ($password != '') {
        $hasedPassword = password_hash($password, PASSWORD_BCRYPT);
    } else {
        $hashedPassword = $staffData['data']['password'];
    }

    if ($name != '' && $email != '') {
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'phone' => $phone
        ];
        $result = update('staff', $staffId, $data);
        if ($result) {
            redirect('staff_edit.php?id=' .$staffId, 'Staff Updated Successfully!');
        } else {
            redirect('admins_edit.php?id=' .$staffId, 'Something Went Wrong!');
        }
    } else {
        redirect('admins_create.php', 'Please fill required fields.');
    }
}


if(isset($_POST['saveCategory']))
{
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;

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

if(isset($_POST['updateCategory']))
{
    $categoryId = validate($_POST['categoryId']);

    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;
    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status
    ];
    $result = update('categories', $categoryId, $data);
    if ($result) {
        redirect('categories_edit.php?id='.$categoryId, 'Category Updated Successfully!');
    } else {
        redirect('categories_edit.php?id='.$categoryId, 'Something Went Wrong!');
    }
}

if(isset($POST['saveProduct']))
{
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1:0;

    if($_FILES['image']['size'] > 0)
    {
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time().'.'.$image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename);

        $finalImage = "assets/uploads/products/".$filename;

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

?>