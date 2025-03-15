<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Mi Sitio</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link 
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" 
      rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
      /* Si deseas personalizar aún más los botones de Login y Registro */
      .btn-green-box {
          border-radius: 5px;
          margin: 0 0.5rem;
      }
    </style>
</head>
<body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-light pt-3 d-none d-lg-block">
        <div class="container">
            <div class="row">
                <!-- Información de contacto -->
                <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <p><i class="fa fa-envelope mr-2"></i>info@example.com</p>
                        <p class="text-body px-3">|</p>
                        <p><i class="fa fa-phone-alt mr-2"></i>+012 345 6789</p>
                    </div>
                </div>
                <!-- Redes sociales -->
                <div class="col-lg-6 text-center text-lg-right">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-primary px-3" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-primary px-3" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-primary px-3" href="">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                        <a class="text-primary px-3" href="">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="text-primary pl-3" href="">
                            <i class="fab fa-youtube"></i>
                        </a>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid position-relative nav-bar p-0">
        <div class="container-lg position-relative p-0 px-lg-3" style="z-index: 9;">
            <nav class="navbar navbar-expand-lg bg-light navbar-light shadow-lg py-3 py-lg-0 pl-3 pl-lg-5">
                <!-- Logo / Marca -->
                <a href="index.php" class="navbar-brand">
                    <h1 class="m-0 text-success">
                        <span class="text-danger">BO</span>
                        <span class="text-warning">L2</span>00
                    </h1>
                </a>
                <!-- Botón para menú en móviles -->
                <button 
                  type="button" 
                  class="navbar-toggler" 
                  data-toggle="collapse" 
                  data-target="#navbarCollapse"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div 
                  class="collapse navbar-collapse justify-content-between px-3" 
                  id="navbarCollapse"
                >
                    <div class="navbar-nav ml-auto py-0">
                        <!-- Enlaces públicos / comunes -->
                        <a href="index.php" class="nav-item nav-link">Inicio</a>
                        <a href="about.php" class="nav-item nav-link">Acerca</a>
                        <a href="services.php" class="nav-item nav-link">Servicios</a>
                        <a href="packages.php" class="nav-item nav-link">Paquetes</a>

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                                Paginas
                            </a>
                            <div class="dropdown-menu border-0 rounded-0 m-0">
                                <a href="blog.php" class="dropdown-item">Blog</a>
                                <a href="destination.php" class="dropdown-item">Destinos</a>
                                <a href="guide.php" class="dropdown-item">Guias</a>
                                <a href="testimonial.php" class="dropdown-item">Testimonios</a>
                            </div>
                        </div>

                        <a href="contact.php" class="nav-item nav-link">Contacto</a>

                        <?php
                        // Si el usuario NO está logueado, mostrar Login y Registrate en botones verdes
                        if (!isset($_SESSION['usuario'])) {
                            echo '<a href="login.php" class="nav-item nav-link btn btn-success btn-green-box text-white">Login</a>';
                            echo '<a href="registro.php" class="nav-item nav-link btn btn-success btn-green-box text-white">Regíster</a>';
                        } else {
                            // El usuario está logueado, mostrar enlaces según rol
                            // Se asume que la clave en la sesión es "Rol" (con mayúscula) y contiene un número
                            $rol = (int) $_SESSION['usuario']['Rol'];

                            switch ($rol) {
                                case 1: // Administrador
                                    echo '<a href="administrador.php" class="nav-item nav-link">Admin Panel</a>';
                                    break;
                                case 2: // Comprador
                                    echo '<a href="comprador.php" class="nav-item nav-link">Comprador Panel</a>';
                                    echo '<a href="carrito.php" class="nav-item nav-link">Carrito</a>';
                                    break;
                                case 3: // Organizador
                                    echo '<a href="organizador.php" class="nav-item nav-link">Organizador Panel</a>';
                                    break;
                                case 4: // Vendedor
                                    echo '<a href="vendedor.php" class="nav-item nav-link">Vendedor Panel</a>';
                                    break;
                                default:
                                    echo '<a href="otra_interface.php" class="nav-item nav-link">Otra</a>';
                                    break;
                            }
                            // Enlace para cerrar sesión (Logout)
                            echo '<a href="logout.php" class="nav-item nav-link">Cerrar Sesión</a>';
                        }
                        ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->
