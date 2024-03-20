<?php
session_start();

require 'dbcon.php';

// Input field validation
function validate($inputData){

    global $conn;
    $validatedData = mysqli_real_escape_string($conn, $inputData);
    return trim($validatedData);
}

// Redirect from 1 page to another page with the message (status)
function redirect($url, $status){
    $_SESSION['status'] = $status;
    header('Location: '.$url);
    exit(0);
}

//Display message or status after any process.
function alertMessage(){

    if(isset($_SESSION['status'])){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
           <h6>'.$_SESSION['status'].'</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['status']);
    }
}

//Insert record using this function
function insert($tableName, $data){

    global $conn;

    $table = validate($tableName);

    $columns = array_keys($data);
    $values = array_values($data);

    $finalColumn = implode(',', $columns);
    $finalValues = "'".implode("' , '", $values)."'";

    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Update data using this function
// Update data in both user and staff tables
function update($tableName, $id, $data){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach($data as $column => $value){
        $updateDataString .= $column.'='."'$value',";
    }

    $finalUpdateData = rtrim($updateDataString, ',');

    $query = "UPDATE $table SET $finalUpdateData WHERE ";
    
    // Append condition based on the table name
    if ($table === 'user') {
        $query .= "staff_ID='$id'";
    } elseif ($table === 'staff') {
        $query .= "staff_ID='$id'";
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





function getAll($table) {
    global $conn;
    $query = "SELECT * FROM `$table`";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    return $result;
}


function getById($tableName, $id){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    if ($table === 'staff') {
        $column = 'staff_ID';
    } elseif ($table === 'user') {
        $column = 'user_ID';
    } else {
        return [
            'status' => 400,
            'message' => 'Invalid table name'
        ];
    }

    $query = "SELECT * FROM $table WHERE $column='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if(mysqli_num_rows($result) == 1){
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



//Delete data from database using id
function delete($tableName, $id){

    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE staff_ID='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    return $result;
}

function checkParamId($type){

    if(isset($_GET[$type])){
        if($_GET[$type] != ''){

            return $_GET[$type];
        }else{
            return '<h5>No ID Found</h5>';
        }
    }else{
        return '<h5>No ID Given</h5>';
    }
}

function logoutSession(){

    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedInUser']);
}
?>