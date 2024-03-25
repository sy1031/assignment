<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit customer
                <a href="customers.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <form action="code.php" method="POST">

                <?php
                if (isset($_GET['id'])) {
                    if ($_GET['id'] != '') {

                        $customerId = $_GET['id'];
                    } else {
                        echo '<h5> No ID Found</h5>';
                        return false;
                    }
                } else {
                    echo '<h5> No ID given in params</h5>';
                    return false;
                }

                $customerData = getById('customer', $customerId);
                if ($customerData) {
                    if ($customerData['status'] == 200) {
                ?>
                        <input type="hidden" name="customer_ID" value="<?= $customerData['data']['customer_ID']; ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="">First Name *</label>
                                <input type="text" name="first_name" required value="<?= $customerData['data']['first_name']; ?>" class="form-control" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Last Name *</label>
                                <input type="text" name="last_name" required value="<?= $customerData['data']['last_name']; ?>" class="form-control" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Email *</label>
                                <input type="email" name="email" required value="<?= $customerData['data']['email']; ?>" class="form-control" />
                            </div>
                        
                            <div class="col-md-6 mb-3">
                                <label for="">Username *</label>
                                <input type="text" name="username" required value="<?= $customerData['data']['username']; ?>" class="form-control" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Phone Number *</label>
                                <input type="number" name="phone" required value="<?= $customerData['data']['phone']; ?>" class="form-control" />
                            </div>

                            <div class="col-md-12 mb-3 text-end">
                                <button type="submit" name="updateCustomer" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                <?php

                    } else {
                        echo '<h5>' . $customerData['message'] . '</h5>';
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