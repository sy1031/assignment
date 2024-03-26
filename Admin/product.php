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
            $product = getAll('product');
            if(!$product){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            
            if (mysqli_num_rows($product) > 0) {


            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category ID</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($product as $item) : ?>
                                <tr>
                                    <td>
                                        <?= $item['product_ID'] ?>
                                    </td>
                                    <td>
                                        <img src="../<?= $item['productImage']; ?>" style="width:50px; height=50px" alt="image">
                                    </td>
                                    <td><?= $item['productName'] ?></td>
                                    <td><?= $item['category_ID'] ?></td>
                                    <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                        <?= $item['productDescription'] ?>
                                    </td>
                                    <td>
                                        <?php
                                            if($item['productAvailability'] == 0){
                                                echo '<span class="badge bg-primary">Available</span>';
                                            }else{
                                                echo '<span class="badge bg-danger">Not Available</span>';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $item['productCreateDate'] ?>
                                    </td>
                                    <td>
                                        <a href="product_edit.php?product_ID=<?= $item['product_ID']; ?>" class="btn btn-success btn-sm">Edit</a>
                                        <a 
                                            href="product_delete.php?product_ID=<?= $item['product_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <tbody>
                    </table>
                </div>
                <?php
                } else {
                    ?>
                        <h4 class="mb-0"> No Record found</h4>
                                
                    <?php
                }
                ?>     
        </div>
    </div>
</div>

<?php include('Includes/footer.php'); ?>