<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Category
                <a href="category.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <form action="code.php" method="POST">

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="">Name</label>
                    <input type="text" name="categoryName" required class="form-control" />
                </div>

                <div class="col-md-12 mb-3">
                    <label for="">Description</label> <!--Description can be null--->
                    <textarea name="categoryDescription" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-6"> <!--To hide categories that you don't want to delete but want it to be unvisible to users--->
                    <label for="">Would you like to hide the inserted information?</label>
                    <br/>
                    <input type="checkbox" name="categoryStatus" style="width:30px;height:30px";>
                </div>

                <div class="col-md-6 mb-3 text-end">
                    <br/>
                    <button type="submit" name="saveCategory" class="btn btn-primary">Save</button>
                </div>
            </div>

            </form>
            
        </div>
    
    </div>
</div>

<?php include('includes/footer.php'); ?>