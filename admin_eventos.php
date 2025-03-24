<?php
$title = "Gestionar Eventos";
include 'admin_header.php';  // Header especial para el panel de admin
include 'modelo/conexion_bd.php'; // Conexión a la BD

// Obtener eventos desde la base de datos
$sqlEventos = "
    SELECT e.Id_evento,
           e.Nombre,
           e.Fecha,
           e.Estado,
           l.Nombre AS nombre_lugar,
           u.Nombre AS nombre_organizador,
           c.Categoria AS nombre_categoria,
           ua.Nombre AS nombre_administrador
    FROM evento e
    JOIN lugar l ON e.Id_lugar = l.id_lugar
    JOIN usuario u ON e.Id_organizador = u.id_usuario
    JOIN categoria_evento c ON e.id_cat_evento = c.id_categoria_evento
    JOIN usuario ua ON e.Id_administrador = ua.Id_usuario
    WHERE e.Estado = 'Aprobado' OR e.Estado = 'Rechazado'
";

$resultEventos = $conexion->query($sqlEventos);
?>

<div class="container my-5">
    <h2>Gestión de Eventos</h2>
    <a href="agregar_evento.php" class="btn btn-success mb-3">Agregar Evento</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Evento</th>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Organizador</th>
                <th>Administrador</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($evento = $resultEventos->fetch_assoc()): ?>
            <tr>
                    <td><?= $evento['Id_evento'] ?></td>
                    <td><?= $evento['Nombre'] ?></td>
                    <td><?= $evento['Fecha'] ?></td>
                    <td><?= $evento['nombre_lugar'] ?></td>
                    <td><?= $evento['nombre_organizador'] ?></td>
                    <td><?= $evento['nombre_administrador'] ?></td>
                    <td><?= $evento['nombre_categoria'] ?></td>
                    <td><?= ucfirst($evento['Estado']) ?></td>
                <td>
                    <a href="editar_evento.php?id=<?= $evento['Id_evento'] ?>" class="btn btn-primary btn-sm">Editar</a>
                    <a href="eliminar_evento.php?id=<?= $evento['Id_evento'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este evento?');">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
