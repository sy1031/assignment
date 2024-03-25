<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit User
                <a href="user.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <form action="code.php" method="POST">

                <?php
                if (isset($_GET['id'])) {
                    if ($_GET['id'] != '') {

                        $userId = $_GET['id'];
                    } else {
                        echo '<h5> No ID Found</h5>';
                        return false;
                    }
                } else {
                    echo '<h5> No ID given in params</h5>';
                    return false;
                }

                $userData = getById('user', $userId);
                if ($userData) {
                    if ($userData['status'] == 200) {
                ?>
                        <input type="hidden" name="user_ID" value="<?= $userData['data']['user_ID']; ?>">

                        <div class="row">

                            <div class="col-md-8">
                                <label for="">Username *</label>
                                <input type="text" name="username" required value="<?= $userData['data']['username']; ?>" class="form-control" />
                            </div>


                            <div class="col-md-8">
                                <label for="">Password *</label>
                                <input type="password" name="password" required class="form-control" />
                            </div>

                            <div class="col-md-12 mb-3 text-end">
                                <button type="submit" name="updateUser" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                <?php

                    } else {
                        echo '<h5>' . $userData['message'] . '</h5>';
                    }
                } else {
                    echo 'Something Went Wrong';
                    return false;
                }

                ?>

            </form>

        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>