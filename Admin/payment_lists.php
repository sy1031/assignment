<?php include ('Includes/header.php'); ?>

<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <style>
        /* Your existing CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            padding: 0 15px;
        }

        .card {
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        .btn {
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .search-container {
            padding: 25px 0 0 20px;
        }

        .search-container input[type=text] {
            padding: 10px;
            margin: 5px;
            width: 50%;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        * {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Payment History</h2>

        <?php
        // Check if a success message is set in the session
        if (isset($_SESSION['success_message'])) {
            // Display the success message
            echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_message']}</div>";
            // Unset the session variable to remove the message after displaying it
            unset($_SESSION['success_message']);
        } ?>

        <div class="card mt-4 shadow-sm">

            <div class="col-md-6">
                <div class="search-container">
                    <input type="text" id="searchInput" onkeyup="searchByName()"
                        placeholder="Search by customer name...">
                </div>
            </div>

            <div class="card-body">

                <?php
                // Fetch payment history along with order summaries and user details
                // By using Join method
                $sql = "SELECT 
                    p.payment_ID, 
                    p.payment_Date, 
                    p.payment_Amount, 
                    p.payment_Status,
                    o.order_ID,
                    u.user_ID,
                    u.first_name,
                    u.last_name
                FROM 
                    payment p
                    INNER JOIN `order` o ON p.order_ID = o.order_ID
                    INNER JOIN `user` u ON o.user_ID = u.user_ID
                WHERE
                    u.usertype = 'customer'";

                $result = $conn->query($sql);

                if (!$result) {
                    die("SQL Error: " . $conn->error); // Output any SQL errors for debugging purposes
                }

                // Check if there are any payment records
                if ($result->num_rows > 0) {
                    // Display payment history along with order summaries and user details
                    echo "<table class='table' id='payment-table'>";
                    echo "<thead><tr><th>Payment ID</th><th>Payment Date</th><th>Customer ID</th><th>Customer Name</th><th>Order ID</th><th>Payment Amount</th><th>Payment Status</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['payment_ID'] . "</td>";
                        echo "<td>" . $row['payment_Date'] . "</td>";
                        echo "<td>" . $row['user_ID'] . "</td>";
                        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                        echo "<td>" . $row['order_ID'] . "</td>";
                        echo "<td>" . $row['payment_Amount'] . "</td>";
                        echo "<td>" . $row['payment_Status'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    // Handle the case where no payment records are found
                    echo "No payment records found.";
                }

                // Close the database connection
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <script>
        //Search By Name Function - Apply Effective Searching for Staff
        function searchByName() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("payment-table");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[3]; // Change index to the appropriate column where customer name is displayed
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

</body>

</html>



<?php include ('includes/footer.php'); ?>