<?php
include("../modelo/conexion_bd.php");
//include '../header1.php'; 
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "No se especificó un ID de usuario.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $rol    = $_POST["rol"];
    
    // Preparar la consulta de actualización
    $sql  = "UPDATE usuario SET Nombre = ?, Apellido = ?, Correo = ?, Rol = ? WHERE Id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssii", $nombre, $apellido, $correo, $rol, $id);
    
    if ($stmt->execute()) {
        header("Location: ../administrador.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    // Obtener los datos del usuario para mostrar en el formulario
    $sql  = "SELECT * FROM usuario WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result  = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if (!$usuario) {
        echo "Usuario no encontrado.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons (opcional) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Estilos personalizados (opcional) -->
  <style>
    /* Imagen de fondo */
    body {
      background: url('../img/bicen2.jpg') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh; /* Para que cubra toda la ventana */
    }
  </style>
</head>
<body>

<div class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Tarjeta (card) para el formulario -->
            <div class="card shadow-lg">
                <div class="card-body p-4">
                    <h1 class="mb-4 text-center">Editar Usuario</h1>

                    <form method="POST" action="">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="nombre" 
                                name="nombre" 
                                value="<?= htmlspecialchars($usuario['Nombre']) ?>" 
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido:</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="apellido" 
                                name="apellido" 
                                value="<?= htmlspecialchars($usuario['Apellido']) ?>" 
                                required
                            >
                        </div>

                        <!-- Correo -->
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo:</label>
                            <input 
                                type="email" 
                                class="form-control" 
                                id="correo" 
                                name="correo" 
                                value="<?= htmlspecialchars($usuario['Correo']) ?>" 
                                required
                            >
                        </div>

                        <!-- Rol -->
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol:</label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="1" <?= ($usuario['Rol'] == 1) ? 'selected' : ''; ?>>Administrador</option>
                                <option value="2" <?= ($usuario['Rol'] == 2) ? 'selected' : ''; ?>>Comprador</option>
                                <option value="3" <?= ($usuario['Rol'] == 3) ? 'selected' : ''; ?>>Organizador</option>
                                <option value="4" <?= ($usuario['Rol'] == 4) ? 'selected' : ''; ?>>Vendedor</option>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                            <a href="../administrador.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div> <!-- col-md-6 -->
    </div> <!-- row -->
</div> <!-- container -->

<?php include '../footer.php';?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>