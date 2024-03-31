<?php
session_start();

require 'dbcon.php';

// Input field validation
function validate($inputData)
{

    global $conn;
    $validatedData = mysqli_real_escape_string($conn, $inputData);
    return trim($validatedData);
}

// Redirect from 1 page to another page with the message (status)
function redirect($url, $status)
{
    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit(0);
}

//Display message or status after any process.
function alertMessage()
{

    if (isset($_SESSION['status'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
           <h6>' . $_SESSION['status'] . '</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['status']);
    }
}

//Insert record using this function
function insert($tableName, $data)
{

    global $conn;

    $table = validate($tableName);

    $columns = array_keys($data);
    $values = array_values($data);

    $finalColumn = implode(',', $columns);
    $finalValues = "'" . implode("' , '", $values) . "'";

    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Update data using this function
// Update data in both user and staff tables
function update($tableName, $id, $data)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach ($data as $column => $value) {
        $updateDataString .= $column . '=' . "'$value',";
    }

    $finalUpdateData = rtrim($updateDataString, ',');

    $query = "UPDATE $table SET $finalUpdateData WHERE ";

    // Append condition based on the table name
    if ($table === 'user') {
        $query .= "user_ID='$id'";
    } elseif ($table === 'staff') {
        $query .= "staff_ID='$id'";
    } elseif ($table === 'customer') {
        $query .= "customer_ID='$id'";
    }

    $result = mysqli_query($conn, $query);

    // If updating the user table, also update the user's information
    if ($table === 'user') {
        $userUpdateData = $data;
        unset($userUpdateData['password']); // Don't update password in the user table
        $userQuery = "UPDATE user SET ";
        foreach ($userUpdateData as $column => $value) {
            $userQuery .= "$column = '$value',";
        }
        $userQuery = rtrim($userQuery, ',');
        $userQuery .= " WHERE user_ID = '$id'";
        $userResult = mysqli_query($conn, $userQuery);
        if (!$userResult) {
            return false;
        }
    }

    return $result;
}

function update_user_pass($tableName, $id, $data)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach ($data as $column => $value) {
        $updateDataString .= $column . '=' . "'$value',";
    }

    $finalUpdateData = rtrim($updateDataString, ',');

    $query = "UPDATE $table SET $finalUpdateData WHERE ";

    // Append condition based on the table name
    if ($table === 'user') {
        $query .= "user_ID='$id'";
    } elseif ($table === 'staff') {
        $query .= "staff_ID='$id'";
    } elseif ($table === 'customer') {
        $query .= "customer_ID='$id'";
    }

    $result = mysqli_query($conn, $query);

    // If updating the user table, also update the user's information
    if ($table === 'user') {
        $userUpdateData = $data;
        unset($userUpdateData['password']); // Don't update password in the user table
        $userQuery = "UPDATE user SET ";
        foreach ($userUpdateData as $column => $value) {
            $userQuery .= "$column = '$value',";
        }
        $userQuery = rtrim($userQuery, ',');
        $userQuery .= " WHERE user_ID = '$id'";
        $userResult = mysqli_query($conn, $userQuery);
        if (!$userResult) {
            return false;
        }
    }

    // If updating the staff table, also update the staff's username
    if ($table === 'staff') {
        $staffUpdateData = $data;
        $staffQuery = "UPDATE staff SET ";
        foreach ($staffUpdateData as $column => $value) {
            $staffQuery .= "$column = '$value',";
        }
        $staffQuery = rtrim($staffQuery, ',');
        $staffQuery .= " WHERE staff_ID = '$id'";
        $staffResult = mysqli_query($conn, $staffQuery);
        if (!$staffResult) {
            return false;
        }
    }

    // If updating the customer table, also update the customer's username
    if ($table === 'customer') {
        $customerUpdateData = $data;
        $customerQuery = "UPDATE customer SET ";
        foreach ($customerUpdateData as $column => $value) {
            $customerQuery .= "$column = '$value',";
        }
        $customerQuery = rtrim($customerQuery, ',');
        $customerQuery .= " WHERE user_ID = '$id'";
        $customerResult = mysqli_query($conn, $customerQuery);
        if (!$customerResult) {
            return false;
        }
    }

    return $result;
}

//to retrieve all category for when viewing data - able to check status
function getCategoryAll($tableName)
{
    global $conn;

    $table = validate($tableName);
    $status = validate($status);

    if($status == 'status'){
        $query = "SELECT * FROM $table WHERE status='0'";
    }
    else{
        $query = "SELECT * FROM $table";
    }
    return mysqli_query($conn, $query);
}

