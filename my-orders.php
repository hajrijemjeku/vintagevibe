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
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['fullname'] ?></td>
                    <td><?= $order['email'] ?></td>
                    <td><?= $order['address'] ?></td>
                    <td><?= $order['notes'] ?></td>
                    <td><?= $order['total'] ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td><?= $order['send_status'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: echo '<p>0 Orders </p>'; ?>
        </div>
        <?php endif; ?>



<?php include('includes/footer.php'); ?>
