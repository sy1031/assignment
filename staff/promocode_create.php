<?php
include ('Includes/header.php');
?>

<?php
// Function to sanitize input data
function sanitizeInput($data)
{
    $data = trim($data); //Removes any leading or trailing whitespace 
    $data = stripslashes($data); //Removes backslashes (\) 
    $data = htmlspecialchars($data); // Converts special characters to HTML entities. (Preventing XSS[Cross-site Scripting] attacks)
    //Eg: &(ampersand) will be converted to &amp; in html (Same thing)
    //More info for htmlspecialchars may refer to : https://www.php.net/manual/en/function.htmlspecialchars.php
    return $data;
}

// Initialize variables for form data
$promotionName = $promotionDescription = $directDiscount = $startDate = $endDate = '';

// Validation flag
$formValid = true;

// Validate and sanitize form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $promotionName = sanitizeInput($_POST['promotionName']);
    $promotionDescription = sanitizeInput($_POST['promotionDescription']);
    $directDiscount = $_POST['direct_discount'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Validate promotion name
    if (empty($promotionName)) {
        $formValid = false;
        echo "<div class='alert alert-danger' role='alert'>Promotion name is required.</div>";
    }

    // Validate start date and end date (optional)
    if ($startDate > $endDate) {
        $formValid = false;
        echo "<div class='alert alert-danger' role='alert'>End date must be after start date.</div>";
    }

    // If form is valid, insert data into the database
    if ($formValid) {
        // Prepare SQL statement
        $sql = "INSERT INTO promotion (promotion_name, promotion_description, direct_discount, start_date, end_date) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ssdss", $promotionName, $promotionDescription, $directDiscount, $startDate, $endDate);

        // Execute statement
        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Promotion code is created successfully.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error creating promotion: " . $conn->error . "</div>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Admin</title>
</head>

<body>
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
        }

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

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
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
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
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

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        select {
            height: 38px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 6px 12px;
        }

        input[type="submit"] {
            width: 100px;
            height: 38px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .titleSize {
            font-weight: bold;
            font-size: 24px;
        }
    </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="card mt-4 shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0 titleSize mt-3">Create Promotional Code</h4>
                </div>
                <div class="card-body">
                    <?php alertMessage(); ?>

                    <form action="promocode_create.php" method="POST">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="promotionName">Promotion Name</label>
                                <input type="text" name="promotionName" required class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="promotionDescription">Description</label>
                                <textarea name="promotionDescription" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="direct_discount">Direct Discount (RM):</label>
                                <input type="number" id="direct_discount" name="direct_discount" min="0" step="0.1">
                            </div>
                            <div class="col-md-6">
                                <label for="startDate">Start Date</label>
                                <input type="date" name="startDate" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="endDate">End Date</label>
                                <input type="date" name="endDate" required class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <br>
                                <button type="submit" name="savePromotion" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

</html>

</body>

</html>

<?php include ('includes/footer.php'); ?>