//to retrieve all product for when viewing data - able to check status
function getProductAll($tableName, $status = NULL)
{
    global $conn;

    $table = validate($tableName);
    $status = validate($status);

    if($status == 'status'){
        $query = "SELECT * FROM $table WHERE status='0'";
    }
    else{
        $query = "SELECT * FROM $table";
    }
    return mysqli_query($conn, $query);
}

function getAll($tableName, $status = NULL)
{
    global $conn;

    $table = validate($tableName);
    $status = validate($status);

    if($status == 'status'){
        $query = "SELECT * FROM $table WHERE status='0'";
    }
    else{
        $query = "SELECT * FROM $table";
    }
    return mysqli_query($conn, $query);
}

function getOrderAll($table)
{
    global $conn;
    $query = "SELECT * FROM `$table`";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    return $result;
}

function getUserByID($user_id) {
    global $conn;

    $query = "SELECT * FROM user WHERE user_ID = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        return $user;
    } else {
        return null;
    }
}


//to get specific data by product id
function getByProductId($tableName, $id)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "SELECT * FROM $table WHERE product_ID='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if(!$result){
        die("Query execution error: " . mysqli_error($conn));
    }

    if ($result) {

        if (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_assoc($result);

            return [
                'status' => 200,
                'data' => $row,
                'message' => 'Record Found'
            ];

        } else {

            return [
                'status' => 404,
                'message' => 'No Data Found'
            ];
            
        }

       }else{
            return [
                'status' => 500,
                'message' => 'Something Went Wrong'
        ];

    }
}

//to get specific data by product id
function getByCategoryId($tableName, $id)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "SELECT * FROM $table WHERE category_ID='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);


    if(!$result){
        die("Query execution error: " . mysqli_error($conn));
    }

    if ($result) {

        if (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_assoc($result);

            return [
                'status' => 200,
                'data' => $row,
                'message' => 'Record Found'
            ];

        } else {

            $response = [
                'status' => 404,
                'message' => 'No Data Found'
            ];

            return $response;
            
        }

       }else{
            $response = [
                'status' => 500,
                'message' => 'Something Went Wrong'
        ];

        return $response;

    }
}


function getOrderFilteredByStatus($tableName, $status) {
    global $conn;

    // Escape the status to prevent SQL injection
    $status = mysqli_real_escape_string($conn, $status);

    // Enclose the table name in backticks to avoid SQL syntax error
    $tableName = "`$tableName`";

    $query = "SELECT * FROM $tableName WHERE order_status = '$status'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error fetching orders: " . mysqli_error($conn);
        return false;
    }

    return $result;
}


function updateProduct($tableName, $id, $data)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach ($data as $column => $value) {
        $updateDataString .= $column . '=' . "'$value',";
    }

    $finalUpdateData = rtrim($updateDataString, ',');

    $query = "UPDATE $table SET $finalUpdateData WHERE product_ID='$id'";
    $result = mysqli_query($conn, $query);
    return $result;
}

function updateCategory($tableName, $id, $data)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach ($data as $column => $value) {
        $updateDataString .= "$column='" . validate($value) . "',";
    }
    $updateDataString = rtrim($updateDataString, ',');

    $query = "UPDATE $table SET $updateDataString WHERE category_ID='$id'";
    $result = mysqli_query($conn, $query);
    return $result;
}

function getStaffAll($table)
{
    global $conn;
    $sort_option = "";
    $sort_criteria = ""; 

    if (isset($_GET['sort_alphabet'])) {
        if ($_GET['sort_alphabet'] == "a-z") {
            $sort_option = "ASC";
            $sort_criteria = "ORDER BY username $sort_option"; 
        } elseif ($_GET['sort_alphabet'] == "z-a") {
            $sort_option = "DESC";
            $sort_criteria = "ORDER BY username $sort_option"; 
        } elseif ($_GET['sort_alphabet'] == "number") {
            $sort_option = "ASC";
            $sort_criteria = "ORDER BY CAST(staff_id AS UNSIGNED) $sort_option"; // Sort numerically by staff_id
        }
    } else {
        $sort_option = "ASC"; // Default sorting option
        $sort_criteria = "ORDER BY staff_id $sort_option"; // Sort numerically by default
    }

    // Adding search functionality
    if(isset($_GET['search']) && !empty($_GET['search'])){
        $filtervalues = $_GET['search'];
        $query = "SELECT * FROM $table WHERE CONCAT(first_name,last_name,staff_ID,username,email) LIKE '%$filtervalues%' ";
    } else {
        $query = "SELECT * FROM `$table` $sort_criteria";
    }

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    return $result;
}

