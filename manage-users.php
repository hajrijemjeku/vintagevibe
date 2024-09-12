<?php include('includes/header.php'); ?>

<?php
$errors = [];


if(!(isset($_SESSION['logged_in'])) && !($_SESSION['logged_in'] == true)){
    header('Location:signin.php');}
    if(isset($_GET['search-users']) && !empty($_GET['search-users'])){
        $users = (new CRUD($pdo))->search('person',[],['name'=>$_GET['search-users']],'');
    }else{
        $users = (new CRUD($pdo))->select('person',[],[],'','');
    }

    $users = $users->fetchAll();

if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $deleteuser = (new CRUD($pdo))->delete('person','id',$_GET['id']);

    header('Location:manage-users.php');
    exit;
}

if(isset($_POST['edit-btn'])){

    if((!empty($_POST['name'])) && (!empty($_POST['surname'])) && (!empty($_POST['email']))){

        $updateuser = (new CRUD($pdo)) -> update('person',['name','surname','email'],[$_POST['name'],$_POST['surname'],$_POST['email']],['id'=>$_POST['id']]);

        header('Location:manage-users.php');


    }else {
        $errors [] = 'Hi! Something went wrong';
    }

}


?>
<?php 
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true):
    if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true): 
?>
    <section class="manage-users py-5">
        <div class="container">
        <div class="search mb-3 w-100" style="margin-left:920px;">
            <form class="w-50"  method="get" action="<?= $_SERVER['PHP_SELF'];?>">
                <input class="w-50" type="search" name="search-users" value="<?= isset($_GET['search-users']) && !empty($_GET['search-users']) ? $_GET['search-users'] : '' ?>" placeholder="Search based on user name" >
            </form>
        </div>
        <?php if(count($errors) > 0): ?>
            <div class="alert alert-warning">
                <?php foreach($errors as $error): ?>
                    <p class="p-0 m-0"><?= $error; ?></p>
                <?php endforeach;?>
            </div>
            <?php endif;?>
        <?php if(count($users) > 0): ?>
            <h2 class="text-center">Users (<?= count($users); ?>)</h2>
        
        <div class="row mt-4">
            <table class="table">
                <tr>
                    <!-- <th>Id</th> -->
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php foreach($users as $user): 
                    if($user['id'] == $_SESSION['user_id'] && $_SESSION['user_id'] == $_SESSION['is_admin']){
                        continue;
                    }else{
                ?>
                <tr>
                    <!-- <td><?//= $myresidence['userid'] ?></td> -->
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['surname'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td>
                        <a href="?action=delete&id=<?=$user['id'];?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a> /
                        <a href="?action=edit&id=<?=$user['id'];?>" class="btn btn-sm btn-secondary">Edit</a> 
                    </td>
                </tr>
                <?php }endforeach; ?>
            </table>
            <?php else: echo '<p>0 Users </p>'; ?>
        </div>
        <?php endif; ?>

        <?php if(isset($_GET['action']) && $_GET['action']=='edit'): 
            
                $fillinputs = (new CRUD($pdo))->select('person',[],['id'=>$_GET['id']],1,'');
                $fillinput = $fillinputs->fetch();

            ?>
            
        <!-- <div class="container d-flex justify-content-center"> -->
            <div class="users-form w-50 p-4 shadow rounded bg-light mx-auto mt-5">
                <div class="text-center mb-4">
                    <h3 class="mb-3 text-secondary">Modify users data</h3>
                </div>
                
                <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="hidden" name="id" class="form-control" id="id" value="<?= $fillinput['id']; ?>" >
                    </div>
                    <!-- <div class="mb-3">
                        <input type="hidden" name="userid" class="form-control" id="userid" value="<?//=$_SESSION['user_id'];?>" >
                    </div> -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="<?=$fillinput['name'];?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" name="surname" class="form-control" id="surname" value="<?=$fillinput['surname'];?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" required value="<?=$fillinput['email'];?>">
                    </div>
                    
                    <button type="submit" name="edit-btn" class="btn btn-primary w-100">Modify</button>
                </form>
            </div>
        <!-- </div> -->
        <?php endif; ?>


        </div>
    </section>

<?php else: header('location:index.php');?>
<?php endif; endif; ?>









<?php include('includes/footer.php'); ?>