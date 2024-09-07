<?php include('includes/header.php');
?>
<section class="product-details py-5">
    <div class="container mt-4">
        <h2 class="text-center">product_details.php</h2>
        <div class="row">
            <!-- Column 1: Multiple rows with images -->
            <div class="col-md-4 mt-5">
            <?php
                if(isset($_GET['product_id']) && !(empty($_GET['product_id']))){
                    $crudObj = new Crud($pdo);
                    $productdetails = $crudObj->select('product',['id','name','price','size'],['id'=>$_GET['product_id']],'','');
                    $productdetails = $productdetails->fetch();

                    $images = $crudObj->select('image',['id','src','alt','productid'],['productid'=> $_GET['product_id']],'','');
                    $images = $images->fetchAll();

                    $defaultImageId = $images[0]['id'] ?? null;

                }
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
            </div>
        </div>
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
