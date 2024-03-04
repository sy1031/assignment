<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
       <title>Login</title> 
    </head>

    <body>
        <?php
        include('database.php');

        if(isset($_POST['submit'])){
            $email = mysqli_real_escape_string($con,$_POST['email']);
            $password = mysqli_real_escape_string($con,$_POST['password']);

            $query = "SELECT * 
                FROM `users` 
                WHERE email='$email'
                AND password='".md5($password)."'"
                ;
                $result = mysqli_query($con,$query) or die(mysqli_error($con));
                $rows = mysqli_num_rows($result);
                if(is_array($row) && !empty($row)){
                    $_SESSION['valid'] = $row['Email'];
                    $_SESSION['username'] = $row['Username'];
                    $_SESSION['id'] = $row['Id'];
                }else{
                    echo "<div class='message'>
                    <p>Wrong Username or Password</p>
                    </div> <br>";

                    echo "<a href='index.php'><button class='btn'>Go Back</button>";
                }
                if(isset($_SESSION['valid'])){
                    header("Location: home.php");
                }
                }else{
        }
        ?>
        <header>Login</header>
        <form action="" method="post">
            <div class="field input">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" autocomplete="off" required>
            </div>
            
            <div class="field">
                <input type="submit" class="btn" name="submit" value="Login" required>
            </div>

            <div class="links">
                Don't have account? <a href="register.php">Sign Up Now</a>
            </div>
        </form>
    </body>
</html>