
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark bg-warning shadow" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading text-dark">Interface</div>

                <a class="nav-link text-dark collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                    aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-box text-dark"></i></div>
                    Product
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                </a>

                <a class="nav-link text-dark collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOrders"
                    aria-expanded="false" aria-controls="collapseAdmins">
                    <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart text-dark"></i></div>
                    Orders
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                </a>
                <div class="collapse" id="collapseOrders" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link text-dark" href="orders.php">Orders List</a>
                        <!-- <a class="nav-link" href="cus_orders.php">Customer's Orders</a> -->
                    </nav>
                </div>

                <a class="nav-link text-dark collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                    aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave text-dark"></i></div>
                    Payment
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link text-dark" href="promocode_create.php">Create Promotional Code</a>
                        <a class="nav-link text-dark" href="promocode_view.php">View Promotional Code</a>
                        <a class="nav-link text-dark" href="paymentHistory.php">View Payment History</a>
                    </nav>
                </div>



                <a class="nav-link text-dark collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseStaffs"
                    aria-expanded="false" aria-controls="collapseStaffs">
                    <div class="sb-nav-link-icon"><i class="fas fa-users text-dark"></i></div>
                    Staff Only
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                </a>
                <div class="collapse" id="collapseStaffs" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link text-dark" href="customer_list.php">Customer List</a>
                    </nav>
                </div>

            </div>
        </div>
        <div class="sb-sidenav-footer bg-warning">
            <div class="small text-dark">Logged in as:</div>
            <div class="text-dark"><?= $_SESSION['loggedInUser']['username']; ?></div>
        </div>
    </nav>
</div>