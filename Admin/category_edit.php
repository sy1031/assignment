<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Category
                <a href="category.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <form action="code.php" method="POST" enctype="multipart/form-data">

            <?php
            $paramId = checkParamId('category_ID');
            if(!is_numeric($paramId)){
                echo '<h5>'.$paramId.'<h5>';
                return false;
            }

            $category = getByCategoryId('category', $paramId);

            if($category['status'] == 200) 
            {
            ?>
            <input type="hidden" name="category_ID" value="<?= $category['data']['category_ID']; ?>">

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="">Name</label>
                    <input type="text" name="categoryName" value="<?= $category['data']['categoryName']; ?>" required class="form-control" />
                </div>

                <div class="col-md-12 mb-3">
                    <label for="">Description</label>
                    <textarea name="categoryDescription" class="form-control" rows="3"><?= $category['data']['categoryDescription']; ?></textarea>
                </div>

                <div class="col-md-6">
                    <label for="">Would you like to hide the inserted information?</label>
                    <br/>
                    <input type="checkbox" name="categoryStatus" <?= $category['data']['categoryStatus'] == true ? 'checked':''; ?> style="width:30px;height:30px";>
                </div>

                <div class="col-md-6 mb-3 text-end">
                    <br/>
                    <button type="submit" name="updateCategory" class="btn btn-primary">Save</button>
                </div>
            </div>
            <?php  
            }
            else
            {
                echo '<h5>'.$category['message'].'<h5>';
            }
            ?>

            </form>
            
        </div>
    
    </div>
</div>

<?php include('includes/footer.php'); ?>