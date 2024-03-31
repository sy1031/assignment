<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Products
                <a href="product_create.php" class="btn btn-primary float-end">Add Product</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php
            // Query to retrieve product details along with category name
            $query = "SELECT p.product_ID, p.productName, p.productImage, p.productDescription, p.productAvailability, p.productCreateDate, c.categoryName
                      FROM product p
                      JOIN category c ON p.category_ID = c.category_ID";
            $result = mysqli_query($conn, $query);

             // Checking if the query was successful and if there are any records
            if (!$result || mysqli_num_rows($result) === 0) {
                echo '<h4>No Records Found</h4>';
            } else {
            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--Show results--->
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?= $row['product_ID'] ?></td>
                                    <td><img src="../<?= $row['productImage']; ?>" style="width:50px; height=50px" alt="image"></td>
                                    <td style="width: 180px;"><?= $row['productName'] ?></td>
                                    <td style="width: 70px;"><?= $row['categoryName'] ?></td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;"><?= $row['productDescription'] ?></td>
                                    <td><?= $row['productAvailability'] == 0 ? '<span class="badge bg-primary">Available</span>' : '<span class="badge bg-danger">Not Available</span>' ?></td>
                                    <td><?= $row['productCreateDate'] ?></td>
                                    <td>
                                        <a href="product_edit.php?product_ID=<?= $row['product_ID']; ?>" class="btn btn-success btn-sm">Edit</a>
                                        <a href="product_delete.php?product_ID=<?= $row['product_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include('Includes/footer.php'); ?>
