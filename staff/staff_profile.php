<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">My Profile
                <a href="homepage.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <form action="code.php" method="POST">
                <?php
                // Check if staff is logged in and their ID is stored in the session
                if (isset($_SESSION['loggedInUser']) && $_SESSION['loggedInUser']['usertype'] === 'staff') {
                    // Retrieve staff ID from the session
                    $staffId = $_SESSION['loggedInUser']['user_ID']; // Assuming 'user_id' stores the staff ID

                    // Retrieve staff data based on the staff ID
                    $staffData = getById('user', $staffId);

                    // Check if staff data was retrieved successfully
                    if ($staffData && $staffData['status'] == 200) {
                        $staff = $staffData['data']; // Extract staff data from the response
                ?>
                        <div class="container">
                            <p><strong>First Name:</strong> <?= $staff['first_name']; ?></p>
                            <p><strong>Last Name:</strong> <?= $staff['last_name']; ?></p>
                            <p><strong>Email:</strong> <?= $staff['email']; ?></p>
                            <p><strong>Phone:</strong> <?= $staff['phone']; ?></p>
                            <p><strong>Username:</strong> <?= $staff['username']; ?></p>
                        </div>
                <?php
                    } else {
                        echo '<p>Staff information not found or an error occurred.</p>';
                    }
                } else {
                    echo '<p>Staff not logged in.</p>';
                }
                ?>
            </form>
        </div>
    </div>
</div>

<?php include('Includes/footer.php'); ?>
