<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">User List
                <a href="user_create.php" class="btn btn-primary float-end">Add User</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <?php
            $user = getAll('user');
            if(!$user){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }
            if (mysqli_num_rows($user) > 0) {


            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($user as $userItem) : ?>
                                <tr>
                                    <td><?= $userItem['username'] ?></td>
                                    <td><?= $userItem['email'] ?></td>
                                    <td><?= $userItem['password'] ?></td>
                                    <td>
                                        <a href="staff_edit.php?id=<?= $userItem['user_ID']; ?>" class="btn btn-success btn-sm">Update</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php
                    } else {
                        ?>
                            <h4 class="mb-0"> No Record found</td>
                                </tr>
                            <?php
                        }
                            ?>
                        </tbody>
                    </table>
                </div>
        </div>

    </div>
</div>
<?php include('Includes/footer.php'); ?>