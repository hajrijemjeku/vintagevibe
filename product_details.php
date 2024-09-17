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
        font-size: 18px;
        padding: 10px;
    }
    .btn-cart:hover{
        background:rgb(14, 154, 101);
        border-radius: 50px;

    }
</style>
<section class="product-details py-5">
    <div class="container mt-4">
        <h2 class="text-center">product_details.php</h2>
        <div class="row">
            <!-- Column 1: Multiple rows with images -->
            <div class="col-md-4 mt-5">
            <?php
                if(isset($_GET['product_id']) && !(empty($_GET['product_id']))){
                    $crudObj = new Crud($pdo);
                    $productdetails = $crudObj->select('product',[],['id'=>$_GET['product_id']],'','');
                    $productdetails = $productdetails->fetch();

                    $images = $crudObj->select('image',[],['productid'=> $_GET['product_id']],'','');
                    $images = $images->fetchAll();

                    $defaultImageId = $images[0]['id'] ?? null;

                }
                if($productdetails['qty']>0){
            ?>
            <?php if(!empty($images)):
                   foreach ($images as $image): ?>
                    <p>
                        <img src="./assets/images/products/<?= $image['src']; ?>" alt="<?= $image['alt']; ?>" style="width:auto; height:200px;" class="img-fluid small-image" />
                    </p>    
                <?php endforeach; else: echo "There's no images to show!";endif; ?>
            </div>

            <!-- Column 2: One big image -->
            <div class="col-md-4">

                <img id="big-image" src="./assets/images/products/<?= $images[0]['src']; ?>" class="img-fluid" alt="<?= $images[0]['alt']; ?>" style="height:500px">
                
            </div>

            <!-- Column 3: Product info -->
            <div class="col-md-4">
                <div class="p-3 border">
                    <h4>Product Info</h4>
                    <p>Name:<?php echo $productdetails['name']; ?></p>
                    <p>Price:<?php echo $productdetails['price']; ?> &euro;</p>
                    <p>Size:<?php echo $productdetails['size']; ?></p>

                </div>
                <div class="p-3 border mt-3">
                    <!-- <h4>Product Info</h4>
                    <p>Name:<?//php echo $productdetails['name']; ?></p>
                    <p>Price:<?//php echo $productdetails['price']; ?> &euro;</p>
                    <p>Size:<?//php echo $productdetails['size']; ?></p> -->

                    <form method="post" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?= $productdetails['id'] ?>">
                        <?php if (in_array($productdetails['id'], $wishlistItems)): ?>
                                <input type="hidden" name="action" value="remove">
                                <button class="btn-wishlist"><i class="fa-solid fa-heart"></i></button>
                        
                        <?php else: ?>
                                <input type="hidden" name="action" value="add">
                                <button class="btn-wishlist"><i class="fa-regular fa-heart"></i></button>
                    
                        <?php endif; ?>
                    </form>
                    <?php if(isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] === true)): ?>
                        <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="d-inline" style="margin-left:140px;">
                            <input type="hidden" name="product_id" id="product_id" value="<?= $productdetails['id'] ?>">
                            <input type="hidden" name="name" value="<?= $productdetails['name'] ?>">
                            <input type="hidden" name="size"  value="<?= $productdetails['size'] ?>">
                            <input type="hidden" name="price"  value="<?= $productdetails['price'] ?>">
                            <input type="hidden" name="qty"  value="<?= $productdetails['qty'] ?>">

                            <button name="add-to-cart" id="add-to-cart" class="btn-cart" type="submit">Add to cart   <i class="fa-solid fa-cart-shopping"></i></i></button>
                        </form>
                    <?php endif; ?>

                </div>

            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <hr class="mx-auto mt-5 border-secondary">
            </div>
           
        </div>

        <div class="similar-products">
            <h3 class=" text-secondary">Similar products</h3>
            <div class="row mt-4">
                <?php
                    $similarproducts = $crudObj->search('product',['id','name','price','size','qty'],['name'=>$productdetails['name'], 'size'=>$productdetails['size']],'');
                    $similarproducts = $similarproducts->fetchAll();
                    
                    foreach($similarproducts as $similarproduct):
                        $images = $crudObj->select('image',['id','src','alt','productid'],['productid'=> $similarproduct['id']],'','');
                        $images = $images->fetchAll();
                        if(!($similarproduct['id']== $productdetails['id'])){
                            if($similarproduct['qty']>0){
                ?>
                <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                    <a href="product_details.php?product_id=<?=$similarproduct['id'];?>" class="text-decoration-none">
                        <div class="card" style="width: 18rem;">
                            <input type="hidden" name="product_id" id="product_id" value="<?= $similarproduct['id'] ?>">
                            <img src="./assets/images/products/<?= $images[0]['src']; ?>" class="card-img-top" alt="<?= $images[0]['alt']; ?>" height="300px">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $similarproduct['name'] ?></h5>
                                <p class="card-text"> 
                                    Price: <strong> <?php echo number_format($similarproduct['price'],2);  ?> &euro; </strong> / Size: <strong><?= $similarproduct['size'] ?> </strong>
                                </p>
                                

                            </div>
                        </div>
                    </a>
                </div>


                <?php
                        }}else{ continue;} endforeach;
                ?>

            </div>

        </div>
        <?php } else{ header('Location:products.php');} ?>
    </div>
    <!-- <script>
        document.querySelectorAll('.small-image').forEach(function(img) {
            img.addEventListener('click', function() {
                var bigImage = document.getElementById('big-image');
                // bigImage.src = this.getAttribute('data-src');
                // bigImage.alt = this.getAttribute('data-alt');
                bigImage.src = this.src;
                bigImage.alt = this.alt;

            });
        });
    </script> -->

    <script>
        var smallImages = document.querySelectorAll('.small-image');

        for (var i = 0; i < smallImages.length; i++) {
            var image = smallImages[i];

            image.addEventListener('click', function() {
                var bigImage = document.getElementById('big-image');

                bigImage.src = this.src;
                bigImage.alt = this.alt;
            });
        }
    </script>


</section>


<?php include('includes/footer.php') ?>