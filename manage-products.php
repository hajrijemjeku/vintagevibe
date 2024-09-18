<?php include('includes/header.php'); ?>

<?php
$errors = [];
if(!(isset($_SESSION['logged_in'])) && !($_SESSION['logged_in'] == true)){
    header('Location:signin.php');
}

if(isset($_GET['search-products']) && !empty($_GET['search-products'])){
    $search_products = (new CRUD($pdo))->search('product',[],['name'=>$_GET['search-products']],'');
    $search_products = $search_products->fetchAll();

    if (count($search_products) > 0) {
        $products = $search_products;
    } else {
        $products = []; // No products found
    }

}else{
    $products = (new CRUD($pdo))->select('product',[],[],'','');
    $products = $products->fetchAll();
}



if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $deleteproduct = (new CRUD($pdo))->delete('product','id',$_GET['id']);

    header('Location:manage-products.php');
    exit;
}

?>

<?php
if(isset($_GET['action']) && ($_GET['action']) == 'edit'){
    if(isset($_POST['update-btn'])){

        $name = $_POST['edit-name'];
        $price = $_POST['edit-price'];
        $size = $_POST['edit-size'];
        $qty = $_POST['edit-qty'];
        $era = $_POST['edit-era'];
        $category = $_POST['edit-category'];
        $edit = (new Crud($pdo)) -> update('product', ['name', 'price','size','qty','eraid', 'categoryid'],[$name, $price, $size, $qty, $era, $category], ['id' => $_POST['productid']]);
        if ($edit) {

            header('Location:manage-products.php');
        } else {
            $errors[] = 'Failed to update product';
        }
    }

}

?>
<?php
   
if(isset($_POST['update-btn'])){
    $requiredFields = ['edit-name', 'edit-size', 'edit-price', 'edit-qty', 'edit-era', 'edit-category'];
  
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Field $field is required.";
        }
    }

    if (empty($errors)) {
        
        $crudObj = new CRUD($pdo);

        // Update product details
        $updateProduct = $crudObj->update('product', ['name', 'size', 'price', 'qty', 'eraid', 'categoryid'], [$_POST['edit-name'], $_POST['edit-size'], $_POST['edit-price'], $_POST['edit-qty'], $_POST['edit-era'], $_POST['edit-category']], ['id' => $_POST['productid']]);

        if ($updateProduct) {
            $images = $_FILES['image'];  //aray i images qe i kemi upload
            $imageNames = $images['name']; //merr src te images qe i kemi upload
            $imageCount = count($imageNames); //numeron images qe i kemi upload

            if ($imageCount > 0) {
                // Fetch all existing images for the product
                $allProdImages = $crudObj->select('image', [], ['productid' => $_POST['productid']],'','');
                $existingImages = $allProdImages->fetchAll();

                $imageIndex = 0;

                // Update existing images
                foreach ($existingImages as $existingImage) {
                    if ($imageIndex < $imageCount) {
                        $imgTempName = $images['tmp_name'][$imageIndex]; //merr tempname te image te[] qe kemi upload
                        $imgNewName = time() . '-' . $imageNames[$imageIndex]; // create new name te image te[] qe kemi upload 

                        if (move_uploaded_file($imgTempName, 'assets/images/products/' . $imgNewName)) {
                            $updateImage = $crudObj->update('image', ['src'], [$imgNewName], ['id' => $existingImage['id']]);
                            if (!$updateImage) {
                                $errors[] = "Failed to update image with ID {$existingImage['id']}";
                            }
                        } else {
                            $errors[] = "Image {$imageNames[$imageIndex]} could not be uploaded.";
                        }
                        $imageIndex++;
                    }
                }

                // Add new images if there are more uploaded images than existing images
                while ($imageIndex < $imageCount) { //imageIndex value starts where it left inside foreach
                    $imgTempName = $images['tmp_name'][$imageIndex];
                    $imgNewName = time() . '-' . $imageNames[$imageIndex];

                    if (move_uploaded_file($imgTempName, 'assets/images/products/' . $imgNewName)) {
                        $insertImage = $crudObj->insert('image', ['productid', 'src'], [$_POST['productid'], $imgNewName]);
                        if (!$insertImage) {
                            $errors[] = "Failed to insert image {$imageNames[$imageIndex]}";
                        }
                    } else {
                        $errors[] = "Image {$imageNames[$imageIndex]} could not be uploaded.";
                    }
                    $imageIndex++;
                }
            }

            // Redirect if no errors
            if (empty($errors)) {
                header('Location: manage-products.php');
                exit;
            }
        } else {
            $errors[] = 'Product could not be updated.';
        }
    }

    // Print errors if any
    if (!empty($errors)) {
        echo '<pre>';
        print_r($errors);
        echo '</pre>';
    }
}
?>

