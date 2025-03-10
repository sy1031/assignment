<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Customer List</h4>
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
                                                                    } ?>" class="form_control" placeholder=" Search data">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            <?php alertMessage(); ?>
            <?php
            $customer = getCustomerAll('customer');
            if (!$customer) {
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }
            if (mysqli_num_rows($customer) > 0) {


            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($customer as $customerItem) : ?>
                                <tr>
                                    <td><?= $customerItem['customer_ID'] ?></td>
                                    <td><?= $customerItem['username'] ?></td>
                                    <td><?= $customerItem['first_name'] ?></td>
                                    <td><?= $customerItem['last_name'] ?></td>
                                    <td><?= $customerItem['email'] ?></td>
                                    <td><?= $customerItem['address'] ?></td>
                                    <td>
                                        <a href="customer_edit.php?id=<?= $customerItem['customer_ID']; ?>" class="btn btn-success btn-sm">Update</a>
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