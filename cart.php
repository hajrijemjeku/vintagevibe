<?php include('includes/header.php'); ?>
<?php
if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $element = $_GET['item'];
    unset($_SESSION['cart'][$element]);
    header('Location:cart.php');
    
} ?>
<?php if(isset($_SESSION['cart']) && (!empty($_SESSION['cart']))){?>
<table class="table">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Size</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
        <th>Actions</th>
    </tr>
   
    <?php  $total = 0;
     foreach($_SESSION['cart'] as $product_id => $product): 
        $total += $product['price'];   
    ?>
    <tr>
        <td><?=$product_id; ?></td>
        <td><?=$product['name']; ?></td>
        <td><?=$product['size']; ?></td>
        <td><?=$product['price']; ?>&euro;</td>
        <td><?=$product['qty']; ?></td>
        <td></td>
        
        <td>
            <a href='?action=delete&item=<?=$product_id?>' class='btn btn-sm btn-danger' onclick="return confirm('Are you sure?');">x</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan='4'></td>
        <td><h5><?=$total?>&euro;</h5></td>
        <td></td>
    </tr>

</table>
<?php //endif; 
    function calculateTotal($cart){
        $total = 0.0;
        if(is_array($cart) && count($cart)>0){
            foreach($cart as $item){
                $total += $item['price'];
            }
        }
        return $total;
    }?>
<?php
 if(isset($_POST['checkout']) && (isset($_SESSION['cart']) && count($_SESSION['cart'])>0)){
    $user_id = $_SESSION['user_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $notes = $_POST['notes'];
    $total = calculateTotal($_SESSION['cart']);

    $crudObj = new Crud($pdo);

    if($crudObj->insert('orders',['personid','fullname','email','address','notes','total'],[$user_id,$fullname,$email,$address,$notes,$total])){
        $insertOrder = $crudObj->select('orders',['id'],['personid'=>$_SESSION['user_id']],1,' id DESC');
        $lastOrderId = $insertOrder->fetch()['id'];
    }
    
    foreach($_SESSION['cart'] as $product){

        $checkoutdone = $crudObj->insert('order_product',['orderid','productid','qty'],[$lastOrderId,$product['id'],$product['qty']]);

        if($checkoutdone){
            $outofstock = $crudObj->update('product',['qty'],[0],['id'=>$product['id']]);
        }
    }
    unset($_SESSION['cart']);

    header('Location: cart.php');
}

?>

<section class="cart py-5">
    <div class="container">
        <h2 class="text-center">Shopping Cart(<?= count($_SESSION['cart']); ?>)</h2>
        <div class="row mt-4 ">

            <div id="cart-container">

                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="w-50" style="margin-left:300px">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Fullname</label>
                        <input type="text" name="fullname" class="form-control" placeholder="Fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required value="<?= $_SESSION['email']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Address" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rowspan="4" required></textarea>
                    </div>
                    <button class="btn btn-sm btn-secondary" type="submit" name="checkout">Check Out</button>
                </form>
            <?php } else { ?>
                    <div class="cart text-center"><h2>Shopping Cart</h2><p>Your cart is empty.</p></div>
            <?php }; ?>


            </div>
        </div>
    </div>
</section>


<?php include('includes/footer.php'); ?>
