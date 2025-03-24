<?php
session_start();

// Verificar que el usuario sea Organizador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 3) {
    header("Location: otra_interface.php");
    exit;
}

include 'organizador_header.php';
include 'modelo/conexion_bd.php';

// Obtener el ID del organizador desde la sesión
$id_organizador = $_SESSION['usuario']['Id_usuario'];

// Obtener la información actual del organizador
$sql = "SELECT * FROM usuario WHERE Id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_organizador);
$stmt->execute();
$result = $stmt->get_result();
$organizador = $result->fetch_assoc();

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $foto_perfil = $_FILES['foto_perfil'];

    // Si se envió el formulario para borrar la foto
    if (isset($_POST['borrar_foto'])) {
        // Eliminar la foto de perfil actual si existe
        if (!empty($organizador['Foto_perfil']) && file_exists($organizador['Foto_perfil'])) {
            unlink($organizador['Foto_perfil']); // Borrar el archivo
        }
        // Actualizar la base de datos para eliminar la ruta de la foto
        $sqlFoto = "UPDATE usuario SET Foto_perfil = NULL WHERE Id_usuario = ?";
        $stmtFoto = $conexion->prepare($sqlFoto);
        $stmtFoto->bind_param("i", $id_organizador);
        $stmtFoto->execute();

        header("Location: organizador_perfil.php?success=1");
        exit;
    }

    // Actualizar la información del organizador
    $sqlUpdate = "UPDATE usuario SET Nombre = ?, Correo = ? WHERE Id_usuario = ?";
    $stmtUpdate = $conexion->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssi", $nombre, $email, $id_organizador);

    if ($stmtUpdate->execute()) {
        // Subir la foto de perfil si se proporcionó
        if ($foto_perfil['error'] === UPLOAD_ERR_OK) {
            $ruta_destino = 'img/perfiles/' . basename($foto_perfil['name']);
            if (move_uploaded_file($foto_perfil['tmp_name'], $ruta_destino)) {
                // Actualizar la ruta de la foto de perfil en la base de datos
                $sqlFoto = "UPDATE usuario SET Foto_perfil = ? WHERE id_usuario = ?";
                $stmtFoto = $conexion->prepare($sqlFoto);
                $stmtFoto->bind_param("si", $ruta_destino, $id_organizador);
                $stmtFoto->execute();
            }
        }

        header("Location: organizador_perfil.php?success=1");
        exit;
    } else {
        $error = "Error al actualizar el perfil.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h2>Mi Perfil</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Perfil actualizado correctamente.
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Mostrar la foto de perfil y los datos -->
    <div class="row mb-4">
        <div class="col-md-4">
            <?php if (!empty($organizador['Foto_perfil'])): ?>
                <img src="<?= $organizador['Foto_perfil'] ?>" alt="Foto de perfil" class="img-thumbnail" style="max-width: 200px;">
                <form method="POST" action="" class="mt-2">
                    <button type="submit" name="borrar_foto" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Borrar Foto
                    </button>
                </form>
            <?php else: ?>
                <div class="alert alert-info">
                    No tienes una foto de perfil.
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <h4>Datos del Perfil</h4>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($organizador['Nombre']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($organizador['Correo']) ?></p>
        </div>
    </div>

    <!-- Formulario para actualizar el perfil -->
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($organizador['Nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($organizador['Correo']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="foto_perfil" class="form-label">Foto de Perfil</label>
            <input type="file" class="form-control" id="foto_perfil" name="foto_perfil">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>