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
            redirect('admins_create.php', 'Something Went Wrong');
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
            redirect('admins_edit.php?id=' .$staffId, 'Something Went Wrong');
        }
    } else {
        redirect('admins_create.php', 'Please fill required fields.');
    }
}
?>