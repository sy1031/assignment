<?php

    $conn = mysqli_connect('localhost','root','','cart');

    //check error - numerical value
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

?>