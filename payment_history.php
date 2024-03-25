<?php
// Include your database connection file
require_once 'config/dbcon.php';


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
    include ('Includes/header.php');
    echo "<div class='container'>";
    echo "<h2 class='pt-3'>Payment History</h2>";
    echo "<div class='table-container'>";
    echo "<table class='payment-table'>";
    echo "<thead style='background-color: #FFC107; color: #fff;'>";
    echo "<tr><th>Payment ID</th><th>Payment Date</th><th>Payment Amount</th><th>Payment Status</th>";
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
    include ('Includes/footer.php');
} else {
    // Handle the case where no payment records are found for the user
    include ('Includes/header.php');
    echo "No payment records found for the logged-in user.";
    include ('Includes/footer.php');
}

// Close the prepared statement
$stmt->close();


?>
<style>
    .table-container {
        overflow-x: auto;
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
</style>