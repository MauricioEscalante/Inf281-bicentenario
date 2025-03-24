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

// Consulta para obtener los eventos del organizador
$sql = "SELECT * FROM evento WHERE Id_organizador = ? AND Estado = 'Aprobado'";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_organizador);
$stmt->execute();
$result = $stmt->get_result();

// Convertir los eventos a un formato compatible con FullCalendar
$eventos = [];
while ($row = $result->fetch_assoc()) {
    $eventos[] = [
        'title' => $row['Nombre'],
        'start' => $row['Fecha'],
        'description' => $row['Descripcion']
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario de Eventos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.min.js"></script>
</head>
<body>
<div class="container my-5">
    <h2>Calendario de Eventos</h2>
    <div id="calendar"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            events: <?= json_encode($eventos) ?>,
            eventClick: function(info) {
                alert('Evento: ' + info.event.title + '\nDescripción: ' + info.event.extendedProps.description);
            }
        });
        calendar.render();
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>