<?php
require 'config/function.php';

if (isset($_POST['loginBtn'])) {
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    if ($email != '' && $password != '') {
        // Check if the user exists in the user table
        $query = "SELECT * FROM user WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $hashedPassword = $row['password'];

            if (password_verify($password, $hashedPassword)) {
                // User found in the user table
                $_SESSION['loggedIn'] = true;
                
                // Set user role (admin, staff, or customer) in the session
                $_SESSION['loggedInUser'] = [
                    'user_ID' => $row['user_ID'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'usertype' => $row['usertype'] // Assuming 'usertype' column stores user role
                ];

                // Redirect to appropriate homepage based on user role
                switch ($_SESSION['loggedInUser']['usertype']) {
                    case 'admin':
                        redirect('admin/homepage.php', 'Logged In Successfully');
                        break;
                    case 'staff':
                        redirect('staff/homepage.php', 'Logged In Successfully');
                        break;
                    case 'customer':
                        redirect('customer/homepage.php', 'Logged In Successfully');
                        break;
                    default:
                        redirect('login.php', 'Invalid User Role');
                        break;
                }
            } else {
                // Invalid password
                redirect('login.php', 'Invalid Password');
            }
        } else {
            // User not found
            redirect('login.php', 'Invalid Email Address');
        }
    } else {
        redirect('login.php', 'All fields are mandatory!');
    }
}
?>
