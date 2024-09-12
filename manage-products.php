<?php include('includes/header.php'); ?>

<?php
$errors[] = '';
if(!(isset($_SESSION['logged_in'])) && !($_SESSION['logged_in'] == true)){
    header('Location:signin.php');}


$products = (new CRUD($pdo))->select('product',[],[],'','');

$products = $products->fetchAll();

if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $deleteproduct = (new CRUD($pdo))->delete('product','id',$_GET['id']);

    header('Location:manage-products.php');
    exit;
}

if(isset($_POST['edit-btn'])){

    if((!empty($_POST['name'])) && (!empty($_POST['size'])) && (!empty($_POST['price'])) && (!empty($_POST['qty'])) && (!empty($_POST['era'])) && (!empty($_POST['category']))){

        $updateproduct = (new CRUD($pdo)) -> update('product',['name','size','price','qty','eraid','categoryid'],[$_POST['name'],$_POST['size'],$_POST['price'],$_POST['qty'],$_POST['era'],$_POST['category']],['id'=>$_POST['id']]);

        header('Location:manage-products.php');
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
    <section class="manage-products py-5">
        <div class="container">
        <?php if(count($products) > 0): ?>
            <h2 class="text-center">My Products (<?= count($products); ?>)</h2>
        
        <div class="row mt-4">
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
                    <!-- <td><?//= $myresidence['userid'] ?></td> -->
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

        <?php if(isset($_GET['action']) && $_GET['action']=='edit'): 
            
                $fillinputs = (new CRUD($pdo))->select('product',[],['id'=>$_GET['id']],1,'');
                if($fillinputs):
                    $fillinput = $fillinputs->fetch();
                    // $fullname = (new CRUD($pdo))->select('person',['name','surname','email'],['id'=>$fillinput['personid']],1,'');
                    // $fullname = $fullname->fetch();

                
            
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
                        <input type="file" name="image" class="form-control" id="image" >
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