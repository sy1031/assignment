<?php
ob_start();
include ('Includes/header.php');

// Check if the promotion ID is provided in the URL
if (isset ($_GET['promotion_id'])) {
    $promotion_id = $_GET['promotion_id'];

    // Fetch the promotional code details from the database
    $sql = "SELECT * FROM promotion WHERE promotion_id = $promotion_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $promotion_name = $row['promotion_name'];
        $promotion_description = $row['promotion_description'];
        $direct_discount = $row['direct_discount'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
    } else {
        // Redirect back to the view page if promotion ID is invalid
        header("Location: promocode_view.php?error=invalid_promotion_id");
        exit();
    }
} else {
    // Redirect back to the view page if promotion ID is not provided
    header("Location: promocode_view.php?error=missing_promotion_id");
    exit();
}

// Handle form submission for updating promotional code
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data

    // Update the promotional code in the database
    $updateSql = "UPDATE promotion SET promotion_name=?, promotion_description=?, direct_discount=?, start_date=?, end_date=? WHERE promotion_id=?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssdssi", $_POST['promotion_name'], $_POST['promotion_description'], $_POST['direct_discount'], $_POST['start_date'], $_POST['end_date'], $promotion_id);

    if ($stmt->execute()) {
        // Redirect back to the view page with success message
        $_SESSION['success_message'] = "Promotional code updated successfully.";
        header("Location: promocode_view.php");
        exit();
    } else {
        // Redirect back to the edit page with error message
        header("Location: promocode_edit.php?promotion_id=$promotion_id&error=update_failed");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Promotional Code - Staff</title>
</head>

<body>
    <div class="container-fluid px-4">
        <h2 class="mt-4"> Update / Edit Promotional Code</h2>
        <!-- Display error messages here if needed -->
        <div class="card mt-4 shadow-sm">
            <div class="card-body">
                <form action="promocode_edit.php?promotion_id=<?php echo $promotion_id; ?>" method="POST">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="promotion_name">Promotion Name</label>
                            <input type="text" name="promotion_name" value="<?php echo $promotion_name; ?>"
                                class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="promotion_description">Description</label>
                            <textarea name="promotion_description" class="form-control"
                                rows="3"><?php echo $promotion_description; ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="direct_discount">Direct Discount (RM)</label>
                            <input type="number" name="direct_discount" value="<?php echo $direct_discount; ?>"
                                class="form-control" min="0" step="0.1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="form-control"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="form-control"
                                required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="promocode_view.php" class="btn btn-secondary">Cancel</a>
                </form>

            </div>
        </div>
    </div>
</body>

</html>

<?php include ('includes/footer.php'); ?>