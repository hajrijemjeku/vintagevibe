<?php include('includes/header.php'); 

if((!isset($_SESSION['logged_in'])) || (isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] !== true))){
    header('Location: signin.php');     
}

if(isset($_SESSION["logged_in"]) && ($_SESSION["logged_in"] == true)) {
    
    if(isset($_SESSION["is_user"]) && ($_SESSION["is_user"] === true)){

        echo "<h1> NOT ALLOWED HERE.  <a href='index.php'>Go Back</a> ! </h1>";        
        die();
            //header('Location:index.php');  
    }   
}


?>

<?php
    $errors = [];

    if(isset($_POST['sentorder']) && isset($_POST['order_id'])){
        $crudObj = new Crud($pdo);
        $sentstatus = $crudObj->update('orders',['send_status'],['sent'],['id'=>$_POST['order_id']]);
        // $orders = $crudObj->select('orders', ['id','personid','fullname','address','notes','created_at','total', 'send_status'], [], '', '');
        // $orders = $orders->fetchAll();
        header("Location: " . $_SERVER['PHP_SELF']."");
    } else {
        if(isset($_GET['search-orders']) && !empty($_GET['search-orders'])){

            $orders = (new Crud($pdo))->search('orders', ['id','personid','fullname','address','notes','created_at','total', 'send_status'], ['fullname'=>$_GET['search-orders']], 0);
            $orders = $orders->fetchAll();
        }else{
            $orders = (new Crud($pdo))->select('orders', ['id','personid','fullname','address','notes','created_at','total', 'send_status'], [], '', '');
            $orders = $orders->fetchAll();
        }
        
    }
    

?>

<?php
if(isset($_SESSION["is_admin"]) && ($_SESSION["is_admin"] == true)) {

    if(isset($_GET['action']) && $_GET['action'] == 'delete'){
        //$orderid = $_GET['id'];

        if((new Crud($pdo))->delete('order_product', 'orderid', $_GET['id']) && (new Crud($pdo))->delete('orders', 'id', $_GET['id'])){

            header('Location:manage-orders.php');
        }
        
    }
}
?>


<section class="products py-5">
    <div class="container">
        <div class="search mb-3 w-100" style="margin-left:900px;">
            <form class="w-50"  method="get" action="<?= $_SERVER['PHP_SELF'];?>">
                <input class="w-50" type="search" name="search-orders" value="<?= isset($_GET['search-orders']) && !empty($_GET['search-orders']) ? $_GET['search-orders'] : '' ?>" placeholder="Search based on fullname" >
            </form>
        </div>
        <?php if(count($errors) > 0){ ?>
            <div class="alert alert-warning ">
                <?php foreach($errors as $error): ?>
                    <p class="m-0 p-0">
                        <?= $error; ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php } ?>

        <?php if(count($orders) > 0): ?>
        <h2 class="text-center">My Orders (<?= count($orders) ?>)</h2>
        <div class="row mt-4">
            <table class="table">
                <tr>
                    <th>Id</th>
                    <th>Personid</th>
                    <th>Fullname</th>
                    <th>Address</th>
                    <th>Notes</th>
                    <th>CreatedAt</th>
                    <th>Total</th>
                    <th>Status</th>
                    <?php if(isset($_SESSION["is_admin"]) && ($_SESSION["is_admin"] == true)) { ?>
                    <th>Delete</th>
                    <?php } ?>
                </tr>
                <?php foreach($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['personid'] ?></td>
                        <td><?= $order['fullname'] ?></td>
                        <td><?= $order['address'] ?></td>
                        <td><?= $order['notes'] ?></td>
                        <td><?= $order['created_at'] ?></td>
                        <td><?= $order['total'] ?> &euro;</td>
                        <?php if($order['send_status']=='sent'){ ?>
                            <td style="color:green;"><?= $order['send_status'] ?></td>
                        <?php } else{ ?>
                        <td>
                            <form action="<?= $_SERVER['PHP_SELF'];?>" method="post">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>" />
                                <input style="background: none; border: none; cursor: pointer; color:red;" type="submit" name="sentorder" value="<?= $order['send_status'] ?>"/>
                            </form> 
                        </td> <?php } ?>
                        <?php if(isset($_SESSION["is_admin"]) && ($_SESSION["is_admin"] == true)) { ?>
                        <td>
                            <a class="btn btn-sm btn-danger" href="?action=delete&id=<?= $order['id'] ?>" onclick="return confirm('Are you sure?');">x</a>
                        </td>
                        <?php } ?>

                    </tr>
                    <?php endforeach; ?>
            </table>
        </div>
        <?php else : echo "<p>Nuk keni porosi me te dhenat e kerkuara!</p>";  endif; ?>
        
    </div>
</section>