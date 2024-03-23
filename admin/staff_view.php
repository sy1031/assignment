<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Employee Information
                <a href="staff.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <form action="code.php" method="POST">
                <?php
                if (isset($_GET['id'])) {
                    if ($_GET['id'] != '') {

                        $staffId = $_GET['id'];
                    } else {
                        echo '<h5> No ID Found</h5>';
                        return false;
                    }
                } else {
                    echo '<h5> No ID given in params</h5>';
                    return false;
                }
               
                $staffData = getById('staff', $staffId);
                if ($staffData) {
                    if ($staffData['status'] == 200) {
                ?>
                        <div class="container">
                            <p><strong>First Name:</strong> <?= $staffData['data']['first_name']; ?></p>
                            <p><strong>Last Name:</strong> <?= $staffData['data']['last_name']; ?></p>
                            <p><strong>Email:</strong> <?= $staffData['data']['email']; ?></p>
                            <p><strong>Phone:</strong> <?= $staffData['data']['phone']; ?></p>
                            <p><strong>Username:</strong> <?= $staffData['data']['username']; ?></p>
                        </div>
                <?php
                    } else {
                        echo '<h5>' . $staffData['message'] . '</h5>';
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

<?php include('Includes/footer.php'); ?>