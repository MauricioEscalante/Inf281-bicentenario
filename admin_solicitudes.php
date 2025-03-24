<?php
$title = "Solicitudes De Eventos";
include 'admin_header.php'; 
// Incluir archivo de conexión a la base de datos
include 'modelo/conexion_bd.php';

// Verificar si el usuario está logueado y es administrador (Rol = 1)
//session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    // Redirigir si no es un administrador
    header("Location: login.php");
    exit;
}*/
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 1) {
    header("Location: otra1_interface.php");
    exit;
}

// Procesar acciones (aprobar/rechazar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && isset($_POST['id_evento'])) {
    $id_evento = $_POST['id_evento'];
    $accion = $_POST['accion'];
    $id_admin = $_SESSION['usuario']['Id_usuario']; // ID del administrador actual
   
    
    if ($accion === 'aprobar' || $accion === 'rechazar') {
        $nuevo_estado = ($accion === 'aprobar') ? 'Aprobado' : 'Rechazado';
        
        // Actualizar el estado del evento y asignar administrador
        $sql = "UPDATE evento SET Estado = ?, Id_administrador = ? WHERE Id_evento = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sii", $nuevo_estado, $id_admin, $id_evento);
        
        if ($stmt->execute()) {
            $mensaje = "El evento ha sido " . ($accion === 'aprobar' ? 'Aprobado' : 'Rechazado') . " con éxito.";
            $clase_alerta = "alert-success";    
            
             // NUEVO: Obtener información del evento para la notificación
             $sqlEvento = "SELECT Id_organizador, Nombre FROM evento WHERE Id_evento = ?";
             $stmtEvento = $conexion->prepare($sqlEvento);
             $stmtEvento->bind_param("i", $id_evento);
             $stmtEvento->execute();
             $resultEvento = $stmtEvento->get_result();
             
             if($rowEvento = $resultEvento->fetch_assoc()) {
                 $id_organizador = $rowEvento['Id_organizador'];
                 $nombre_evento = $rowEvento['Nombre'];
                 
                 // Mensaje para la notificación
                 $mensajeNotif = "Su evento '$nombre_evento' ha sido " . strtolower($nuevo_estado) . ".";
                 
                 // Insertar la notificación
                 $sqlNotif = "INSERT INTO notificaciones (id_usuario, mensaje, leido, fecha) 
                             VALUES (?, ?, 0, CURRENT_TIMESTAMP())";
                 $stmtNotif = $conexion->prepare($sqlNotif);
                 $stmtNotif->bind_param("is", $id_organizador, $mensajeNotif);
                 $stmtNotif->execute();
             }
            // Si fue aprobado, redirigir a la gestión de eventos
            if ($accion === 'aprobar') {
                header("Location: admin_eventos.php");
                exit;
            }
        } else {
            $mensaje = "Error al procesar la solicitud: " . $stmt->error;
            $clase_alerta = "alert-danger";
        }
    }
}

// Obtener todos los eventos pendientes con información adicional
$sql = "SELECT e.Id_evento, e.Nombre, e.Fecha, e.Descripcion, e.Estado, 
               l.Nombre AS Lugar, 
               c.Categoria,
               u.Nombre AS Organizador
        FROM evento e
        JOIN lugar l ON e.Id_lugar = l.id_lugar
        JOIN categoria_evento c ON e.Id_cat_evento = c.Id_categoria_evento
        JOIN usuario u ON e.Id_organizador = u.id_usuario
        WHERE e.Estado = 'pendiente'
        ORDER BY e.Fecha ASC";

$result = $conexion->query($sql);

?>
<!--
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Revisión de Solicitudes de Eventos</title>
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>-->
<style>
    /* Imagen de fondo */
    body {
      background: url('img/bicen1.jpeg') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh; /* Para que cubra toda la ventana */
    }
  </style>
  <!--
<body>-->
<div class="container my-5">
    <h1 class="mb-4 text-white">Solicitudes de Eventos Pendientes</h1>
    
    <?php if (isset($mensaje)): ?>
    <div class="alert <?= $clase_alerta ?> alert-dismissible fade show" role="alert">
        <?= $mensaje ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php while ($evento = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-calendar-event me-2"></i><?= htmlspecialchars($evento['Nombre']) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($evento['Fecha'])) ?><br>
                                <strong>Lugar:</strong> <?= htmlspecialchars($evento['Lugar']) ?><br>
                                <strong>Categoría:</strong> <?= htmlspecialchars($evento['Categoria']) ?><br>
                                <strong>Organizador:</strong> <?= htmlspecialchars($evento['Organizador']) ?>
                            </p>
                            <div class="mb-3">
                                <strong>Descripción:</strong>
                                <p class="card-text"><?= nl2br(htmlspecialchars($evento['Descripcion'])) ?></p>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <form method="POST" action="" class="me-2">
                                    <input type="hidden" name="id_evento" value="<?= $evento['Id_evento'] ?>">
                                    <input type="hidden" name="accion" value="aprobar">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i> Aprobar
                                    </button>
                                </form>
                                
                                <form method="POST" action="">
                                    <input type="hidden" name="id_evento" value="<?= $evento['Id_evento'] ?>">
                                    <input type="hidden" name="accion" value="rechazar">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-x-circle me-1"></i> Rechazar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> No hay solicitudes pendientes de aprobación.
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver al Panel de Administración
        </a>
        <a href="admin_eventos.php" class="btn btn-primary ms-2">
            <i class="bi bi-calendar2-check me-1"></i> Ir a Gestión de Eventos
        </a>
    </div>
</div>
<?php include("footer.php")?>
