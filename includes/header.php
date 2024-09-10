<?php
session_start();
include 'db.php';
//ob_start();
//error_reporting(E_ALL);
// $crudObj = new Crud($pdo);

    
    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    });



    




?>

 <!-- Your HTML to display products -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VintageVibe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .navbar-light .navbar-nav .nav-link {
            color: #000;
        }
    </style>
</head>
<body style="overflow-x:hidden;">
    <header>
        
        <!-- Navbar -->
        <!-- <nav class="navbar navbar-expand-lg fixed-top bg-light navbar-light"> -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
        
            <div style="background-color: #dcaac0;"  class="w-100">
            <div class="container">
                <div class="row d-flex align-items-center py-3">
                <div class="col-12 d-flex justify-content-between mb-3 mt-4">
                    <div>
                        <a style="margin-left:480px;" class="navbar-brand" href="#">
                            <img id="logo" src="assets/images/logo.png" alt="vintagevibe Logo" draggable="false" width="350"/>
                        </a>
                    </div>

                    <div class="flex-end" >
                        <!-- Me u shfaq search vetem tek shop.php -->
                        <?php $current_page = $_SERVER['PHP_SELF'];
                            $current_page = explode('/', $current_page);
                            if(end($current_page) == 'index.php'):
                        ?>
                            <form action="index.php" method="GET" role="search" class="mt-2" >
                                <input name="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?> value = "<?php echo $_GET['search'] ?>" <?php  endif; ?>>
                            </form>
                        <?php endif; ?>
                        <ul class="navbar-nav align-items-center mx-auto">
                            <li class="nav-item">
                                <a class="nav-link mx-2 text-white" href="#!">SignUp</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-2 text-white" href="#!">SignIn</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-2 text-white" href="wishlist.php">Wishlist</a>
                            </li>
                        </ul>

                    </div>
                    
                    
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav align-items-center mx-auto " >
                        <li class="nav-item">
                            <a class="nav-link mx-2 text-white" href="index.php"><i class="fas fa-home pe-2" style="color:white "></i>Home</a>
                        </li>
                        <!-- <li class="nav-item" style="margin-left:20px">
                            <a class="nav-link mx-2 text-white" href="products.php"><i class="fa-solid fa-shirt pe-2" style="color:white"></i></i>Products</a>
                        </li> -->
                        <li class="nav-item dropdown" style="margin-left:20px">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-bars-staggered pe-2"></i>
                            Category</a>
                            <ul class="dropdown-menu">
                                <?php 
                                    $crudObj = new Crud($pdo);
                                    $categories = $crudObj->select('category',['id','name'],[],'','');
                                    $categories = $categories->fetchAll();
                                    foreach($categories as $category):
                                
                                ?>
                                <li><a class="dropdown-item" href="products.php?category_id=<?php echo $category['id'];?>"><?= ucfirst($category['name']) ?></a></li>

                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="nav-item dropdown" style="margin-left:20px">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-calendar pe-2"></i>
                            Era</a>
                            <ul class="dropdown-menu">
                            <?php 
                                $crudObj = new Crud($pdo);
                                $timeline = $crudObj->select('era',['id','name'],[],'','');
                                $timeline = $timeline->fetchAll();
                                foreach($timeline as $era):
                                
                            ?>
                                <li><a class="dropdown-item" href="products.php?era_id=<?php echo $era['id'];?>"><?= ucfirst($era['name']) ?></a></li>
                                <!-- <?//php if(basename($_SERVER['SCRIPT_FILENAME']) == "index.php"): ?>products.php?era_id=<?//php echo $era['id']; else: ?> <?//php endif; ?>" -->
                                    <!-- ><?//= ucfirst($era['name']) ?></a></li> -->
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </nav>
        <!-- Navbar -->
        
    </header>
