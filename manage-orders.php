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
        header("Location: " . $_SERVER['PHP_SELF']."");
    } else {
        if(isset($_GET['search-orders']) && !empty($_GET['search-orders'])){

            $search_orders = (new Crud($pdo))->search('orders', ['id','personid','fullname','address','notes','created_at','total', 'send_status'], ['fullname'=>$_GET['search-orders']], 0);
            $search_orders = $search_orders->fetchAll();

            if (count($search_orders) > 0) {
                $orders = $search_orders;
            } else {
                $orders = []; // No orders found
            }

        }else{
            $orders = (new Crud($pdo))->select('orders', ['id','personid','fullname','address','notes','created_at','total', 'send_status'], [], '', '');
            $orders = $orders->fetchAll();
        }
        
    }
    
?>

<section class="products py-5">
    <div class="container">
        <div class="search mb-3 w-100 " style="margin-left:980px;">
            <form class="w-50"  method="get" action="<?= $_SERVER['PHP_SELF'];?>">
                <div class="input-group w-50">
                    <input class="form-control" type="search" name="search-orders" value="<?= isset($_GET['search-orders']) && !empty($_GET['search-orders']) ? $_GET['search-orders'] : '' ?>" placeholder="Search based on fullname" >
                </div>
            </form>
        </div>
        <?php if (isset($_GET['search-orders']) && !empty($_GET['search-orders']) && count($orders) === 0): ?>
            <h2 class="text-center mt-5" style="color:darkolivegreen;">No orders found with the search criteria!</h2>
        <?php endif; ?>
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
        <h2 class="text-center" style="color:darkolivegreen;">Manage Orders (<?= count($orders) ?>)</h2>
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
                    <th>Action</th>
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
                        <?php } else if($order['send_status']=='cancelled'){?>
                            <td style="color:grey;">Order has been <?= $order['send_status'] ?>.</td>
                            <?php } else{ ?>
                        <td>
                            <form action="<?= $_SERVER['PHP_SELF'];?>" method="post">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>" />
                                <input style="background: none; border: none; cursor: pointer; color:red;" title="send order" type="submit" name="sentorder" value="<?= $order['send_status'] ?>"/>
                            </form> 
                        </td> <?php } ?>
                        <?php if(isset($_SESSION["is_admin"]) && ($_SESSION["is_admin"] == true)) { ?>
                        <td>
                            <a class="btn btn-sm btn-danger" href="?action=delete&id=<?= $order['id'] ?>" onclick="return confirm('Are you sure?');">delete</a> 
                            <?php if(($order['send_status']=='pending')): ?>
                            /<a class="btn btn-sm btn-secondary" href="?action=cancel&id=<?= $order['id'] ?>" onclick="return confirm('Are you sure?');">cancel</a> <?php endif; ?>

                        </td>
                        <?php } ?>

                    </tr>

                    <?php
                    if(isset($_SESSION["is_admin"]) && ($_SESSION["is_admin"] == true)) {

                        if(isset($_GET['action']) && $_GET['action'] == 'cancel'){
                            $orderId = $_GET['id'];
                            $order = (new Crud($pdo))->update('orders', ['send_status'], ['cancelled'], ['id'=> $orderId]);
                            $orders = (new Crud($pdo))->select('orders', [], ['id'=> $orderId], '','')->fetch();

                            if($order && $orders['send_status'] === 'cancelled'){
                    
                                $orderproduct = (new Crud($pdo))->select('order_product',[],['orderid'=> $orderId],'','orderid');
                                $orderproduct = $orderproduct->fetchAll();
                                foreach($orderproduct as $op){
                                    $updatestock = (new Crud($pdo))->update('product',['qty'],[1],['id'=>$op['productid']]);

                                    if(!$updatestock){
                                        echo "Failed to update stock for productID: " . $op['productid'];
                                        exit;
                                    }
                                }
                            }
                            header('Location:manage-orders.php');
                        }

                        if(isset($_GET['action']) && $_GET['action'] == 'delete'){
                            $orderId = $_GET['id'];
                            $order = (new Crud($pdo))->select('orders', [], ['id' => $orderId], '', '')->fetch();
                            if($order && $order['send_status'] == 'pending'){
                    
                                    $orderproduct = (new Crud($pdo))->select('order_product',[],['orderid'=> $orderId],'','orderid');
                                    $orderproduct = $orderproduct->fetchAll();
                                    foreach($orderproduct as $op){
                                        $updatestock = (new Crud($pdo))->update('product',['qty'],[1],['id'=>$op['productid']]);

                                        if(!$updatestock){
                                            echo "Failed to update stock for productID: " . $op['productid'];
                                            exit;
                                        }
                                    }
                            }
                            $deleteOrderProducts = (new Crud($pdo))->delete('order_product', 'orderid', $orderId);
                            $deleteOrder = (new Crud($pdo))->delete('orders', 'id', $orderId);

                            if ($deleteOrderProducts && $deleteOrder) {
                                header('Location: manage-orders.php');
                                exit;
                            } else {
                                echo 'Failed to delete order or order products.';
                                exit;
                            }
                        }
                    }
                    ?>
                    <?php endforeach; ?>
            </table>
        </div>
        <?php else :  ?> 
            <?php if (!isset($_GET['search-orders']) || empty($_GET['search-orders'])): ?>
                <h2 class="text-center mt-5" style="color:darkolivegreen;">You've got (<?= count($orders); ?>) Orders to Manage</h2>
                <p class="text-center mt-5" style="color:darkslategrey;">
                    <a href="products.php" style="color:#00d974;" class="link rounded text-decoration-none">Head to the products</a> section to ensure everything is up-to-date and ready for new orders!
                </p>
            <?php endif; ?>
        <?php endif; ?>

        <?php

?>
        
    </div>
</section>