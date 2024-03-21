<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Employee List
                <a href="staff_create.php" class="btn btn-primary float-end">Add Employee</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <?php
            $staff = getAll('staff');
            if (!$staff) {
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }
            if (mysqli_num_rows($staff) > 0) {


            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($staff as $staffItem) : ?>
                                <tr>
                                    <td><?= $staffItem['staff_ID'] ?></td>
                                    <td><?= $staffItem['first_name'] ?></td>
                                    <td><?= $staffItem['last_name'] ?></td>
                                    <td><?= $staffItem['email'] ?></td>
                                    <td><?= $staffItem['username'] ?></td>
                                    <td>
                                        <a href="staff_view.php?id=<?= $staffItem['staff_ID']; ?>" class="btn btn-primary btn-sm">View</a>
                                        <a href="staff_edit.php?id=<?= $staffItem['staff_ID']; ?>" class="btn btn-success btn-sm">Edit</a>
                                        <a href="staff_delete.php?id=<?= $staffItem['staff_ID']; ?>" class="btn btn-danger btn-sm">Delete</a>
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