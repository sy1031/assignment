<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
        <title>User Registration</title>
    </head>
    <body>
        <?php
        require('database.php');

        if (isset($_REQUEST['username'])) {
            $username = stripslashes($_REQUEST['username']);
            $username = mysqli_real_escape_string($conn,$username); 
            $email = stripslashes($_REQUEST['email']);
            $email = mysqli_real_escape_string($conn,$email);
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($conn,$password);

            $query = "INSERT into `customers` (username, password, email)
            VALUES ('$username', '".md5($password)."', '$email')";
            
            $result = mysqli_query($conn,$query);

            if ($result) {
                // Retrieve the customer_ID
                $customer_id = mysqli_insert_id($conn);
    
                // Insert a new order into the order table
                $insert_order_query = "INSERT INTO `order` (customer_ID, order_date, total_amount) VALUES ('$customer_id', NOW(), 0)";
                $insert_order_result = mysqli_query($conn, $insert_order_query);
    
                if ($insert_order_result) {
                    echo "<div class='form'>
                    <h3>You are registered successfully.</h3>
                    <br/>Click here to <a href='login.php'>Login</a></div>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }

        } else {
            ?>
            <div class="form">
            <h1>User Registration</h1>

            <form name="registration" action="" method="post">
                <input type="text" name="username" placeholder="Username" required /><br>
                <input type="email" name="email" placeholder="Email" required /><br>
                <input type="password" name="password" placeholder="Password" required /><br>
                <input type="submit" name="submit" value="Register" />
            </form>

            <p><a href="login.php">Login</a></p>
            
            </div>
            <?php   
        } ?>
    </body>
</html>