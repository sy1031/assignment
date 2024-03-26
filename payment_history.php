<?php
// Include your database connection file
require_once 'config/dbcon.php';

// Check if the user is logged in
session_start(); // Start the session
if (!isset ($_SESSION['loggedInUser'])) {
    // Redirect the user to the login page or handle the situation accordingly
    // For example:
    header("Location: login.php");
    exit; // Stop further execution
}


// Prepare SQL statement to fetch payment history for the logged-in user
$sql = "SELECT 
            p.payment_ID, 
            p.payment_Date, 
            p.payment_Amount, 
            p.payment_Status,
            o.user_ID, 
            o.order_date, 
            o.total_amount
        FROM 
            payment p
        INNER JOIN `order` o ON p.order_ID = o.order_ID 
        WHERE o.user_ID = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Check if the statement preparation was successful
if (!$stmt) {
    die ("SQL Error: " . $conn->error); // Output any SQL errors for debugging purposes
}

// Bind the parameter (user ID) to the statement
$user_id = $_SESSION['loggedInUser']['user_ID'];
$stmt->bind_param("i", $user_id);

// Execute the statement
$stmt->execute();

// Get the result of the executed statement
$result = $stmt->get_result();

// Check if there are any payment records for the user
if ($result->num_rows > 0) {
    // Display payment history along with order summaries
    include ('navbar-cus.php');
    echo "<div class='container'>";
    echo "<h1 class='block-heading'>Payment History</h1>";
    echo "<div class='table-container'>";
    echo "<table class='payment-table'>";
    echo "<thead style='background-color: #FFC107; color: #fff;'>";
    echo "<tr><th>Payment ID</th><th>Payment Date/Time</th><th>Payment Amount (RM)</th><th>Payment Status</th>";
    echo "</thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['payment_ID'] . "</td>";
        echo "<td>" . $row['payment_Date'] . "</td>";
        echo "<td>" . $row['payment_Amount'] . "</td>";
        echo "<td>" . $row['payment_Status'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "<br>";
    include ('footer.php');
} else {
    // Handle the case where no payment records are found for the user
    include include ('navbar-cus.php');
    echo "No payment records found for the logged-in user.";
    include ('footer.php');
}

// Close the prepared statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment History</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
     <!-- font awesome link -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

</body>

</html>
<style>
    .table-container {
        overflow-x: auto;
        min-height: 400px;
    }

    .payment-table {
        width: 100%;
        border-collapse: collapse;
    }

    .payment-table th,
    .payment-table td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .payment-table th {
        background-color: #Ffe58d;
        color: #666;
    }

    .block-heading {
    padding-top: 50px;
    margin-bottom: 40px;
    text-align: center;
    }
</style>