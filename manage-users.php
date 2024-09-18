<?php include('includes/header.php'); ?>

<?php
$errors = [];


if(!(isset($_SESSION['logged_in'])) && !($_SESSION['logged_in'] == true)){
    header('Location:signin.php');}
    if(isset($_GET['search-users']) && !empty($_GET['search-users'])){
        $search_users = (new CRUD($pdo))->search('person',[],['name'=>$_GET['search-users']],'');
        $search_users = $search_users->fetchAll();

        if (count($search_users) > 0) {
            $users = $search_users;
        } else {
            $users = []; // No users found
        }
    }
    else{
        $users = (new CRUD($pdo))->select('person',[],[],'','');
        $users = $users->fetchAll();
    }

    
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
<?php 
    if(isset($_POST['submitreview'])) {

        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirm-password'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $roleid = $_POST['role'];

        if(!empty($email) && !empty($password)){

            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                if($password == $confirmpassword){
                    $password = password_hash($password, PASSWORD_BCRYPT);
                    $crudObj = new CRUD($pdo);
                    $allusers = $crudObj->select('person',[],['email'=> $email],'','');
                    $allusers = $allusers->fetch();
                    
                    if($allusers){
                        $errors[] = 'Ths email is already registered';
                    }else{
                        if(empty($roleid)){
                            $errors[] = 'Fill user`s role';
                        }
                        else if($registerUser = $crudObj->insert('person',['name','surname','email','password','address','city','country','roleid'],[$name,$surname, $email, $password, $address, $city, $country,$roleid])){
                            header('Location:manage-users.php');
                        } else{
                            $errors[] = 'Something went wrong';
                        }
                    }
                } else {
                    $errors[] = 'Passwords do not match!';
                }
                
            }else{
                $errors[] = 'Email was not valid';
            }
        }else{
            $errors[] = 'Fill both email and password fields!';
        }   
    }
        

?>
    <section class="manage-users py-5">
        <div class="container">
        <div class="search mb-3 w-100" style="margin-left:920px;">
            <form class="w-50"  method="get" action="<?= $_SERVER['PHP_SELF'];?>">
                <div class="input-group w-50">
                    <input class="form-control" type="search" name="search-users" value="<?= isset($_GET['search-users']) && !empty($_GET['search-users']) ? $_GET['search-users'] : '' ?>" placeholder="Search based on user name" >
                </div>
            </form>
        </div>
        <?php if (isset($_GET['search-users']) && !empty($_GET['search-users']) && count($users) === 0): ?>
            <h2 class="text-center mt-5" style="color:darkolivegreen;">No users found with the search criteria!</h2>
        <?php endif; ?>

        <?php if($errors): ?>
            <div class="alert alert-warning">
                <?php foreach($errors as $error): ?>
                    <p class="p-0 m-0"><?php echo $error; ?></p>
                <?php endforeach;?>
            </div>
        <?php endif; ?>
        
        <?php if(count($users) > 0): ?>
            <h2 class="text-center" style="color:darkolivegreen;">Manage User Accounts (<?= count($users) ?>)</h2>
        
        <div class="row mt-4">
            <table class="table">
                <tr>
                    <!-- <th>Id</th> -->
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th style="width: 250px;;">Action</th>
                    <th style="width:100px;">
                        <button type="submit" class="btn btn-outline-success add-review-btn"  data-bs-toggle="modal"  data-bs-target="#addReviewModal" data-residence-id="">
                            Add user
                        </button>
                    </th>
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
        </div>
        <?php else: ?>
            <?php if (!isset($_GET['search-users']) || empty($_GET['search-users'])): ?>
                <h2 class="text-center mt-5" style="color:darkolivegreen;">You've got (<?= count($users); ?>) Users Account to Manage</h2>
                <p class="text-center mt-5" style="color:darkslategrey;"> Head to the  
                    <a href="signup.php" style="color:#00d974;" class="link rounded text-decoration-none"> SignUp </a> or  
                    <a href="signin.php" style="color:#00d974;" class="link rounded text-decoration-none">  SignIn  </a> section to ensure everything is okay!
                </p>
            <?php endif; ?>
        <?php endif; ?>

            <!-- Modal Structure -->
            <div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addReviewModalLabel">Add a new User</h5>
                            <button type="submit" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"> &times; </span>
                            </button>
                        </div>
                        <form id="reviewForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="id" name="id" required>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="surname">Surname:</label>
                                    <input type="text" class="form-control" id="surname" name="surname" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm-password">Confirm Password:</label>
                                    <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" class="form-control" id="address" name="address" >
                                </div>
                                <div class="form-group">
                                    <label for="city">City:</label>
                                    <input type="text" class="form-control" id="city" name="city" >
                                </div>
                                <div class="form-group">
                                    <label for="country">Country:</label>
                                    <input type="text" class="form-control" id="country" name="country" >
                                </div>
                                <div class="form-group">
                                    <label for="role" class="form-label">Role</label>
                                    <select name="role" id="role" class="form-control mb-2">
                                        <option value="">Select Role</option>
                                        <?php
                                            $roles = (new CRUD($pdo))->select('role',[],[],'','');
                                            $roles = $roles->fetchAll();
                                            
                                            foreach($roles as $role):
                                        ?>
                                        <option value="<?= $role['id']; ?>" required><?= $role['name']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button name="submitreview" type="submit" class="btn btn-primary">Save User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        <?php if(isset($_GET['action']) && $_GET['action']=='edit'): 
            
                $fillinputs = (new CRUD($pdo))->select('person',[],['id'=>$_GET['id']],1,'');
                $fillinput = $fillinputs->fetch();

            ?>
            
        <!-- <div class="container d-flex justify-content-center"> -->
            <div class="users-form w-50 p-4 shadow rounded mx-auto mt-5" style="background-color:rgba(116, 148, 100, 0.1)">
                <div class="text-center mb-4">
                    <h3 class="mb-3" style="font-family: Arial, sans-serif; font-weight: bold; color: #7b9b77;">Modify users data</h3>
                </div>
                
                <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="hidden" name="id" class="form-control" id="id" value="<?= $fillinput['id']; ?>" >
                    </div>
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
                    
                    <button type="submit" name="edit-btn" class="btn btn-success w-100">Modify</button>
                </form>
            </div>
        <?php endif; ?>


        </div>
    </section>

<?php else: header('location:index.php');?>
<?php endif; endif; ?>









<?php include('includes/footer.php'); ?>