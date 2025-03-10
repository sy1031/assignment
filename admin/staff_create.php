<?php include('Includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create New Employee
                <a href="staff.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="code.php" method="POST">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="">First Name *</label>
                    <input type="text" name="first_name" required class="form-control" />
                </div>

                <div class="col-md-6 mb-3">
                    <label for="">Last Name *</label>
                    <input type="text" name="last_name" required class="form-control" />
                </div>

                <div class="col-md-6 mb-3">
                    <label for="">Email *</label>
                    <input type="email" name="email" required class="form-control"/>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Password *</label>
                    <input type="password" name="password" required class="form-control"/>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="">Username *</label>
                    <input type="text" name="username" required class="form-control" />
                </div>

                <div class="col-md-6 mb-3">
                    <label for="">Phone Number *</label>
                    <input type="number" name="phone" required class="form-control"/>
                </div>

                <div class="col-md-12 mb-3 text-end">
                    <button type="submit" name="saveStaff" class="btn btn-primary">Save</button>
                </div>
            </div>

            </form>
            
        </div>
    
    </div>
</div>

<?php include('includes/footer.php'); ?>