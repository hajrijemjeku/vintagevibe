<?php include ('includes/header.php'); ?>

<?php 

   


?>

<section class="products py-5">
    <div class="container" style="background-color:lightblue">
        <h2 class="text-center">Products.php</h2>
        <div class="row mt-4">

        <?php
            // $crudObj = new Crud($pdo);
            //$products = $crudObj->select('product',['id','name','price', 'size'],[] ,'', 'DESC');
            //   print_r($products->fetch());

            // Assuming you have initialized $pdo and $crudObj somewhere before this
            $eraId = isset($_GET['era_id']) ? intval($_GET['era_id']) : 0;
            $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

            
            if ($eraId) {
                //$eraId = isset($_GET['era_id']);
                $crudObj = new Crud($pdo);
                $products = $crudObj->search('product', ['id', 'name', 'price','size'], ['eraid' => $eraId],'');
                $products = $products->fetchAll();
            }
            if ($categoryId) {
                //$eraId = isset($_GET['era_id']);
                $crudObj = new Crud($pdo);
                $products = $crudObj->search('product', ['id', 'name', 'price','size'], ['categoryid' => $categoryId],'');
                $products = $products->fetchAll();
            } 
            
            if(!$products){ ?>
                <h5 class="card-title">Produkte te eres: <?php echo $era['name'] ?> nuk kemi!</h5>
            <?php
            }
            else {
                
            foreach($products as $product):

                $images = $crudObj->select('image',['src','alt'],['productid'=>$product['id']] ,'', '');
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


                            <!-- <?//php if (in_array($product['id'], $wishlistItems)): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button class="btn-wishlist"><i class="fa-solid fa-heart"></i></button>
                                </form>
                            <?//php else: ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?=$product['id'] ?>">
                                    <input type="hidden" name="action" value="add">
                                    <button class="btn-wishlist"><i class="fa-regular fa-heart"></i></button>
                                </form>
                            <?//php endif; ?> -->
                        </div>
                    </div>
                </div>

            <?php endforeach; }; ?>

        </div>
    </div>
</section>

<?php include ('includes/footer.php'); ?>
