<?php include('includes/header.php');
 include('includes/db.php');
 ?>

<?php
$errors = [];
    if(isset($_POST['signin-btn'])){

        $email = $_POST['email'];
        $password = $_POST['password'];

        if(!empty($email) && !empty($password)){

            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $user = (new Crud($pdo))->select('person',[],['email'=>$email], 1, '');
                $user = $user -> fetch(); 

                if(!$user){
                    $errors[] = 'Not registered! Go to SignUp to create an account.';

                }else{
                if(password_verify($password, $user['password'])){

                    $_SESSION['logged_in'] = true;
                    $_SESSION['email'] = $email;
                    $_SESSION['user_id'] = $user['id'];
                    if($user['roleid'] == 1){
                        $_SESSION['is_admin'] = true;

                    } else if ($user['roleid'] == 2){
                        $_SESSION['is_manager'] = true;
                    } else {
                        $_SESSION['is_user'] = true;
                    }
                    

                   header('Location:account.php');            
                }else{
                    $errors[] = 'Wrong credentials!';
                }}

            }else{
                $errors[] = 'Enter a valid email address';
            }

        }
        else{
            $errors[] = 'username or password empty';
        }
    }

?>

<section class="signin my-5">
    <div class="container  d-flex justify-content-center" >
        <div class="signin-form p-4 w-50 rounded" style="background-color:rgba(116, 148, 100, 0.1)">
            <h3 class="mb-3 text-center" style="font-family: Arial, sans-serif; font-weight: bold; color: #7b9b77;">Sign In</h3>
            <?php if(count($errors)>0): ?>
            <div class="alert alert-warning">
                <?php foreach($errors as $error): ?>
                    <p class="p-0 m-0"><?= $error; ?></p>
                <?php endforeach;?>
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['success']) &&  $_GET['success'] == 1): ?>
            <div class="alert alert-info">
                Registered successfully.
                <br>
                Please Sign In!
            </div>
            <?php endif; ?>
            <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email"  aria-describedby="emailHelp">
                    <div id="divemail" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control"  id="password">
                </div>
                
            <button type="submit" name="signin-btn" class="btn btn-success">Sign In</button>
           
            </form>
            <div class="mt-4">
                <a href="signup.php" style="color:#00d974; float: right;"  class="link rounded text-decoration-none ">Don't have an account? Register now!</a>
            </div>
        </div>
    </div>
    

</section>

<?php include('includes/footer.php'); ?>