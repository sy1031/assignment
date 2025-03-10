<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Categories
                <a href="category_create.php" class="btn btn-primary float-end">Create Category</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php
            $category = getAll('category');
            if(!$category){
                // Display an error message if no categories found
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }
            if(mysqli_num_rows($category) > 0) {


            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- Retrieve product details from database-->
                            <?php foreach ($category as $item) : ?>
                                <tr>
                                    <td><?= $item['category_ID'] ?></td>
                                    <td><?= $item['categoryName'] ?></td>
                                    <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                        <?= $item['categoryDescription'] ?>
                                    </td>
                                    <td>
                                        <?php
                                        // Determine the category status and display relevant message
                                            if($item['categoryStatus'] == 1){
                                                echo '<span class="badge bg-danger">Hidden</span>';
                                            }else{
                                                echo '<span class="badge bg-primary">Visible</span>';
                                            }
                                        ?>
                                    </td>
                                    <!-- Edit and delete buttons for each category -->
                                    <td>
                                        <a href="category_edit.php?category_ID=<?= $item['category_ID']; ?>" class="btn btn-success btn-sm">Edit</a>
                                        <a href="category_delete.php?category_ID=<?= $item['category_ID']; ?>" class="btn btn-danger btn-sm">Delete</a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                } 
                else 
                {
                    // Display a message if no categories found
                    ?>
                    
                        <h4 class="mb-0">No Record found</h4>
                    <?php
                }
                ?>
        </div>
    </div>
</div>

<?php include('Includes/footer.php'); ?>