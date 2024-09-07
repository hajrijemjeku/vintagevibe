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
        <h2 class="text-center">Latest Products</h2>
        <div class="row mt-4">
                <!-- Latest products prej db me while -->
                 <?php
                    $crudObj = new Crud($pdo);
                    $products = $crudObj->select('product',['id','name','price', 'size'],[] ,'', 'DESC');
                    //   print_r($products->fetch());

                    if(isset($_GET['search']) && (!empty($_GET['search']))){

                        $products = $crudObj->search('product',['id','name','price','size'],['name'=> $_GET['search']],'');
                        //$products = $products->fetchAll();
                        
                    }

                      
                    while($product = $products->fetch()):

                        $images = $crudObj->select('image',['src','alt'],['productid'=>$product['id']] ,'', '');
                        $image = $images->fetchAll();
                          
                 ?>

                        <!-- <a href="product_details.php?product_id=<?=$product['id'];?>">
                            <img src="./assets/images/products/<?= $image[0]['src']; ?>" class="card-img-top" alt="<?= $image[0]['alt']; ?>" height="300px">
                        </a> -->
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