<?php
include("modelo/conexion_bd.php");
//include '../header1.php'; 
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "No se especificó un ID de evento.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $evento = $_POST["evento"];
    $fecha = $_POST["fecha"];
    $lugar = $_POST["lugar"];
    $estado = $_POST["estado"];
    
    // Preparar la consulta de actualización
    $sql = "UPDATE evento SET Nombre = ?, Fecha = ?, Id_lugar = ?, Estado = ? WHERE Id_evento = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $evento, $fecha, $lugar, $estado, $id);
    
    if ($stmt->execute()) {
        header("Location: admin_eventos.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    // Obtener los datos del evento para mostrar en el formulario
    $sql = "SELECT * FROM evento WHERE Id_evento = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $evento = $result->fetch_assoc();

    if (!$evento) {
        echo "Evento no encontrado.";
        exit;
    }
    
    // Obtener información del lugar para mostrar el nombre en vez del ID
    $sqlLugar = "SELECT id_lugar, Nombre FROM lugar";
    $resultLugar = $conexion->query($sqlLugar);
    $lugares = [];
    while($row = $resultLugar->fetch_assoc()) {
        $lugares[$row['id_lugar']] = $row['Nombre'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Evento</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons (opcional) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Estilos personalizados (opcional) -->
  <style>
    /* Imagen de fondo */
    body {
      background: url('img/bicen2.jpg') no-repeat center center fixed;
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
                    <h1 class="mb-4 text-center">Editar Evento</h1>

                    <form method="POST" action="">
                        <!-- evento -->
                        <div class="mb-3">
                            <label for="evento" class="form-label">Nombre del Evento:</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="evento" 
                                name="evento" 
                                value="<?= htmlspecialchars($evento['Nombre']) ?>" 
                                required
                            >
                        </div>
                        
                        <!-- Fecha -->
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha:</label>
                            <input 
                                type="date" 
                                class="form-control" 
                                id="fecha" 
                                name="fecha" 
                                value="<?= htmlspecialchars($evento['Fecha']) ?>" 
                                required
                            >
                        </div>

                        <!-- Lugar -->
                        <div class="mb-3">
                            <label for="lugar" class="form-label">Lugar:</label>
                            <select class="form-select" id="lugar" name="lugar" required>
                                <?php foreach ($lugares as $id_lugar => $nombre_lugar): ?>
                                    <option value="<?= $id_lugar ?>" <?= ($evento['Id_lugar'] == $id_lugar) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($nombre_lugar) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Estado -->
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado:</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="pendiente" <?= ($evento['Estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="aprobado" <?= ($evento['Estado'] == 'aprobado') ? 'selected' : ''; ?>>Aprobado</option>
                                <option value="rechazado" <?= ($evento['Estado'] == 'rechazado') ? 'selected' : ''; ?>>Rechazado</option>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualizar Evento</button>
                            <a href="admin_eventos.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div> <!-- col-md-6 -->
    </div> <!-- row -->
</div> <!-- container -->

<?php include 'footer.php';?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>