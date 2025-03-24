<?php
// Incluir archivo de conexión a la base de datos
include 'modelo/conexion_bd.php';

// Verificar si el usuario está logueado y es organizador (Rol = 3)
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 3) {
    // Redirigir si no es un organizador
    header("Location: login.php");
    exit;
}

// ID del organizador (usuario actual)
$id_organizador = $_SESSION['id_usuario'];

// 1. Obtener listas desde la BD para llenar los <select>
// a) Lugares
$sqlLugar = "SELECT id_lugar, Nombre FROM lugar";
$resultLugar = $conexion->query($sqlLugar);

// b) Categorías de evento
$sqlCategoria = "SELECT Id_categoria_evento, Categoria FROM categoria_evento";
$resultCategoria = $conexion->query($sqlCategoria);

// Procesar formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $id_lugar = $_POST['id_lugar'] ?? 0;
    $id_cat_evento = $_POST['id_cat_evento'] ?? 0;
    
    // Estado predeterminado: pendiente
    $estado = 'pendiente';
    
    // Inserción en la tabla 'evento'
    $sqlInsert = "
        INSERT INTO evento 
            (Nombre, Fecha, Descripcion, Estado, Id_organizador, Id_lugar, Id_cat_evento)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    // Preparar la consulta
    $stmt = $conexion->prepare($sqlInsert);
    $stmt->bind_param(
        "ssssiis",
        $nombre,
        $fecha,
        $descripcion,
        $estado,
        $id_organizador,
        $id_lugar,
        $id_cat_evento
    );

    // Ejecutar y verificar
    if ($stmt->execute()) {
        $mensaje = "¡Evento solicitado con éxito! Un administrador revisará tu solicitud.";
        $clase_alerta = "alert-success";
    } else {
        $mensaje = "Error al enviar la solicitud: " . $stmt->error;
        $clase_alerta = "alert-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Nuevo Evento</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="text-center mb-0">Solicitar Nuevo Evento</h2>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($mensaje)): ?>
                    <div class="alert <?= $clase_alerta ?> alert-dismissible fade show" role="alert">
                        <?= $mensaje ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <!-- Nombre del evento -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Evento</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <!-- Fecha del evento -->
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                        </div>

                        <!-- Lugar -->
                        <div class="mb-3">
                            <label for="id_lugar" class="form-label">Lugar</label>
                            <select class="form-select" id="id_lugar" name="id_lugar" required>
                                <option value="" disabled selected>-- Seleccione un Lugar --</option>
                                <?php while($lug = $resultLugar->fetch_assoc()): ?>
                                    <option value="<?= $lug['id_lugar'] ?>">
                                        <?= htmlspecialchars($lug['Nombre']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Categoría de evento -->
                        <div class="mb-3">
                            <label for="id_cat_evento" class="form-label">Categoría</label>
                            <select class="form-select" id="id_cat_evento" name="id_cat_evento" required>
                                <option value="" disabled selected>-- Seleccione una Categoría --</option>
                                <?php while($cat = $resultCategoria->fetch_assoc()): ?>
                                    <option value="<?= $cat['Id_categoria_evento'] ?>">
                                        <?= htmlspecialchars($cat['Categoria']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                            <a href="mis_eventos.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-muted">
                    <p class="mb-0">Nota: Tu solicitud será revisada por un administrador antes de ser publicada.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>