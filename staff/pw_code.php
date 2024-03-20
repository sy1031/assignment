<?php
// Include necessary files and initialize session if not already done
include('../config/function.php');

// Check if the form is submitted
if (isset($_POST['changePassword'])) {
    // Retrieve user ID from session
    $user_ID = $_SESSION['loggedInUser']['user_ID'];

    // Validate and sanitize form inputs
    $old_password = validate($_POST['old_password']);
    $new_password = validate($_POST['new_password']);
    $confirm_password = validate($_POST['cfm_password']);

    // Check if new password matches confirm password
    if ($new_password !== $confirm_password) {
        redirect('staff_change_password.php', 'New password and confirm password do not match.');
    }

    // Retrieve user information from the database
    $user_info = getById('user', $user_ID);

    // Check if user exists and old password matches
    if ($user_info['status'] === 200) {
        $user_data = $user_info['data'];
        $hashed_password = $user_data['password'];

        // Verify the old password
        if (password_verify($old_password, $hashed_password)) {
            // Hash the new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the user table
            $update_user_query = "UPDATE user SET password='$new_hashed_password' WHERE user_ID='$user_ID'";
            $update_user_result = mysqli_query($conn, $update_user_query);

            // Update the password in the staff table using the staff_ID associated with the user
            $staff_ID = $user_data['staff_ID'];
            $update_staff_query = "UPDATE staff SET password='$new_hashed_password' WHERE staff_ID='$staff_ID'";
            $update_staff_result = mysqli_query($conn, $update_staff_query);

            // Check if both updates were successful
            if ($update_user_result && $update_staff_result) {
                redirect('staff_change_password.php', 'Password changed successfully.');
            } else {
                redirect('staff_change_password.php', 'Failed to update password. Please try again.');
            }
        } else {
            redirect('staff_change_password.php', 'Incorrect old password.');
        }
    } else {
        redirect('staff_change_password.php', 'User not found.');
    }
}
?>
