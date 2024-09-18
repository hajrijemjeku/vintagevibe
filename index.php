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

<?php
if(isset($_POST['add-to-cart'])){
    $product_id = $_POST['product_id'];
    $product_name = $_POST['name'];
    $product_size = $_POST['size'];
    $product_price = $_POST['price'];
    $product_qty = $_POST['qty'];


    if(isset($_SESSION['cart'])){
        if(array_key_exists($product_id, $_SESSION['cart'])){ ?>
            <script>alert('already added to cart')</script>
            
        <?php
        }else{
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product_name,
                'size' => $product_size,
                'price' => $product_price,
                'qty' => $product_qty

            ];
        }
    }else{
        $_SESSION['cart'] = [];
        $_SESSION['cart'][$product_id] = [
            'id' => $product_id,
            'name' => $product_name,
            'size' => $product_size,
            'price' => $product_price,
            'qty' => $product_qty
        ];

    }
    header('Location:cart.php');

}

?>
<style>
    .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin: 16px;
            position: relative;
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
    .btn-cart {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 24px;
    }


    .out-of-stock-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 10; /* Ensure it appears above other content */
        opacity: 0.5; /* Adjust opacity if needed */
    }

    .carousel-item {
    position: relative;
}

.carousel-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, 0.5); /* Optional: Add a semi-transparent background */
}


.carousel-overlay p {
    position: absolute;
    color: white;
    font-size: 84px; /* Adjust the size as needed */
    font-family: 'Allan';
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Optional: Add a shadow for better readability */
    opacity: 0.5;
    color:#7b9b77;
    
}

.btn-outline-primary {
    color: #7b9b77; /* Text color */
    border-color: #7b9b77; /* Border color */
}

.btn-outline-primary:hover {
    color: #fff; /* Text color on hover */
    background-color: #7b9b77; /* Background color on hover */
    border-color: #7b9b77; /* Border color on hover */
}

.btn-outline-primary:focus, 
.btn-outline-primary.focus {
    box-shadow: 0 0 0 0.2rem rgba(123, 155, 119, 0.5); /* Focus shadow color */
}
#span-price {
    color:#7b9b77;
}

</style>


<section class="slider" style="position:relative;">
    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner p-3 my-3">
            <div class="carousel-item active">
                <img src="./assets/images/slider/1.jpg" class="d-block w-100" height="450px" alt="...">
                <div class="carousel-overlay">
                    <p>Vintage Vibe</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./assets/images/slider/2.jpg" class="d-block w-100" height="450px" alt="...">
                <div class="carousel-overlay">
                    <p>Vintage Vibe</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./assets/images/slider/3.jpg" class="d-block w-100" height="450px" alt="...">
                <div class="carousel-overlay">
                    <p>Vintage Vibe</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<section class="index py-5">
    <div class="container" style="background-color:rgba(116, 148, 100, 0.1)">
        <h2 class="text-center" style="font-family: Arial, sans-serif; font-weight: bold; color: #7b9b77;">Our Products</h2>

        <div class="row mt-4">
            <div class="col-12">
                <div class="text-center">
                    <form action="<?= $_SERVER['PHP_SELF']?>" method="get" class="d-inline">
                        <button class="btn btn-outline-primary" title="Nga me e shtrenjta tek me e lira" type="submit" name="down">
                            <i class="fa fa-arrow-down"></i>
                        </button><span id="span-price"> Price </span>
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
                            $products = $crudObj->select('product',['id','name','price', 'size','qty'],[] ,'', 'id DESC');
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

                        $products = $crudObj->search('product',['id','name','price','size','qty'],['name'=> $_GET['search']],'');      
                    }
                    while($product = $products->fetch()):
                        
                        $images = $crudObj->select('image',['src','alt'],['productid'=>$product['id']] ,'', '');
                        $image = $images->fetchAll();   
                        if($product['qty']>0): 
                 ?>

                <div class="col-lg-3 col-md-3 col-sm-12 mb-3" >
                    <a href="product_details.php?product_id=<?=$product['id'];?>" class="text-decoration-none">
                        <div class="card" style="width: 18rem; height:500px;">
                            <input type="hidden" name="product_id" id="product_id" value="<?= $product['id'] ?>">
                            <img src="./assets/images/products/<?= $image[0]['src']; ?>" class="card-img-top" alt="<?= $image[0]['alt']; ?>" height="300px">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name'] ?></h5>
                                <p class="card-text"> 
                                    Price: <strong> <?php echo number_format($product['price'],2);  ?> &euro; </strong> / Size: <strong><?= $product['size'] ?> </strong>
                                </p>

                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <?php if (in_array($product['id'], $wishlistItems)): ?>
                                            <input type="hidden" name="action" value="remove">
                                            <button class="btn-wishlist"><i class="fa-solid fa-heart"></i></button>
                                    
                                    <?php else: ?>
                                            <input type="hidden" name="action" value="add">
                                            <button class="btn-wishlist"><i class="fa-regular fa-heart"></i></button>
                                
                                    <?php endif; ?>
                                </form>
                                <?php if(isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] === true)): ?>
                                    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="d-inline" style="margin-left:140px;">
                                        <input type="hidden" name="product_id" id="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="name" value="<?= $product['name'] ?>">
                                        <input type="hidden" name="size"  value="<?= $product['size'] ?>">
                                        <input type="hidden" name="price"  value="<?= $product['price'] ?>">
                                        <input type="hidden" name="qty"  value="<?= $product['qty'] ?>">

                                        <button name="add-to-cart" id="add-to-cart" class="btn-cart" type="submit"><i class="fa-solid fa-cart-shopping"></i></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>

                <?php else: ?>
                
                    <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                    <!-- <a href="product_details.php?product_id=<?//=$product['id'];?>" class="text-decoration-none"> -->
                        <div class="card" style="width: 18rem;">
                        <img src="./assets/images/products/out-of-stock.jpg" class="out-of-stock-overlay" alt="Out of Stock">
                            <input type="hidden" name="product_id" id="product_id" value="<?= $product['id'] ?>">
                            <img src="./assets/images/products/<?= $image[0]['src']; ?>" class="card-img-top" alt="<?= $image[0]['alt']; ?>" height="300px">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name'] ?></h5>
                                <p class="card-text"> 
                                    Price: <strong> <?php echo number_format($product['price'],2);  ?> &euro; </strong> / Size: <strong><?= $product['size'] ?> </strong>
                                </p>

                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <?php if (in_array($product['id'], $wishlistItems)): ?>
                                            <input type="hidden" name="action" value="remove">
                                            <button class="btn-wishlist"><i class="fa-solid fa-heart"></i></button>
                                    
                                    <?php else: ?>
                                            <input type="hidden" name="action" value="add">
                                            <button class="btn-wishlist"><i class="fa-regular fa-heart"></i></button>
                                
                                    <?php endif; ?>
                                </form>
                                <?php if(isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] === true)): ?>
                                    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="d-inline" style="margin-left:140px;">
                                        <input type="hidden" name="product_id" id="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="name" value="<?= $product['name'] ?>">
                                        <input type="hidden" name="size"  value="<?= $product['size'] ?>">
                                        <input type="hidden" name="price"  value="<?= $product['price'] ?>">
                                        <input type="hidden" name="qty"  value="<?= $product['qty'] ?>">

                                        <button name="add-to-cart" id="add-to-cart" class="btn-cart" type="submit"><i class="fa-solid fa-cart-shopping"></i></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <!-- </a> -->
                    </div>
                
                
                <?php endif; endwhile; ?>
            
        </div>
    </div>
</section>
    
    


<?php include('includes/footer.php'); ?>