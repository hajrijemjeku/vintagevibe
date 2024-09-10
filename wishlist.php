<?php include('includes/header.php');
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

$wishlistItems = isset($_SESSION['wishlist']) ? $_SESSION['wishlist'] : [];

$wishlistProducts = [];
// If there are items in the wishlist, fetch their details from the database
if (!empty($wishlistItems)) {

    foreach($wishlistItems as $item){
        $crudObj = new Crud($pdo);
        $wishlistProduct = $crudObj->select('product',[],['id'=>$item],'','');
        $wishlistProduct = $wishlistProduct->fetchAll();
        $wishlistProducts = array_merge($wishlistProducts, $wishlistProduct);
    }   
} else {
    $wishlistProducts = [];
}
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
<section class="wishlist py-5">
    <div class="container">
   
        <div class="row mt-4">
        <?php if (empty($wishlistProducts)): ?>
            <p>Nuk keni produkte ne wishlist!</p>

        <?php else: foreach ($wishlistProducts as $product): 
                $crudObj = new Crud($pdo);
                $images = $crudObj->select('image',[],['productid'=>$product['id']],'','');
                $image = $images->fetchAll();
        ?>
        
            <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
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
                    <!-- <h4><?//= htmlspecialchars($product['name']); ?></h4> -->
                </div>
            </div>
        <?php endforeach; endif;?>
        </div>
    </div>
</section>

<?php include('includes/footer.php');?>