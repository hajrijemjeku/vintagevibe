<?php include('includes/header.php');
// session_start();

// Initialize wishlist if not set
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Handle adding/removing items from the wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];

        if ($_POST['action'] === 'add') {
            if (!in_array($product_id, $_SESSION['wishlist'])) {
                $_SESSION['wishlist'][] = $product_id;
            }
        } elseif ($_POST['action'] === 'remove') {
            $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$product_id]);
        }
    }
}

$wishlistItems = $_SESSION['wishlist'];


?>
<style>
    .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin: 16px;
        }
    
        .card img {
            width: 100%;
            /* height: auto; */
        }
        .btn-wishlist {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
        }
</style>
<section class="index py-5">
    <div class="container" style="background-color:lightblue">
        <h2 class="text-center">All Products</h2>
        <div class="row mt-4">
            <div class="col-12">
                <div class="text-center">
                    <form action="<?= $_SERVER['PHP_SELF']?>" method="get" class="d-inline">
                        <button class="btn btn-outline-primary" title="Nga me e shtrenjta tek me e lira" type="submit" name="down">
                            <i class="fa fa-arrow-down"></i>
                        </button><span class="text-primary"> Price </span>
                        <button class="btn btn-outline-primary" title="Nga me e lira tek me e shtrenjta" type="submit" name="up">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                    </form>
                    <?php
                        if(isset($_GET['down'])){
                            $products = $crudObj->select('product',[],[],'','price DESC');
                            //$products = $products->fetchAll();
                        }else if(isset($_GET['up'])){
                            $products = $crudObj->select('product',[],[],'','price ASC');
                            //$products = $products->fetchAll();
                        }else {
                            $crudObj = new Crud($pdo);
                            $products = $crudObj->select('product',['id','name','price', 'size'],[] ,'', 'id DESC');
                        }
                    ?>
                    
                    <button type="submit" class="btn btn-outline-primary filter-btn" data-bs-toggle="modal" data-bs-target="#filterModal">Filter</button>
                </div>
        <!-- MODAL FOR FILTERING DATA-->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filter</h5>
                            <button type="submit" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="reviewForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="GET">
                            <div class="modal-body">
                                <!-- <div class="form-group">
                                    <label for="rooms">Rooms:</label>
                                    <input type="number" class="form-control" id="rooms" name="rooms" min="1">
                                </div> -->
                                <div class="form-group">
                                    <label for="size" class="form-label">Size</label>
                                    <select name="size" id="size" class="form-control mb-2">
                                        <option value="">Select Size</option>
                                        <?php
                                            $sizes = (new CRUD($pdo))->distinctSelect('product','size');
                                            $sizes = $sizes->fetchAll();
                                            
                                            foreach($sizes as $size):
                                        ?>
                                        <option value="<?= $size['size']; ?>"><?= $size['size']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category" class="form-label">Category</label>
                                    <select name="category" id="category" class="form-control mb-2">
                                        <option value="">Select Category</option>
                                        <?php
                                            $categories = (new CRUD($pdo))->select('category',[],[],'','');
                                            $categories = $categories->fetchAll();
                                            
                                            foreach($categories as $category):
                                        ?>
                                        <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="era" class="form-label">Era</label>
                                    <select name="era" id="era" class="form-control mb-2">
                                        <option value="">Select Era</option>
                                        <?php
                                            $era = (new CRUD($pdo))->select('era',[],[],'','');
                                            $era = $era->fetchAll();
                                            
                                            foreach($era as $years):
                                        ?>
                                        <option value="<?= $years['id']; ?>"><?= $years['name']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button name="filterdata" type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
            </div>
        
        <?php
            if(isset($_GET['filterdata'])){
                $size = $_GET['size'];
                $category = $_GET['category'];
                $era = $_GET['era'];

                $filterConditions = [];
                if (!empty($size)) {
                    $filterConditions['size'] = $size;
                }
                if (!empty($category)) {
                    $filterConditions['categoryid'] = $category;
                }
                if (!empty($era)) {
                    $filterConditions['eraid'] = $era;
                }

                $products = $crudObj->select('product', [], $filterConditions, '', '');
                //$products = $products->fetchAll();
               
            }
        ?>
        </div>

        <div class="row">
            <div class="col-12">
                <hr class="mx-auto mt-5 border-secondary">
            </div>
           
        </div>

        <div class="row mt-4">
                <!-- Latest products prej db me while -->
                 <?php

                    if(isset($_GET['search']) && (!empty($_GET['search']))){

                        $products = $crudObj->search('product',['id','name','price','size'],['name'=> $_GET['search']],'');
                        //$products = $products->fetchAll();
                        
                    }


                      
                    while($product = $products->fetch()):

                        $images = $crudObj->select('image',['src','alt'],['productid'=>$product['id']] ,'', '');
                        $image = $images->fetchAll();
                          
                 ?>

                <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                    <a href="product_details.php?product_id=<?=$product['id'];?>" class="text-decoration-none">
                        <div class="card" style="width: 18rem;">
                            <input type="hidden" name="product_id" id="product_id" value="<?= $product['id'] ?>">
                            <img src="./assets/images/products/<?= $image[0]['src']; ?>" class="card-img-top" alt="<?= $image[0]['alt']; ?>" height="300px">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name'] ?></h5>
                                <p class="card-text"> 
                                    Price: <strong> <?php echo number_format($product['price'],2);  ?> &euro; </strong> / Size: <strong><?= $product['size'] ?> </strong>
                                </p>


                                <?php if (in_array($product['id'], $wishlistItems)): ?>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="action" value="remove">
                                        <button class="btn-wishlist"><i class="fa-solid fa-heart"></i></button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?=$product['id'] ?>">
                                        <input type="hidden" name="action" value="add">
                                        <button class="btn-wishlist"><i class="fa-regular fa-heart"></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>

                <?php endwhile; ?>
            
        </div>
    </div>
</section>
    
    


<?php include('includes/footer.php'); ?>