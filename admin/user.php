<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">User List
                <a href="user_create.php" class="btn btn-primary float-end">Add User</a>
            </h4>
        </div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <select name="sort_alphabet" class="form-control">
                                <option value="">--Select Option--</option>
                                <option value="a-z" <?php if (isset($_GET['sort_alphabet']) && $_GET['sort_alphabet'] == "a-z") {
                                                        echo "selected";
                                                    } ?>>Ascending Order</option>
                                <option value="z-a" <?php if (isset($_GET['sort_alphabet']) && $_GET['sort_alphabet'] == "z-a") {
                                                        echo "selected";
                                                    } ?>>Descending Order</option>
                            </select>

                            <button type="submit" class="input-group-text btn btn-primary" id="basic-addon2">
                                Sort
                            </button>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group mb-3">
                            <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                                                                        echo $_GET['search'];
                                                                    } ?>" class="form_control" placeholder="Search data">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            <?php alertMessage(); ?>
            <?php
            $user = getAll('user');
            if (!$user) {
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