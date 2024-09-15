<?php include('includes/header.php'); ?>

<?php
$errors[] = '';
if(!(isset($_SESSION['logged_in'])) && !($_SESSION['logged_in'] == true)){
    header('Location:signin.php');
}

if(isset($_GET['search-products']) && !empty($_GET['search-products'])){
    $products = (new CRUD($pdo))->search('product',[],['name'=>$_GET['search-products']],'');

}else{
    $products = (new CRUD($pdo))->select('product',[],[],'','');

}

$products = $products->fetchAll();

if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $deleteproduct = (new CRUD($pdo))->delete('product','id',$_GET['id']);

    header('Location:manage-products.php');
    exit;
}

if(isset($_POST['edit-btn'])){

    if((!empty($_POST['name'])) && (!empty($_POST['size'])) && (!empty($_POST['price'])) && (!empty($_POST['qty'])) && (!empty($_POST['era'])) && (!empty($_POST['category']))){

        $updateproduct = (new CRUD($pdo)) -> update('product',['name','size','price','qty','eraid','categoryid'],[$_POST['name'],$_POST['size'],$_POST['price'],$_POST['qty'],$_POST['era'],$_POST['category']],['id'=>$_POST['id']]);

        $images = $_FILES['image'];
        
        if($updateproduct){
            
            $image_uploaded = $images['name'];

            if(isset($images) && count($image_uploaded)>0){
                foreach($image_uploaded as $key => $image_name){
    
                    $img_tempname = $images['tmp_name'][$key];
                    $img_newname = time() . '-'. $image_name;
    
                    if(move_uploaded_file($img_tempname, 'assets/images/products/'.$img_newname)){
                        $updateImage = $crudObj->update('image',['src'],[$img_newname],['productid'=> $_POST['id']]);
                        //$insertImage = (new CRUD($pdo))->insert('image', ['productid', 'src'], [$_POST['id'], $img_newname]);
                        if (!$updateImage) {
                            $errors[] = "Failed to update image $image_name";
                        }
                    } else{
                        $errors[] = 'Image could not upload';
                    }
                }
                //header('Location:manage-products.php');
            }
            header('Location:manage-products.php');
        }else{
            $errors[] = 'Product could not update';
        }
        
        //header('Location:manage-products.php');
        // exit;


    }else {
        $errors [] = 'something went wrong';
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

        if(!empty($name) && !empty($price) && !empty($qty) && !empty($size) && !empty($category) && !empty($era)){

            $crudObj = new CRUD($pdo);

            if($addProduct = $crudObj->insert('product',['name','price','size','qty','categoryid','eraid'],[$name, $price, $size, $qty, $category, $era])){

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
        // if($errors) {
        //     echo '<pre>';
        //     print_r($errors);
        //     echo '</pre>';
        // } 
    }
?>
    <section class="manage-products py-5">
        <div class="container">
        <div class="search mb-3 w-100" style="margin-left:920px;">
            <form class="w-50"  method="get" action="<?= $_SERVER['PHP_SELF'];?>">
                <input class="w-50" type="search" name="search-products" value="<?= isset($_GET['search-products']) && !empty($_GET['search-products']) ? $_GET['search-products'] : '' ?>" placeholder="Search based on product name" >
            </form>
        </div>
        <?php if(count($products) > 0): ?>
            <h2 class="text-center">My Products (<?= count($products); ?>)</h2>
        
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
            <?php else: echo '<p>0 Products </p>'; ?>
        </div>
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
                                    <input type="number" class="form-control" min="0" id="qty" name="qty" required>
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
                                <button name="addproduct" type="submit" class="btn btn-primary">Save Product</button>
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
            <div class="product-form w-50 p-4 shadow rounded bg-light">
                <div class="text-center mb-4">
                    <h3 class="mb-3 text-secondary">Update Product</h3>
                </div>
                
                <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="hidden" name="id" class="form-control" id="id" value="<?= $fillinput['id']; ?>" >
                    </div>
                    <!-- <div class="mb-3">
                        <input type="hidden" name="personid" class="form-control" id="personid" value="<?//=$fillinput['personid'];?>" >
                    </div> -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="title" value="<?=$fillinput['name'];?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="size" class="form-label">Size</label>
                        <select name="size" id="size" class="form-control mb-2">
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
                        <label for="price" class="form-label">Price</label>
                        <input type="text" name="price" class="form-control" min="1" id="price" value="<?=$fillinput['price'];?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Qty</label>
                        <input type="number" name="qty" class="form-control" min="0" id="qty" value="<?=$fillinput['qty'];?>" required>
                    </div>               
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-control mb-2">
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
                        <label for="era" class="form-label">Era</label>
                        <select name="era" id="era" class="form-control mb-2">
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
                    </div>
                    
                    <button type="submit" name="edit-btn" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        <!-- </div> -->
        <?php endif;endif; ?>


        </div>
    </section>


<?php else: header('location:index.php');?>
<?php endif; endif; ?>





<?php include('includes/footer.php'); ?>