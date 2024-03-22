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
                $parmValue = checkParamId('product_ID');
                if(!is_numeric($parmValue)){
                    echo '<h5>Id is not an integer.<h5>';
                    return false;
                }

                $product = getById('product', $paramValue);
                if($product){
                    if($product['status'] == 200)
                    {
                   
                    ?>
                    <input type="hidden" name="product_ID" value="<?= $product['data']['category_ID']; ?>" >

                    <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Select Category</label>
                                    <select name="category_ID" class="form-select">
                                        <option value="">Select Category</option>
                                        <?php
                                        $categories=getAll('categories');
                                        if($categories){
                                            if(mysqli_num_rows($categories)>0){
                                                foreach($categories as $catitem){
                                                    ?>

                                                        <option 
                                                            value="<?= $cateItem['category_ID']; ?>"
                                                            <?= $product['data']['category_ID'] == $cateItem['category_ID']  ? 'selected':''; ?>
                                                        >
                                                            <?= $cateItem['productName']; ?>
                                                        </option>

                                                    <?php

                                                }

                                            }else{
                                                echo '<option value="">No Categories Found!</option>';
                                            }
                                            
                                        }else{
                                            echo '<option value="">Something Went Wrong!</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="">Product Name</label>
                                    <input type="text" name="productName" required value = "<?= $product['data']['name']; ?>" class="form-control" />
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
                                    <label for="">Image</label>
                                    <input type="file" name="productImage" class="form-control" />
                                    <img src="../<?= $product['data']['productImage']; ?>" style="width=40px;height=40px;" alt="Image">
                                </div>

                                <div class="col-md-6">
                                    <label for="">Status(UnChecked = Visible, Checked = Hidden)</label>
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
                        echo '<h5>'.$product['message'].'<h5>';
                        return false;
                    }

                }
                else
                {
                    echo '<h5>Something Went Wrong!<h5>';
                    return false;
                }

            ?>
            </form>
            
        </div>
    
    </div>
</div>

<?php include('includes/footer.php'); ?>