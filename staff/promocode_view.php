<?php include ('Includes/header.php');

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Promotional Code - Admin</title>
</head>


<body>
    <div class="container-fluid px-4">
        <h2 class="mt-4">View Promotional Code</h2>

        <?php
        // Check if a success message is set in the session
        if (isset ($_SESSION['success_message'])) {
            // Display the success message
            echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_message']}</div>";
            // Unset the session variable to remove the message after displaying it
            unset($_SESSION['success_message']);
        } ?>
        
        <div class="card mt-4 shadow-sm">
            <div class="card-body">
                <?php
                // Fetch promotional codes from the database
                $sql = "SELECT * FROM promotion";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Display promotional codes
                    echo "<table class='table table-bordered'>";
                    echo "<thead><tr><th>Promotional Code</th><th>Description</th><th>Direct Discount (RM)</th><th>Start Date</th><th>End Date</th><th>Actions</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['promotion_name'] . "</td>";
                        echo "<td>" . $row['promotion_description'] . "</td>";
                        echo "<td>" . $row['direct_discount'] . "</td>";
                        echo "<td>" . $row['start_date'] . "</td>";
                        echo "<td>" . $row['end_date'] . "</td>";
                        echo "<td><a href='promocode_edit.php?promotion_id=" . $row['promotion_id'] . "' class='btn btn-primary btn-sm'>Edit</a> <a href='promocode_delete.php?promotion_id=" . $row['promotion_id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this promotional code?\")'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "No promotional codes found.";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php include ('includes/footer.php'); ?>