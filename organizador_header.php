<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 3) {
    header("Location: otra_interface.php");
    exit;
}

include 'modelo/conexion_bd.php';

$id_usuario = $_SESSION['usuario']['Id_usuario'];
$sqlNotif = "SELECT COUNT(*) as total FROM notificaciones WHERE id_usuario = ? AND leido = 0";
$stmt = $conexion->prepare($sqlNotif);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultNotif = $stmt->get_result();
$rowNotif = $resultNotif->fetch_assoc();
$notifCount = $rowNotif['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Organizador</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Estilos personalizados -->
  <link href="css/style.css" rel="stylesheet">
  <style>
    nav.navbar {
      background-color: #6c757d;
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
    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255,0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22' /%3E%3C/svg%3E");
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="organizador_dashboard.php">
      <img src="img/logo.png" alt="Logo" width="40" class="me-2">
      <i class="fas fa-calendar-check"></i> Panel Organizador
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#organizadorNav" aria-controls="organizadorNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="organizadorNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="organizador.php">
            <i class="fas fa-clipboard-list"></i> Mis Eventos
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="organizador_solicitudes.php">
            <i class="fas fa-envelope-open-text"></i> Mis Solicitudes
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="organizador_calendar.php">
            <i class="fas fa-calendar-alt"></i> Calendario
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="organizador_perfil.php">
            <i class="fas fa-user-cog"></i> Mi Perfil
          </a>
        </li>
        <!-- Ícono de notificaciones con contador -->
        <li class="nav-item">
          <a class="nav-link position-relative" href="organizador_notificaciones.php">
            <i class="fas fa-bell"></i>
            <?php if($notifCount > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $notifCount ?>
                <span class="visually-hidden">notificaciones sin leer</span>
              </span>
            <?php endif; ?>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Fin del header -->
