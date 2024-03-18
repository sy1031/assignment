<?php

require 'config/function.php';

if (isset($_POST['loginBtn'])) {
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    if ($email != '' && $password != '') {
        $query = "SELECT * FROM user WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) == 1) {

                $row = mysqli_fetch_assoc($result);
                $hasedPassword = $row['password'];

                if (!password_verify($password, $hashedPassword)) {
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['loggedInUser'] = [
                        'user_id' => $row['id'],
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'usertype' => $row['usertype']
                    ];

                    // Redirect based on user role
                    switch ($row['usertype']) {
                        case 'admin':
                            redirect('admin/homepage.php', 'Logged In Successfully');
                            break;
                        case 'customer':
                            redirect('customer/homepage.php', 'Logged In Successfully');
                            break;
                        case 'staff':
                            redirect('staff/homepage.php', 'Logged In Successfully');
                            break;
                        default:
                            // Handle other roles or redirect to a default page
                            redirect('default.php', 'Logged In Successfully');
                            break;
                    }
                } else {
                    redirect('login.php', 'Invalid Password');
                }
            } else {
                redirect('login.php', 'Invalid Email Address');
            }
        }
    } else {
        redirect('login.php', 'All fields are mandetory!');
    }
}
