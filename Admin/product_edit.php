<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Product
                <a href="product.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <form action="code.php" method="POST" enctype="multipart/form-data">

            <?php
            // Validating the product ID from the URL parameter
                $paramId = checkParamId('product_ID');
                if(!is_numeric($paramId)){
                    echo '<h5>'.$paramId.'<h5>';
                    return false;
                }

                // Getting the product details by product ID
                $product = getByProductId('product', $paramId);

                 // Checking if the product details are retrieved successfully
                if($product){
                    if($product['status'] == 200)
                    {
                   
                    ?>
                    <!-- Hidden input field to store the product ID -->
                    <input type="hidden" name="product_ID" value="<?= $product['data']['product_ID']; ?>" >

                    <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Select Category</label>
                                    <select name="category_ID" class="form-select">
                                        <option value="">Select Category</option>
                                        <?php
                                        // Getting all categories
                                        $category = getCategoryAll('category');
                                        if($category){
                                            if(mysqli_num_rows($category)>0){
                                                // Iterating through each category
                                                foreach($category as $item){
                                                    ?>

                                                        <!-- Option for each category -->
                                                        <option 
                                                            value="<?= $item['category_ID']; ?>"
                                                            <?= $product['data']['category_ID'] == $item['category_ID']  ? 'selected':''; ?>
                                                        >
                                                            <?= $item['categoryName']; ?><!--line with issue--->
                                                        </option>

                                                    <?php

                                                }

                                            }else{
                                                // Display if no categories found
                                                echo '<option value="">No Categories Found!</option>';
                                            }
                                            
                                        }else{
                                            //Display if something went wrong
                                            echo '<option value="">Something Went Wrong!</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- Input field for product details -->
                                <div class="col-md-12 mb-3">
                                    <label for="">Product Name</label>
                                    <input type="text" name="productName" required value = "<?= $product['data']['productName']; ?>" class="form-control" />
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="">Description</label>
                                    <textarea name="productDescription" class="form-control" rows="3" ><?= $product['data']['productDescription']; ?></textarea>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="">Price</label>
                                    <input type="text" name="productPrice" required value ="<?= $product['data']['productPrice']; ?>" class="form-control" />
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="">Quantity</label>
                                    <input type="text" name="productQuantity" required value = "<?= $product['data']['productQuantity']; ?>" class="form-control" />
                                </div>

                                <div class="col-md-4 mb-3">
                                    <!-- For image submission -->
                                    <label for="">Image</label>
                                    <input type="file" name="productImage" class="form-control" />
                                    <?php if(empty($_FILES['productImage']['name'])): ?>
                                        <input type="hidden" name="oldProductImage" value="<?= $product['data']['productImage']; ?>" />
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="">Would you like to hide this product?</label>
                                    <br/>
                                    <input type="checkbox" name="productAvailability" <?= $product['data']['productAvailability'] == true ? 'checked':''; ?> style="width:30px;height:30px";>
                                </div>

                                <div class="col-md-6 mb-3 text-end">
                                    <br/>
                                    <button type="submit" name="updateProduct" class="btn btn-primary">Update</button>
                                </div>
                    </div>
                    <?php

                    }
                    else
                    {
                        // Displaying an error message if the product status is not 200
                        echo '<h5>'.$product['message'].'<h5>';
                        return false;
                    }

                }
                else
                {
                    // Displaying an error message if the product details are not retrieved
                    echo '<h5>Something Went Wrong!<h5>';
                    return false;
                }

            ?>
            </form>
            
        </div>
    
    </div>
</div>

<?php include('includes/footer.php'); ?>