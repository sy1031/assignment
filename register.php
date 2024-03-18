<?php
include('includes/header.php');

if(isset($_POST['registerBtn'])) {
    $username = validate($_POST['username']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $usertype = 'customer';

    // Check if the email already exists in the database
    $email_check_query = "SELECT * FROM user WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $email_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        redirect('register.php', 'Email already exists. Please use a different email.');
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO user (username, phone, email, usertype, password) 
                  VALUES ('$username', '$phone', '$email', '$usertype', '$hashedPassword')";
        $result = mysqli_query($conn, $query);

        if($result) {
            redirect('login.php', 'Registration successful. Please login to continue.');
        } else {
            redirect('register.php', 'Error registering user. Please try again.');
        }
    }
}
?>

<div class="py-5 bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4">

                    <?php alertMessage(); ?>
                    
                    <div class="p-5">
                        <h4 class="text-dark mb-3">Register an Account</h4>
                        <form action="register.php" method="POST">

                            <div class="mb-3">
                                <label>Username: </label>
                                <input type="text" name="username" class="form-control" required />
                            </div>

                            <div class="mb-3">
                                <label>Phone: </label>
                                <input type="text" name="phone" class="form-control" required />
                            </div>

                            <div class="mb-3">
                                <label>Email: </label>
                                <input type="email" name="email" class="form-control" required />
                            </div>

                            <div class="mb-3">
                                <label>Password: </label>
                                <input type="password" name="password" class="form-control" required />
                            </div>

                            <div class="my-3">
                                <button type="submit" name="registerBtn" class="btn btn-primary w-100 mt-2">Register</button>
                            </div>
                        </form>
                        
                        <!-- Link to login page -->
                        <div class="text-center">
                            <p>Already have an account? <a href="login.php">Sign in here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
