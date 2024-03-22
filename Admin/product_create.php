<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add Products
                <a href="product.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <form action="code.php" method="POST" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Select Category</label>
                    <select name="category_ID" class="form-select">
                        <option value="">Select Category</option>
                            <?php
                            $category=getAll('category');
                            if($category){
                                if(mysqli_num_rows($category) > 0){
                                    foreach($category as $catitem){
                                        echo '<option value="'.$catitem['category_ID'].'">'.$catitem['categoryName'].'</option>';
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
                <div class="col-md-6 mb-3">
                    <label for="">Product Name</label>
                    <input type="text" name="productName" required class="form-control" />
                </div>

                <div class="col-md-6 mb-3"> <!--new--->
                    <label for="">Brand</label>
                    <input type="text" name="productBrand" required class="form-control" />
                </div>

                <div class="col-md-12 mb-3">
                    <label for="">Description</label>
                    <textarea name="productDescription" class="form-control" rows="3"></textarea>
                </div>


                <div class="col-md-4 mb-3">
                    <label for="">Price</label>
                    <input type="text" name="productPrice" required class="form-control" />
                </div>

                <div class="col-md-4 mb-3">
                    <label for="">Quantity</label>
                    <input type="text" name="productQuantity" required class="form-control" />
                </div>

                <div class="col-md-4 mb-3">
                    <label for="">Image</label>
                    <input type="file" name="productImage" class="form-control" />
                </div>

                <div class="col-md-6">
                    <label for="">Status(Unchecked = Available, Checked = Unavailable)</label>
                    <br/>
                    <input type="checkbox" name="productAvailability" style="width:30px;height:30px";>
                </div>

                <div class="col-md-6 mb-3 text-end">
                    <br/>
                    <button type="submit" name="saveProduct" class="btn btn-primary">Save</button>
                </div>
            </div>

            </form>
            
        </div>
    
    </div>
</div>

<?php include('includes/footer.php'); ?>