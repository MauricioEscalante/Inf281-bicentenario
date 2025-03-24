<?php
session_start();

// Verificar que el usuario sea Organizador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 3) {
    header("Location: otra_interface.php");
    exit;
}

include 'modelo/conexion_bd.php'; // Incluir la conexión a la base de datos

// Verificar si se ha proporcionado un ID de evento
if (!isset($_GET['id'])) {
    header("Location: organizador.php");
    exit;
}

$id_evento = $_GET['id'];
$id_organizador = $_SESSION['usuario']['Id_usuario'];

// Obtener los datos del evento
$sql = "SELECT * FROM evento WHERE Id_evento = ? AND Id_organizador = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_evento, $id_organizador);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Si el evento no existe o no pertenece al organizador, redirigir
    header("Location: organizador.php");
    exit;
}

$evento = $result->fetch_assoc();

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];

    // Actualizar el evento
    $sqlUpdate = "UPDATE evento SET Nombre = ?, Fecha = ?, Descripcion = ? WHERE Id_evento = ? AND Id_organizador = ?";
    $stmtUpdate = $conexion->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sssii", $nombre, $fecha, $descripcion, $id_evento, $id_organizador);

    if ($stmtUpdate->execute()) {
        header("Location: organizador.php?success=1");
        exit;
    } else {
        $error = "Error al actualizar el evento.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'organizador_header.php'; ?>
<div class="container my-5">
    <h2>Editar Evento</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Evento</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($evento['Nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha del Evento</label>
            <input type="date" class="form-control" id="fecha" name="fecha" value="<?= htmlspecialchars($evento['Fecha']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($evento['Descripcion']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="organizador.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>