function getUserAll($table)
{
    global $conn;
    $sort_option = "";
    $sort_criteria = ""; 

    if (isset($_GET['sort_alphabet'])) {
        if ($_GET['sort_alphabet'] == "a-z") {
            $sort_option = "ASC";
            $sort_criteria = "ORDER BY username $sort_option"; 
        } elseif ($_GET['sort_alphabet'] == "z-a") {
            $sort_option = "DESC";
            $sort_criteria = "ORDER BY username $sort_option"; 
        } elseif ($_GET['sort_alphabet'] == "number") {
            $sort_option = "ASC";
            $sort_criteria = "ORDER BY CAST(user_id AS UNSIGNED) $sort_option"; // Sort numerically by staff_id
        }
    } else {
        $sort_option = "ASC"; // Default sorting option
        $sort_criteria = "ORDER BY user_id $sort_option"; // Sort numerically by default
    }

    // Adding search functionality
    if(isset($_GET['search']) && !empty($_GET['search'])){
        $filtervalues = $_GET['search'];
        $query = "SELECT * FROM $table WHERE CONCAT(first_name,last_name,user_ID,username,email) LIKE '%$filtervalues%' ";
    } else {
        $query = "SELECT * FROM `$table` $sort_criteria";
    }

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    return $result;
}

function getCustomerAll($table)
{
    global $conn;
    $sort_option = "";
    $sort_criteria = ""; 

    if (isset($_GET['sort_alphabet'])) {
        if ($_GET['sort_alphabet'] == "a-z") {
            $sort_option = "ASC";
            $sort_criteria = "ORDER BY username $sort_option"; 
        } elseif ($_GET['sort_alphabet'] == "z-a") {
            $sort_option = "DESC";
            $sort_criteria = "ORDER BY username $sort_option"; 
        } elseif ($_GET['sort_alphabet'] == "number") {
            $sort_option = "ASC";
            $sort_criteria = "ORDER BY CAST(customer_ID AS UNSIGNED) $sort_option"; // Sort numerically by customer_id
        }
    } else {
        $sort_option = "ASC"; // Default sorting option
        $sort_criteria = "ORDER BY customer_ID $sort_option"; // Sort numerically by default
    }

    // Adding search functionality
    if(isset($_GET['search']) && !empty($_GET['search'])){
        $filtervalues = $_GET['search'];
        $query = "SELECT * FROM $table WHERE CONCAT(first_name,last_name,customer_ID,username,email) LIKE '%$filtervalues%' ";
    } else {
        $query = "SELECT * FROM `$table` $sort_criteria";
    }

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    return $result;
}


function getById($tableName, $id)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    if ($table === 'staff') {
        $column = 'staff_ID';
    } elseif ($table === 'user') {
        $column = 'user_ID';
    } elseif ($table === 'customer') {
        $column = 'customer_ID';
    } else {
        return [
            'status' => 400,
            'message' => 'Invalid table name'
        ];
    }

    $query = "SELECT * FROM $table WHERE $column='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            return [
                'status' => 200,
                'data' => $row,
                'message' => 'Record Found'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'No Data Found'
            ];
        }
    } else {
        return [
            'status' => 500,
            'message' => 'Something Went Wrong'
        ];
    }
}

//Delete data from product table using product id
function deleteProduct($tableName, $id)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    // Check if there are associated OrderItem records
    $query = "SELECT * FROM order_item WHERE product_ID='$id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Associated OrderItem records found, return false
        return false;
    }

     // No associated OrderItem records, proceed with deletion
    $deleteQuery = "DELETE FROM $table WHERE product_ID='$id' LIMIT 1";
    $deleteResult = mysqli_query($conn, $deleteQuery);
 

    if ($deleteResult) {
        return true;
    } else {
        error_log("Error deleting product: " . mysqli_error($conn));
        return false;
    }
}

//Delete data from category table using category id
function deleteCategory($tableName, $id)
{
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

     // Check if there are associated Product records
    $query = "SELECT * FROM product WHERE category_ID='$id'";
    $result = mysqli_query($conn, $query);//line 443

    if (mysqli_num_rows($result) > 0) {
        // Associated Product records found, return false
        return false;
    }

     // No associated OrderItem records, proceed with deletion
    $deleteQuery = "DELETE FROM $table WHERE category_ID='$id' LIMIT 1";
    $deleteResult = mysqli_query($conn, $deleteQuery);
 

    if ($deleteResult) {
        return true;
    } else {
        error_log("Error deleting category: " . mysqli_error($conn));
        return false;
    }

}



//delete order
function deleteOrder($tableName, $id){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE order_ID='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    return $result;
}


//Delete data from database using id
function delete($tableName, $id)
{

    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE staff_ID='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    return $result;
}


function checkParamId($type)
{

    if (isset($_GET[$type])) {
        if ($_GET[$type] != '') {

            return $_GET[$type];
        } else {
            return '<h5>No ID Found</h5>';
        }
    } else {
        return '<h5>No ID Given</h5>';
    }
}

function logoutSession()
{

    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedInUser']);
}

