<?php
session_start();
// Verificar que el usuario sea Organizador (Rol = 3)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 3) {
    header("Location: otra_interface.php");
    exit;
}

include 'organizador_header.php';
include 'modelo/conexion_bd.php';

// Obtener listas para llenar los <select>
// a) Lugares
$sqlLugar = "SELECT id_lugar, Nombre FROM lugar";
$resultLugar = $conexion->query($sqlLugar);

// b) Categorías de evento
$sqlCategoria = "SELECT Id_categoria_evento, Categoria FROM categoria_evento";
$resultCategoria = $conexion->query($sqlCategoria);

// Obtener el id del organizador desde la sesión
$id_organizador = $_SESSION['usuario']['Id_usuario'];

// Procesar el formulario al enviar (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $nombre        = $_POST['nombre']        ?? '';
    $fecha         = $_POST['fecha']         ?? '';
    $descripcion   = $_POST['descripcion']   ?? '';
    // El estado siempre es 'pendiente'
    $estado        = 'pendiente';
    // Usamos el id del organizador de la sesión
    $id_lugar      = $_POST['id_lugar']      ?? 0;
    $id_cat_evento = $_POST['id_cat_evento'] ?? 0;
    
    // Inserción en la tabla 'evento'
    $sqlInsert = "
        INSERT INTO evento 
            (Nombre, Fecha, Descripcion, Estado, Id_organizador, Id_lugar, Id_cat_evento)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    // Preparar la consulta
    $stmt = $conexion->prepare($sqlInsert);
    // 'ssssiii' = 4 strings y 3 enteros
    $stmt->bind_param(
        "ssssiii",
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
        // Redirigir al listado de eventos del organizador
        header("Location: organizador.php");
        exit;
    } else {
        echo "Error al agregar el evento: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Evento</title>
    <!-- Bootstrap CSS (si no lo incluyes en organizador_header.php) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h2>Crear Evento</h2>
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

        <!-- El estado se fija automáticamente a 'pendiente' sin mostrar campo al usuario -->
        <input type="hidden" name="estado" value="pendiente">

        <!-- Selección de Lugar -->
        <div class="mb-3">
            <label for="id_lugar" class="form-label">Lugar</label>
            <select class="form-select" id="id_lugar" name="id_lugar" required>
                <option value="" disabled selected>-- Seleccione un Lugar --</option>
                <?php while($lug = $resultLugar->fetch_assoc()): ?>
                    <option value="<?= $lug['id_lugar'] ?>">
                        <?= $lug['Nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Selección de Categoría de Evento -->
        <div class="mb-3">
            <label for="id_cat_evento" class="form-label">Categoría</label>
            <select class="form-select" id="id_cat_evento" name="id_cat_evento" required>
                <option value="" disabled selected>-- Seleccione una Categoría --</option>
                <?php while($cat = $resultCategoria->fetch_assoc()): ?>
                    <option value="<?= $cat['Id_categoria_evento'] ?>">
                        <?= $cat['Categoria'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Evento</button>
        <a href="organizador.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
