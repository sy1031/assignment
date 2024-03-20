<?php
include('../config/function.php');

if (isset($_POST['changePassword'])) {

    $staffId = validate($_POST['staff_ID']);

    // Retrieve staff ID from session
    $staffId = $_SESSION['loggedInUser']['staff_ID'];

    // Validate and sanitize form inputs
    $oldPassword = validate($_POST['old_password']);
    $newPassword = validate($_POST['new_password']);
    $confirmPassword = validate($_POST['cfm_password']);

    // Check if new password matches confirm password
    if ($newPassword !== $confirmPassword) {
        redirect('change_password.php', 'New password and confirm password do not match.');
    }

    // Retrieve existing staff data
    $staffData = getById('staff', $staffId);
    if ($staffData['status'] != 200) {
        redirect('staff_change_password.php?id=' .$staffId, 'Staff not found.');
    }

    // Hash the old password for comparison
    $hashedOldPassword = $staffData['data']['password'];

    // Check if old password matches the stored password
    if (password_verify($oldPassword, $hashedOldPassword)) {
        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Prepare data for updating staff
        $staff_data = [
            'password' => $hashedNewPassword
        ];

        // Update staff record
        $result_staff = update('staff', $staffId, $staff_data);

        // Prepare data for updating user
        $user_data = [
            'password' => $hashedNewPassword
        ];

        // Update user record using staff_id
        $result_user = update('user', $staffId, $user_data);

        if ($result_staff && $result_user) {
            redirect('staff_change_password.php', 'Password changed successfully.');
        } else {
            redirect('staff_change_password.php', 'Failed to update password. Please try again.');
        }
    } else {
        redirect('staff_change_password.php', 'Incorrect old password.');
    }
}
?>
