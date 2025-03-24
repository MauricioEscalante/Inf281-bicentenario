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

// Consultas para obtener datos del organizador
$sqlTotalEventos = "SELECT COUNT(*) AS total FROM evento WHERE Id_organizador = ?";
$sqlEventosAprobados = "SELECT COUNT(*) AS aprobados FROM evento WHERE Id_organizador = ? AND Estado = 'Aprobado'";
$sqlEventosPendientes = "SELECT COUNT(*) AS pendientes FROM evento WHERE Id_organizador = ? AND Estado = 'Pendiente'";
$sqlEventosRechazados = "SELECT COUNT(*) AS rechazados FROM evento WHERE Id_organizador = ? AND Estado = 'Rechazado'";

// Ejecutar consultas
$stmtTotal = $conexion->prepare($sqlTotalEventos);
$stmtTotal->bind_param("i", $id_organizador);
$stmtTotal->execute();
$totalEventos = $stmtTotal->get_result()->fetch_assoc()['total'];

$stmtAprobados = $conexion->prepare($sqlEventosAprobados);
$stmtAprobados->bind_param("i", $id_organizador);
$stmtAprobados->execute();
$eventosAprobados = $stmtAprobados->get_result()->fetch_assoc()['aprobados'];

$stmtPendientes = $conexion->prepare($sqlEventosPendientes);
$stmtPendientes->bind_param("i", $id_organizador);
$stmtPendientes->execute();
$eventosPendientes = $stmtPendientes->get_result()->fetch_assoc()['pendientes'];

$stmtRechazados = $conexion->prepare($sqlEventosRechazados);
$stmtRechazados->bind_param("i", $id_organizador);
$stmtRechazados->execute();
$eventosRechazados = $stmtRechazados->get_result()->fetch_assoc()['rechazados'];

// Obtener notificaciones recientes relacionadas con los eventos del organizador
$sqlNotificaciones = "SELECT * FROM notificaciones WHERE id_usuario = ? ORDER BY fecha DESC LIMIT 5";
$stmtNotificaciones = $conexion->prepare($sqlNotificaciones);
$stmtNotificaciones->bind_param("i", $id_organizador);
$stmtNotificaciones->execute();
$notificaciones = $stmtNotificaciones->get_result();

// Obtener estadísticas de eventos por mes
$sqlEstadisticas = "SELECT DATE_FORMAT(Fecha, '%Y-%m') AS mes, COUNT(*) AS total 
                    FROM evento 
                    WHERE Id_organizador = ? 
                    GROUP BY mes 
                    ORDER BY mes DESC 
                    LIMIT 6";
$stmtEstadisticas = $conexion->prepare($sqlEstadisticas);
$stmtEstadisticas->bind_param("i", $id_organizador);
$stmtEstadisticas->execute();
$estadisticas = $stmtEstadisticas->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard del Organizador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Librería para gráficos (Chart.js) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container my-5">
    <h2>Dashboard del Organizador</h2>

    <!-- Resumen de eventos -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total de Eventos</h5>
                    <p class="card-text display-4"><?= $totalEventos ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Eventos Aprobados</h5>
                    <p class="card-text display-4"><?= $eventosAprobados ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Eventos Pendientes</h5>
                    <p class="card-text display-4"><?= $eventosPendientes ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Eventos Rechazados</h5>
                    <p class="card-text display-4"><?= $eventosRechazados ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de eventos por mes -->
    <div class="row mb-4">
        <div class="col">
            <h4>Actividad Reciente</h4>
            <canvas id="graficaEventos"></canvas>
        </div>
    </div>

    <!-- Notificaciones recientes -->
    <div class="row mb-4">
        <div class="col">
            <h4>Notificaciones Recientes</h4>
            <ul class="list-group">
                <?php while ($notificacion = $notificaciones->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <p><?= htmlspecialchars($notificacion['mensaje']) ?></p>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($notificacion['fecha'])) ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <!-- Mini calendario -->
    <div class="row">
        <div class="col">
            <h4>Próximos Eventos</h4>
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Scripts para FullCalendar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            events: [
                <?php
                // Obtener eventos aprobados para el calendario
                $sqlEventosCalendario = "SELECT Nombre, Fecha FROM evento WHERE Id_organizador = ? AND Estado = 'Aprobado'";
                $stmtCalendario = $conexion->prepare($sqlEventosCalendario);
                $stmtCalendario->bind_param("i", $id_organizador);
                $stmtCalendario->execute();
                $resultCalendario = $stmtCalendario->get_result();
                while ($evento = $resultCalendario->fetch_assoc()): ?>
                    {
                        title: '<?= $evento['Nombre'] ?>',
                        start: '<?= $evento['Fecha'] ?>'
                    },
                <?php endwhile; ?>
            ]
        });
        calendar.render();
    });
</script>

<!-- Script para la gráfica de eventos por mes -->
<script>
    const ctx = document.getElementById('graficaEventos').getContext('2d');
    const graficaEventos = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php
                $estadisticasArray = [];
                while ($estadistica = $estadisticas->fetch_assoc()) {
                    echo "'" . $estadistica['mes'] . "',";
                    $estadisticasArray[] = $estadistica['total'];
                }
                ?>
            ],
            datasets: [{
                label: 'Eventos por Mes',
                data: [<?= implode(',', $estadisticasArray) ?>],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>