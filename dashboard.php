<?php
include('includes/header.php');  ?>

<section class="py-5">
    <div class="container">
        <?php
        // $_SESSION['logged_in'] = false;
            if((!isset($_SESSION['logged_in'])) || (isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] !== true))){
                header('Location:signin.php');      
            }
        ?>


    <?php if(!(isset($_SESSION['is_user']) && $_SESSION['is_user'] == true)): ?>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a class="text-decoration-none fs-5 link-secondary" href ="manage-products.php">Manage Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a class="text-decoration-none fs-5 link-secondary" href="manage-orders.php">Manage Orders</a>
                    </div>
                </div>
            </div>
        </div>
        <?php else: header('Location: index.php'); ?> <?php endif; ?>

        <!-- <?//php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 0): ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <a href="my-orders.php">My Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        <?//php endif; ?> -->



    </div>
</section>


<?php include('includes/footer.php'); ?>