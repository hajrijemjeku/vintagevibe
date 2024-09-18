<?php include('includes/header.php'); ?>

<?php
if((!isset($_SESSION['logged_in'])) || (isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] !== true))){
    header('Location: signin.php');     
}

if(isset($_SESSION["logged_in"]) && ($_SESSION["logged_in"] == true)) {
    
    $crudObj = new Crud($pdo);
    $myorders = $crudObj->select('orders',[],['personid'=>$_SESSION['user_id']],'','');
    $myorders = $myorders->fetchAll();

}

?>
<?php if(count($myorders) > 0): ?>
            <h2 class="text-center"> My Orders (<?= count($myorders); ?>)</h2>
        
        <div class="row mt-4">
            <table class="table">
                <tr>
                    <th>Id</th>
                    <th>Fullname</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Notes</th>
                    <th>Total</th>
                    <th>Created_at</th>
                    <th>Send_status</th>
                    
                </tr>
                <?php foreach($myorders as $order): 
                    
                ?>
                <p style="color:goldenrod"></p>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['fullname'] ?></td>
                    <td><?= $order['email'] ?></td>
                    <td><?= $order['address'] ?></td>
                    <td><?= $order['notes'] ?></td>
                    <td><?= $order['total'] ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td style="<?php if($order['send_status'] == 'pending') {echo 'color:goldenrod';} else if($order['send_status'] == 'sent') {echo 'color:green';} else {echo 'color:red';} ?>"><?= $order['send_status'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <h2 class="text-center mt-5" style="color:darkolivegreen;"> You've got (<?= count($myorders); ?>) Orders</h2>
                <p class="text-center mt-5" style="color:darkslategrey;">You haven’t made any orders yet. 
                    <a  href="products.php" style="color:#00d974;"  class="link rounded text-decoration-none ">Explore our products</a> and make your first purchase today!
                </p>
        </div>
        <?php endif; ?>



<?php include('includes/footer.php'); ?>
