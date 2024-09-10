<?php include('includes/header.php'); 
//  ob_start()
?>

<?php

$errors = [];
$account = (new CRUD($pdo))->select('person',[],['id'=> $_SESSION['user_id']],1,'');

if(!(isset($_SESSION['logged_in'])) && !($_SESSION['logged_in'] == true)){
    header('Location:login.php');}

                  
if($account){
    $account = $account->fetch();
}
else{
    $errors[] = 'Something went wrong';
}
if(isset($_POST['update-btn'])){

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];


    $modify = (new CRUD($pdo))->update('person',['name','surname','email','address','city','country'],[$name, $surname, $email, $address, $city, $country], ['id'=>$_SESSION['user_id']]);

    if($modify){
        $_SESSION['email'] = $email;
        
        header('Location:account.php?modify=1');
    }
    else{
        $errors[] = 'Something went wrong during modification';
    }
}
                 

?>


<section class="account my-5">
    <div class="container d-flex justify-content-center">
        <div class="account-form w-50  p-4 shadow rounded bg-light">
            <div class="text-center mb-4">
                <h3 class="mb-3 text-secondary">My Account</h3>
            </div>
            <?php if(count($errors)>0): ?>
            <div class="alert alert-warning">
                <?php foreach($errors as $error): ?>
                    <p class="p-0 m-0"><?= $error; ?></p>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
            <?php if(isset($_GET['modify']) &&  $_GET['modify'] == 1): ?>
            <div class="alert alert-info">
                Account Modified successfully.
            </div>
            <?php endif; ?>
           
            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name"  value="<?= $account['name'] ?>" >
                </div>
                <div class="mb-3">
                    <label for="surname" class="form-label">Surname</label>
                    <input type="text" name="surname" class="form-control" id="surname"  value="<?= $account['surname'] ?>" >
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" value="<?= $account['email'] ?>"  aria-describedby="emailHelp">
                    <div id="divemail" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" id="address"  value="<?= $account['address'] ?>" >
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" name="city" class="form-control" id="city"  value="<?= $account['city'] ?>" >
                </div>
                <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" id="country"  value="<?= $account['country'] ?>" >
                </div>
                <div class="mb-3">
                    <button type="submit" name="update-btn" class="btn btn-success w-50 mb-3">Modifiko</button>
                    <?php if(isset($_SESSION['is_user']) && $_SESSION['is_user']===true): ?>
                        <button type="button" name="delete-btn" class="btn btn-danger w-50"  data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $account['id'] ?>" data-residence-id="<?= $account['id'] ?>">Fshij</button>
                    <?php endif; ?>
                </div>
            </form>
            <div class="modal fade" id="deleteUserModal<?= $account['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteUserModalLabel<?= $account['id'] ?>">Confirm with password u wanna delete your '<?= $account['name'];?>' account </h5>
                            <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="deleteForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" name="password" id="password" required >
                                </div>
                                <input type="hidden" name="id" id="id" value="<?= $account['id'] ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button name="deleteacc" type="submit" class="btn btn-primary">Delete Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
         </div>
    </div>
</section>


<?php 
    if(isset($_POST['deleteacc'])) {

        $password = $_POST['password'];
        $user_id = $account['id'];

        if(!empty($password)){

            $user = (new CRUD($pdo))->select('person',[],['id'=> $user_id],1,'');
            $user = $user->fetch();

            if(password_verify($password, $user['password'])){
                $deleteuser = (new CRUD($pdo))->delete('person','id',$user_id);

                session_unset();      
                session_destroy();
                unset($_SESSION['user_id']);
                unset($_SESSION['logged_in']);
                unset($_SESSION['is_user']);
                unset($_SESSION['email']);
                header('Location: signup.php');
                exit;
                
            }else{
                $errors[] = 'something went wrong';
            }
            
        }
    }

?>



<?php include('includes/footer.php'); ?>
