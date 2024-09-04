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
<body>
    <header>
        
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg fixed-top bg-light navbar-light">
        <div class="container">
            <div style="background-color: #dcaac0;"  class="container d-flex justify-content-center">
                <div class="row">
                <div class="col-12 d-flex justify-content-between mb-3 mt-4">
                    <div>
                        <a style="margin-left:480px;" class="navbar-brand" href="#"
                        ><img
                        id="MDB-logo"
                        src="assets/images/logo.png"
                        alt="vintagevibe Logo"
                        draggable="false"
                        width="350"/></a>
                    </div>

                    <div class="flex-end mt-2" >
                        <form action="shop.php" role="search" >
                            <input name="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <!-- <button name="search" class="btn btn-outline-success" type="submit">Search</button> -->
                        </form>
                        <ul class="navbar-nav align-items-center mx-auto">
                            <li class="nav-item">
                                <a class="nav-link mx-2 text-white" href="#!">SignUp</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-2 text-white" href="#!">SignIn</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-2 text-white" href="#!">Wishlist</a>
                            </li>
                        </ul>

                    </div>
                    
                    
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav align-items-center mx-auto " >
                        <li class="nav-item">
                            <a class="nav-link mx-2 text-white" href="#!"><i class="fas fa-home pe-2" style="color:white "></i>Home</a>
                        </li>
                        <li class="nav-item dropdown" style="margin-left:40px">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-bars-staggered pe-2"></i>
                            Category</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="login.php">Login</a></li>
                                <li><a class="dropdown-item" href="register.php">Register</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown" style="margin-left:40px">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-calendar pe-2"></i>
                            Era</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="login.php">Login</a></li>
                                <li><a class="dropdown-item" href="register.php">Register</a></li>
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


</body>
</html>