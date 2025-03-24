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

// Consulta para obtener las solicitudes de eventos del organizador
$sql = "SELECT * FROM evento WHERE Id_organizador = ? AND (Estado = 'Aprobado' OR Estado = 'Rechazado')";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_organizador);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-5">
    <h2>Mis Solicitudes de Eventos</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['Id_evento'] ?></td>
                    <td><?= $row['Nombre'] ?></td>
                    <td><?= $row['Fecha'] ?></td>
                    <td><?= $row['Descripcion'] ?></td>
                    <td>
                        <?php if ($row['Estado'] == 'Aprobado'): ?>
                            <span class="badge bg-success">Aprobado</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rechazado</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>