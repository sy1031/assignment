<?php 
include ('Includes/header.php');
 ?>

<?php
//require ('../config/dbcon.php');

// Function to sanitize input data
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
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
    if (empty ($promotionName)) {
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
            echo "<div class='alert alert-success' role='alert'>Promotion created successfully.</div>";
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }


        h2 {
            margin-top: 0;
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .titleSize {
            font-weight:bold;
            font-size:24px;
        }
    </style>
    </head>

    <body>
        <div class="container-fluid px-4">
            <div class="card mt-4 shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0 titleSize mt-3">Create Promotional Code 
                    </h4>
                </div>
                <div class="card-body">

                    <?php alertMessage(); ?>

                    <form action="promocode_create.php" method="POST">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="">Promotion Name</label>
                                <input type="text" name="promotionName" required class="form-control" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="">Description</label>
                                <textarea name="promotionDescription" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="direct_discount">Direct Discount (RM):</label>
                                <input type="number" id="direct_discount" name="direct_discount" min="0" step="0.1"><br>
                            </div>

                            <div class="col-md-6">
                                <label for="">Start Date</label>
                                <input type="date" name="startDate" required class="form-control" />
                            </div>

                            <div class="col-md-6">
                                <label for="">End Date</label>
                                <input type="date" name="endDate" required class="form-control" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <br />
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