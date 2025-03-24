<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verifica que sea Administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 1) {
    header("Location: otra1_interface.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : "Panel de Administración"; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome (para íconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <link href="css/style.css" rel="stylesheet">

    <style>
      nav.navbar {
          background-color: #343a40; /* Color oscuro */
          background-color: #343a40;
    position: relative;
    z-index: 9999;
      }
      nav.navbar .navbar-brand,
      nav.navbar .nav-link {
          color: #fff !important;
      }
      nav.navbar .nav-link:hover {
          color: #ddd !important;
      }
      .navbar-toggler {
          border-color: rgba(255,255,255,0.1);
      }
    </style>
</head>
<body>

<!-- Barra de navegación para Admin -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <!-- Logo y título -->
    <a class="navbar-brand" href="admin_dashboard.php">
      <img src="img/logo.png" alt="Logo" width="40" class="me-2">
      <i class="fas fa-user-shield"></i> Admin Panel
    </a>

    <!-- Botón para móviles -->
    <button 
      class="navbar-toggler" 
      type="button" 
      data-bs-toggle="collapse" 
      data-bs-target="#adminNav" 
      aria-controls="adminNav" 
      aria-expanded="false" 
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        <!-- Pestaña USUARIOS -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="usuariosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-users"></i> USUARIOS
          </a>
          <ul class="dropdown-menu" aria-labelledby="usuariosDropdown">
            <li><a class="dropdown-item" href="administrador.php"><i class="fas fa-user-cog"></i> Gestionar Usuarios</a></li>
            <li><a class="dropdown-item" href="admin_reporte_usuarios.php"><i class="fas fa-file-pdf"></i> Generar Reporte de Usuarios</a></li>
          </ul>
        </li>

        <!-- Pestaña EVENTOS -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="eventosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-calendar-alt"></i> EVENTOS
          </a>
          <ul class="dropdown-menu" aria-labelledby="eventosDropdown">
            <li><a class="dropdown-item" href="admin_eventos.php"><i class="fas fa-calendar-check"></i> Gestionar Eventos</a></li>
            <li><a class="dropdown-item" href="admin_solicitudes.php"><i class="fas fa-envelope-open-text"></i> Solicitud de Eventos</a></li>
            <li><a class="dropdown-item" href="admin_reportes.php"><i class="fas fa-file-pdf"></i> Generar Reporte de Eventos</a></li>
          </ul>
        </li>

        <!-- Pestaña CALENDARIO -->
        <li class="nav-item">
          <a class="nav-link" href="admin_calendar.php">
            <i class="fas fa-calendar"></i> CALENDARIO
          </a>
        </li>

        <!-- Cerrar sesión -->
        <li class="nav-item">
          <a class="nav-link" href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>




