<?php
include('includes/header.php');  ?>

<section class="py-5">
    <div class="container">
        <?php
            if((!isset($_SESSION['logged_in'])) || (isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] !== true))){
                header('Location:signin.php');      
            }
        ?>


    <?php if(!(isset($_SESSION['is_user']) && $_SESSION['is_user'] == true)): ?>
        <div class="row text-center">
            <h2 class="text-center mt-5 mb-5" style="color:darkolivegreen;"> Dashboard </h2>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-light">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Manage Products</h5>
                        <a class="btn btn-success" href ="manage-products.php">Manage Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-light">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Manage Orders</h5>
                        <a class="btn btn-success" href="manage-orders.php">Manage Orders</a>
                    </div>
                </div>
            </div>
            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-light">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Manage Users</h5>
                            <a class="btn btn-success" href="manage-users.php">Manage Users</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php else: header('Location: index.php'); ?> <?php endif; ?>


    </div>
</section>


<?php include('includes/footer.php'); ?>