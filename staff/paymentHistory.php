<?php
// Include your database connection file
require ('../config/dbcon.php');

// Fetch payment history along with order summaries and user details
$sql = "SELECT 
p.payment_ID, 
p.payment_Date, 
p.payment_Amount, 
p.payment_Status,
u.user_ID,
u.first_name,
u.last_name,
u.usertype,
o.order_date, 
o.total_amount,
od.product_ID,
od.quantity,
od.price
FROM 
payment p
INNER JOIN `order` o ON p.order_ID = o.order_ID
INNER JOIN `user` u ON o.user_ID = u.user_ID
INNER JOIN `cart` od ON o.order_ID = od.cart_ID
WHERE
u.usertype = 'customer';
";

$result = $conn->query($sql);

if (!$result) {
    die ("SQL Error: " . $conn->error); // Output any SQL errors for debugging purposes
}

// Check if there are any payment records
if ($result->num_rows > 0) {
    // Display payment history along with order summaries and user details
    echo "<h2>Payment History</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Payment ID</th><th>Payment Date</th><th>Payment Amount</th><th>Payment Status</th><th>Customer ID</th><th>Customer Name</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['payment_ID'] . "</td>";
        echo "<td>" . $row['payment_Date'] . "</td>";
        echo "<td>" . $row['payment_Amount'] . "</td>";
        echo "<td>" . $row['payment_Status'] . "</td>";
        echo "<td>" . $row['user_ID'] . "</td>";
        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    // Handle the case where no payment records are found
    echo "No payment records found.";
}

// Close the database connection
$conn->close();
?>