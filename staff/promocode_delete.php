<?php
require ('../config/dbcon.php');
session_start();

// Check if promotion_id parameter is set and not empty
if (isset ($_GET['promotion_id']) && !empty ($_GET['promotion_id'])) {
    // Get the promotion_id
    $id = $_GET['promotion_id'];

    // Prepare SQL statement to delete the promotional code with the given ID
    $deleteSql = "DELETE FROM promotion WHERE promotion_id = $id";

    // Execute the delete query
    if ($conn->query($deleteSql) === TRUE) {
        // Set success message in session
        $_SESSION['success_message'] = "Promotional code deleted successfully.";
        // Redirect back to the view promotional code page after successful deletion
        header("Location: promocode_view.php");
        exit();
    } else {
        // If deletion fails, redirect with an error message
        header("Location: promocode_view.php?error=delete_failed");
        exit();
    }
} else {
    // If promotion_id parameter is not set or empty, redirect with an error message
    header("Location: promocode_view.php?error=invalid_promotion_id");
    exit();
}

?>