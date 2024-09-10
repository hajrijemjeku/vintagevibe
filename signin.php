<?php include('includes/header.php');
 include('includes/db.php');
 ?>

<?php
$errors = [];
    if(isset($_POST['signin-btn'])){

        $email = $_POST['email'];
        $password = $_POST['password'];
        // $remember = $_POST['remember'];
        //$remember = isset($_POST['remember']) ? $_POST['remember'] : null;

        if(!empty($email) && !empty($password)){

            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $user = (new Crud($pdo))->select('person',[],['email'=>$email], 1, '');
                $user = $user -> fetch(); //i kthen te dhanat
                //echo "<pre>";
                //print_r($user);
                if(password_verify($password, $user['password'])){
                    //echo "test";
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
                    


                    // if($remember == 1){
                    //     $expires = time() + (10*24*60*60);
                    //     setcookie('remember', true, $expires);
                    //     setcookie('user_id', $user['id'], $expires);
                    //     setcookie('email', $email, $expires);
                    //     setcookie('is_admin', $user['is_admin'], $expires);

                    // }

                   header('Location:account.php');            
                }else{
                    $errors[] = 'Wrong credentials!';
                }

            }else{
                $errors[] = 'Enter a valid email address';
            }

        }
        else{
            $errors[] = 'username or password empty';
        }

    }

?>

<section class="login my-5">
    <div class="container  d-flex justify-content-center">
        <div class="login-form w-50">
            <h3 class="mb-3 text-center">Login</h3>
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
                Please log in!
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
                <!-- <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember" value="1">
                    <label for="remember" class="form-check-label">Remember me</label>
                </div> -->
                
            <button type="submit" name="signin-btn" class="btn btn-primary">Sign In</button>
           
            </form>
            <div class="mt-4">
                <a href="signup.php">Don't have an account? Register now!</a>
            </div>
        </div>
    </div>
    

</section>

<?php include('includes/footer.php'); ?>