<?php 
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true):
    if(!(isset($_SESSION['is_user']) && $_SESSION['is_user'] == true)): 
?>
<?php
    if(isset($_POST['addproduct'])) {

        $name = $_POST['name'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $size = $_POST['size'];
        $category = $_POST['category'];
        $era = $_POST['era'];
        $images = $_FILES['image'];

        if(!empty($name) && !empty($size) && !empty($category) && !empty($era)){
            if($price == 0 || $price > 0 || $qty == 0 || $qty > 0){
            $crudObj = new CRUD($pdo);
            $addProduct = $crudObj->insert('product',['name','price','size','qty','categoryid','eraid'],[$name, $price, $size, $qty, $category, $era]);
            if($addProduct){

                $productId = $pdo->lastInsertId();
                $image_uploaded = $_FILES['image']['name'];

                if(isset($images) && count($image_uploaded)>0){

                    foreach($image_uploaded as $key => $image_name){

                        $img_tempname = $images['tmp_name'][$key];
                        $img_newname = time() . '-'. $image_name;

                        if(move_uploaded_file($img_tempname, 'assets/images/products/'.$img_newname)){
                            $insertImage = $crudObj->insert('image',['productid','src'],[$productId, $img_newname]);
                        }
                    }
                    header('Location:manage-products.php');
                } else{
                    $errors[] = 'Image was not added!';
                }
            } 
            else {
                $errors[] = 'Product was not added!';
            }
        }
        }

    }
?>
    <section class="manage-products py-5">
        <div class="container">
        <div class="search mb-3 w-100" style="margin-left:920px;">
            <form class="w-50"  method="get" action="<?= $_SERVER['PHP_SELF'];?>">
                <div class="input-group w-50">
                    <input class="form-control" type="search" name="search-products" value="<?= isset($_GET['search-products']) && !empty($_GET['search-products']) ? $_GET['search-products'] : '' ?>" placeholder="Search based on product name" >
                </div>
            </form>
        </div>
        <?php if (isset($_GET['search-products']) && !empty($_GET['search-products']) && count($products) === 0): ?>
            <h2 class="text-center mt-5" style="color:darkolivegreen;">No products found with the search criteria!</h2>
        <?php endif; ?>
        <?php if($errors): ?>
            <div class="alert alert-warning">
                <?php foreach($errors as $error): ?>
                    <p class="p-0 m-0"><?php echo $error; ?></p>
                <?php endforeach;?>
            </div>
        <?php endif; ?>
        <?php if(count($products) > 0): ?>
            <h2 class="text-center" style="color:darkolivegreen;">Manage Products (<?= count($products); ?>)</h2>
        
        <div class="row mt-4">
            <button type="submit" class="btn btn-outline-success add-product-btn w-25 mx-auto mb-4"  data-bs-toggle="modal"  data-bs-target="#addProductModal" data-residence-id="">
                Add new Product
            </button>
            <table class="table">
                <tr>
                    <!-- <th>Id</th> -->
                    <th>Name</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Era</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            <?php foreach($products as $product): ?>
                <tr>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['size'] ?></td>
                    <td><?= $product['price'] ?>&euro;</td>
                    <td><?= $product['qty'] ?></td>
                    <td><?= $product['eraid'] ?></td>
                    <td><?= $product['categoryid'] ?></td>
                    <td>
                        <a href="?action=delete&id=<?=$product['id'];?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a> /
                        <a href="?action=edit&id=<?=$product['id'];?>" class="btn btn-sm btn-secondary">Edit</a> 
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php else:?>
            <?php if (!isset($_GET['search-products']) || empty($_GET['search-products'])): ?>
                <h2 class="text-center mt-5" style="color:darkolivegreen;">You've got (<?= count($products); ?>) Products to Manage</h2>
                <p class="text-center mt-5" style="color:darkslategrey;">  
                    <a href="products.php" style="color:#00d974;" class="link rounded text-decoration-none"> Head to the Products </a> section to ensure everything is okay!
                </p>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Modal Structure -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Add a new Product</h5>
                            <button type="submit" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"> &times; </span>
                            </button>
                        </div>
                        <form id="productForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"  enctype="multipart/form-data" >
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="id" name="id" required>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price:</label>
                                    <input type="text" class="form-control" id="price" name="price" required>
                                </div>
                                <div class="form-group">
                                    <label for="qty">Qty:</label>
                                    <input type="number" class="form-control" id="qty" name="qty" required>
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
                                        <option value="<?= $category['id']; ?>" required><?= $category['name']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
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
                                    <label for="era" class="form-label">Era</label>
                                    <select name="era" id="era" class="form-control mb-2">
                                        <option value="">Select Era</option>
                                        <?php
                                            $era = (new CRUD($pdo))->select('era',[],[],'','');
                                            $era = $era->fetchAll();
                                            
                                            foreach($era as $years):
                                        ?>
                                        <option value="<?= $years['id']; ?>" required><?= $years['name']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image[]" class="form-control" id="image" multiple required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button name="addproduct" type="submit" class="btn btn-success">Save Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php if(isset($_GET['action']) && $_GET['action']=='edit'): 
            
                $fillinputs = (new CRUD($pdo))->select('product',[],['id'=>$_GET['id']],1,'');
                if($fillinputs):
                    $fillinput = $fillinputs->fetch();
            
            ?>
            
        <!-- <div class="container d-flex justify-content-center"> -->
            <div class="product-form w-50 p-4 shadow rounded mx-auto mt-5" style="background-color:rgba(116, 148, 100, 0.1)">
                <div class="text-center mb-4">
                    <h3 class="mb-3" style="font-family: Arial, sans-serif; font-weight: bold; color: #7b9b77;">Update Product</h3>
                </div>
                
                <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="hidden" name="productid" class="form-control" id="productid" value="<?= $fillinput['id']; ?>" >
                    </div>
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" name="edit-name" class="form-control" id="edit-name" value="<?=$fillinput['name'];?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-size" class="form-label">Size</label>
                        <select name="edit-size" id="edit-size" class="form-control mb-2">
                            <option value="" disabled>Select Size</option>
                            <?php
                                $sizeenum = (new CRUD($pdo))->distinctSelect('product','size');
                                $sizeenum = $sizeenum->fetchAll();
                                
                                foreach($sizeenum as $size):
                            ?>
                            
                            <option value="<?= $size['size']?>"<?php if($fillinput['size']==$size['size']): ?>selected <?php endif; ?>><?= $size['size']; ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-price" class="form-label">Price</label>
                        <input type="text" name="edit-price" class="form-control" min="0" value="<?=$fillinput['price'];?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-qty" class="form-label">Qty</label>
                        <input type="number" name="edit-qty" class="form-control" min="0"  value="<?=$fillinput['qty'];?>" required>
                    </div>               
                    <div class="mb-3">
                        <label for="edit-category" class="form-label">Category</label>
                        <select name="edit-category" id="edit-category" class="form-control mb-2">
                            <option value="" disabled>Select Category</option>
                            <?php
                                $categories = (new CRUD($pdo))->select('category',[],[],'','');
                                $categories = $categories->fetchAll();
                                
                                foreach($categories as $category):
                            ?>
                            <option value="<?=$category['id'];?>" <?php if($fillinput['categoryid']==$category['id']): ?>selected <?php endif; ?>><?= $category['name']; ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-era" class="form-label">Era</label>
                        <select name="edit-era" id="edit-era" class="form-control mb-2">
                            <option value="" disabled>Select Era</option>
                            <?php
                                $era = (new CRUD($pdo))->select('era',[],[],'','');
                                $era = $era->fetchAll();
                                
                                foreach($era as $years):
                            ?>
                            <option value="<?=$years['id'];?>" <?php if($fillinput['eraid']==$years['id']): ?>selected <?php endif; ?>><?= $years['name']; ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image[]" class="form-control" multiple >
                    <?php
                        $getproduct_images = (new CRUD($pdo))->select('image',[],['productid'=>$_GET['id']],'','');
                            if($getproduct_images){
                                $getproduct_images = $getproduct_images->fetchAll();
                        
                                foreach($getproduct_images as $prod_image){
                            
                    ?>
                            <?php if(!empty($prod_image['src'])) {?>
                            <img class="mx-5 mt-4" style="height:50px;"  src="./assets/images/products/<?= $prod_image['src']; ?>" alt="<?= $prod_image['alt']; ?>"/>
                            <?php } else { echo "<em>There is no image set here</em>"; } } }?><br><br>
                        </div>
                    
                    <button type="submit" name="update-btn" class="btn btn-success w-100">Update</button>
                </form>
            </div>
        <?php endif;endif; ?>


        </div>
    </section>


<?php else: header('location:index.php');?>
<?php endif; endif; ?>



<?php include('includes/footer.php'); ?>