<?php
// 1. Incluye tu header especial de Admin (verifica rol, etc.)
include 'admin_header.php';
// 2. Incluye tu archivo de conexión
include 'modelo/conexion_bd.php';
// 3. Obtener listas desde la BD para llenar los <select>
// a) Lugares
$sqlLugar = "SELECT id_lugar, Nombre FROM lugar";
$resultLugar = $conexion->query($sqlLugar);
// b) Categorías de evento
$sqlCategoria = "SELECT Id_categoria_evento, Categoria FROM categoria_evento";
$resultCategoria = $conexion->query($sqlCategoria);
// c) Organizadores (por ejemplo, Rol=3)
$sqlOrganizadores = "SELECT id_usuario, Nombre FROM usuario WHERE Rol = 3";
$resultOrganizadores = $conexion->query($sqlOrganizadores);
// d) Administradores (por ejemplo, Rol=1)
$sqlAdmins = "SELECT id_usuario, Nombre FROM usuario WHERE Rol = 1";
$resultAdmins = $conexion->query($sqlAdmins);
// 4. Procesar el formulario al enviar (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos
    $nombre           = $_POST['nombre']           ?? '';
    $fecha            = $_POST['fecha']            ?? '';
    $descripcion      = $_POST['descripcion']      ?? '';
    $estado           = $_POST['estado']           ?? 'pendiente';
    $id_organizador   = $_POST['id_organizador']   ?? 0;
    $id_lugar         = $_POST['id_lugar']         ?? 0;
    $id_cat_evento    = $_POST['id_cat_evento']    ?? 0;
    $id_administrador = $_POST['id_administrador'] ?? 0;

    // 5. Inserción en la tabla 'evento'
    // Omitimos Id_evento porque es autoincrement
    $sqlInsert = "
        INSERT INTO evento 
            (Nombre, Fecha, Descripcion, Estado, Id_organizador, Id_lugar, Id_cat_evento, Id_administrador)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";

    // Preparamos la consulta
    $stmt = $conexion->prepare($sqlInsert);
    // 'ssss' = 4 strings, 'iiii' = 4 enteros
    $stmt->bind_param(
        "ssssiiii",
        $nombre,
        $fecha,
        $descripcion,
        $estado,
        $id_organizador,
        $id_lugar,
        $id_cat_evento,
        $id_administrador
    );

    // Ejecutamos y verificamos
    if ($stmt->execute()) {
        // Redirigir a la lista de eventos
        header("Location: admin_eventos.php");
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
    <title>Agregar Evento</title>
    <!-- Bootstrap CSS (si no lo tienes ya en tu admin_header.php) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container my-5">
    <h2>Agregar Evento</h2>

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
            <textarea 
                class="form-control" 
                id="descripcion" 
                name="descripcion" 
                rows="4" 
                required
            ></textarea>
        </div>

        <!-- Estado -->
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado">
                <option value="pendiente" selected>Pendiente</option>
                <option value="aprobado">Aprobado</option>
                <option value="rechazado">Rechazado</option>
            </select>
        </div>

        <!-- Organizador -->
        <div class="mb-3">
            <label for="id_organizador" class="form-label">Organizador</label>
            <select class="form-select" id="id_organizador" name="id_organizador" required>
                <option value="" disabled selected>-- Seleccione un Organizador --</option>
                <?php while($org = $resultOrganizadores->fetch_assoc()): ?>
                    <option value="<?= $org['id_usuario'] ?>">
                        <?= $org['Nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Lugar -->
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

        <!-- Categoría de evento -->
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


        <!-- Administrador -->
        <div class="mb-3">
            <label for="id_administrador" class="form-label">Administrador</label>
            <select class="form-select" id="id_administrador" name="id_administrador" required>
                <option value="" disabled selected>-- Seleccione un Administrador --</option>
                <?php while($adm = $resultAdmins->fetch_assoc()): ?>
                    <option value="<?= $adm['id_usuario'] ?>">
                        <?= $adm['Nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Evento</button>
        <a href="admin_eventos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
