<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 3) {
    header("Location: otra_interface.php");
    exit;
}
include 'modelo/conexion_bd.php';
$id_usuario = $_SESSION['usuario']['Id_usuario'];

// Procesar la eliminación de una notificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_notificacion'])) {
    $id_notificacion = $_POST['id_notificacion'];

    // Eliminar la notificación
    $sqlDelete = "DELETE FROM notificaciones WHERE id_notificacion = ? AND id_usuario = ?";
    $stmtDelete = $conexion->prepare($sqlDelete);
    $stmtDelete->bind_param("ii", $id_notificacion, $id_usuario);

    if ($stmtDelete->execute()) {
        $mensaje = "Notificación eliminada correctamente.";
    } else {
        $mensaje = "Error al eliminar la notificación: " . $stmtDelete->error;
    }
}

// Obtener todas las notificaciones (leídas y no leídas)
$sqlNotif = "SELECT * FROM notificaciones WHERE id_usuario = ? ORDER BY fecha DESC";
$stmt = $conexion->prepare($sqlNotif);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultNotif = $stmt->get_result();

// Almacenar los resultados en un array
$notificaciones = $resultNotif->fetch_all(MYSQLI_ASSOC);

// Marcar como leídas todas las notificaciones no leídas
$sqlUpdate = "UPDATE notificaciones SET leido = 1 WHERE id_usuario = ? AND leido = 0";
$stmtUpdate = $conexion->prepare($sqlUpdate);
$stmtUpdate->bind_param("i", $id_usuario);
$stmtUpdate->execute();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Notificaciones</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<?php include 'organizador_header.php'; ?>
<div class="container my-5">
  <h2>Notificaciones</h2>

  <?php if (isset($mensaje)): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= $mensaje ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if(empty($notificaciones)): ?>
    <div class="alert alert-info">
      No tienes notificaciones.
    </div>
  <?php else: ?>
    <ul class="list-group">
      <?php foreach($notificaciones as $notif): ?>
        <li class="list-group-item <?= $notif['leido'] ? 'list-group-item-secondary' : 'list-group-item-warning'; ?>">
          <p><?= htmlspecialchars($notif['mensaje']); ?></p>
          <small class="text-muted"><?= date('d/m/Y H:i', strtotime($notif['fecha'])); ?></small>
          <?php if($notif['leido']): ?>
            <span class="badge bg-success float-end">Leída</span>
          <?php else: ?>
            <span class="badge bg-warning float-end">No leída</span>
          <?php endif; ?>

          <!-- Botón de eliminar -->
          <form method="POST" action="" style="display: inline;">
            <input type="hidden" name="id_notificacion" value="<?= $notif['id_notificacion']; ?>">
            <button type="submit" class="btn btn-danger btn-sm float-end ms-2" onclick="return confirm('¿Estás seguro de que quieres eliminar esta notificación?');">
              <i class="bi bi-trash"></i> Eliminar
            </button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>