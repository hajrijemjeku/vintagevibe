<?php include ('includes/header.php'); ?>

<?php 



?>
<style>
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
</style>
<section class="products py-5">
    <div class="container" style="background-color:lightblue">
        <h2 class="text-center">Products.php</h2>

        <div class="row mt-4">
            <div class="col-12">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="GET">
                    <?php if(isset($_GET['category_id'])): ?>
                        <input type="hidden" name="category_id" value="<?= (isset($_GET['category_id'])) ? ($_GET['category_id']) : '';  ?>">
                    <?php endif; if(isset($_GET['era_id'])): ?>
                        <input type="hidden" name="era_id" value="<?= (isset($_GET['era_id'])) ? ($_GET['era_id']) : '';  ?>">
                    <!-- <?//php else: ?> -->
                        <!-- <input type="hidden" name="category_id" value="<?//php if(isset($_GET['category_id'])){ ($_GET['category_id']); }else if(isset($_GET['era_id'])){ ($_GET['era_id']); } else{ '';}  ?>"> -->
                    <?php endif; ?>
                    <div class="slidecontainer d-inline">
                        <p class="d-inline"><strong>Price: </strong> <span id="demo"></span>
                            <input type="range" min="1" max="100" value="<?= (!empty($_GET['pricevalue'])) ?  $_GET['pricevalue'] : 1 ?>" class="slider" id="myRange" name="pricevalue">
                      
                    </div>
                    <input type="submit" value="Filter" name="filter">
                </form>
                

            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <hr class="mx-auto mt-5 border-secondary">
            </div>
           
        </div>

        <div class="row mt-4">

        <?php

            $eraId = isset($_GET['era_id']) ? intval($_GET['era_id']) : 0;
            $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
            
            if(isset($_GET['filter']) && !(empty($_GET['pricevalue'])) && !empty($_GET['category_id'])){
                
                $price = intval($_GET['pricevalue']);
                $category_id = intval($_GET['category_id']);
                
                $crudObj = new Crud($pdo);
                $products = $crudObj->select('product',[],['categoryid'=> $category_id, 'price <= ' => $price], '', '');
                $products = $products->fetchAll();
            }
            
            if(isset($_GET['filter']) && !(empty($_GET['pricevalue'])) && !empty($_GET['era_id'])){
                $price = intval($_GET['pricevalue']);
                $era_id = intval($_GET['era_id']);
                
                $crudObj = new Crud($pdo);
                $products = $crudObj->select('product',[],['eraid'=> $era_id, 'price <= ' => $price], '', '');
                $products = $products->fetchAll();

            }
            
            
            if(!isset($products)){
                if ($eraId) {
                    //$eraId = isset($_GET['era_id']);
                    $crudObj = new Crud($pdo);
                    $products = $crudObj->search('product', ['id', 'name', 'price','size', 'qty'], ['eraid' => $eraId],'');
                    $products = $products->fetchAll();
                }
                if($categoryId) {
                    //$eraId = isset($_GET['era_id']);
                    $crudObj = new Crud($pdo);
                    $products = $crudObj->search('product', ['id', 'name', 'price','size','qty'], ['categoryid' => $categoryId],'');
                    $products = $products->fetchAll();
                } 
                if(!$eraId && !$categoryId){
                    $crudObj = new Crud($pdo);
                    $products = $crudObj->select('product', ['id', 'name', 'price','size','qty'], [],'','');
                    $products = $products->fetchAll();
                }
            }
            
            

            
            if(!$products && isset($_GET['era_id'])){ ?>
                <h5 class="card-title">Produkte te eres se kerkuar nuk kemi!</h5>  <?//php echo $era['name'] ?>
            <?php
            } else if(!$products && isset($_GET['category_id'])){ ?>
                <h5 class="card-title">Produkte te kategorise se kerkuar me te dhena te filtruara, nuk kemi!</h5>
            <?php
            } else {
                
            foreach($products as $product):

                $images = $crudObj->select('image',['src','alt'],['productid'=>$product['id']] ,'', '');
                $image = $images->fetchAll();
                // $base_url = 'http://localhost/php/yv/vintagevibe/products.php?category_id=';
                // $endurl = $_GET['category_id'];
                // $url = $base_url . $endurl;
                if($product['qty']>0): 
                    
        ?>
                <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                    <div class="card" style="width: 18rem;">
                        <input type="hidden" name="product_id" id="product_id" value="<?= $product['id'] ?>">
                        
                        <!-- <a href="<?//=$url?>&details=<?//= $product['name']; ?>"> -->
                        <a href="product_details.php?product_id=<?=$product['id'];?>">
                            <img src="./assets/images/products/<?= $image[0]['src']; ?>" class="card-img-top" alt="<?= $image[0]['alt']; ?>" height="300px">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name'] ?></h5>
                            <p class="card-text"> 
                                Price: <strong> <?php echo number_format($product['price'],2);  ?> &euro; </strong> / Size: <strong><?= $product['size'] ?> </strong>
                            </p>

                        </div>
                    </div>
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

                            </div>
                        </div>
                    <!-- </a> -->
                    </div>




            <?php endif; endforeach; }; ?>

        </div>
    </div>
    <script>
        var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");
        output.innerHTML = slider.value;
        output.innerHTML += `&euro; `;

        slider.oninput = function() {
        output.innerHTML = this.value;
        output.innerHTML += `&euro; `;
        }
    </script>
</section>

<?php include ('includes/footer.php'); ?>
