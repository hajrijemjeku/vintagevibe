<?php include_once('includes/header.php'); ?>
<?php

$errors = [];

if(isset($_POST['signup-btn']) ){

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirm-password'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];

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
                    if($registerUser = $crudObj->insert('person',['name','surname','email','password','address','city','country'],[$name,$surname, $email, $password, $address, $city, $country])){
                    
                        header('Location:signin.php?success=1');
                        // <div class='alert alert-success'><h3>Registered successfully!</h3></div>
                    }else{
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


<section class="signup my-5">
    <div class="container d-flex justify-content-center">
        <div class="signup-form w-50 p-4 shadow rounded bg-light">
            <div class="text-center mb-4">
                <h3 class="mb-3 text-secondary">Create an account</h3>
                <p class="text-secondary">Or, <a href="signin.php" class="link-info text-decoration-none">sign in to your account</a></p>
            </div>
            <?php if(count($errors) > 0): ?>
            <div class="alert alert-warning">
                <?php foreach($errors as $error): ?>
                    <p class="p-0 m-0"><?= $error; ?></p>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
            <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="surname" class="form-label">Surname</label>
                    <input type="text" name="surname" class="form-control" id="surname" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" required>
                    <div id="divemail" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <input type="password" name="confirm-password" class="form-control" id="confirm-password" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" id="address" >
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" name="city" class="form-control" id="city" >
                </div>
                <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" id="country" >
                </div>
                
                <button type="submit" name="signup-btn" class="btn btn-primary w-100">Sign Up</button>
            </form>
        </div>
    </div>
</section>



<?php include('includes/footer.php'); ?>