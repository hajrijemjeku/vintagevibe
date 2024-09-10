<?php include('includes/header.php'); ?>
<h4>cart.php</h4>
<?php
if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $element = $_GET['item'];
    unset($_SESSION['cart'][$element]);
    header('Location:cart.php');
    
} ?>
<?php if(isset($_SESSION['cart']) && (!empty($_SESSION['cart']))):?>
<table class="table">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Size</th>
        <th>Price</th>
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
<?php endif; ?>




<?php include('includes/footer.php'); ?>
