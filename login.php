<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>

    <?php include('includes/header.php'); ?>
    <body class="bg-dark">
        <div class="py-5">
            <div class="mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card_login shadow rounded-4">
                            <?php alertMessage(); ?>
                                
                            <div class="p-5">
                                <h4 class="text-dark mb-3">Sign into your System</h4>
                                <form action="login_code.php" method="POST">

                                <div class="mb-3">
                                    <label>Email: </label>
                                    <input type="email" name="email" class="form-control" required />
                                </div>

                                <div class="mb-3">
                                    <label>Password: </label>
                                    <input type="password" name="password" class="form-control" required />
                                </div>

                                <div class="my-3">
                                    <button type="submit" name="loginBtn" class="btn btn-primary w-100 mt-2">Sign In</button>
                                </div>
                                </form>

                                <!-- Link to registration page -->
                                <div class="text-center">
                                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>   
        </div>
    </body>
    <?php include('includes/footer.php'); ?>
</html>