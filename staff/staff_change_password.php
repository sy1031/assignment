<?php include('Includes/header.php'); ?>


<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Change Password
                <a href="homepage.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="pw_code.php" method="post">

                <div class="col-md-6 mb-3">
                    <label for="">Old Password *</label>
                    <input type="password" name="old_password" required class="form-control" />
                </div>

                <div class="col-md-6 mb-3">
                    <label for="">New Password *</label>
                    <input type="password" name="new_password" required class="form-control" />
                </div>

                <div class="col-md-6 mb-3">
                    <label for="">Confirm New Password *</label>
                    <input type="password" name="cfm_password" required class="form-control" />
                </div>

                <div class="col-md-6 mb-3">
                    <button type="submit" name="changePassword" class="btn btn-primary">Change Password</button>
                </div>





            </form>

        </div>

        <?php include('Includes/footer.php'); ?>