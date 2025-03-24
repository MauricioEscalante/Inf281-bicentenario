<?php
include 'admin_header.php';  // Header especial para el panel de admin
include 'modelo/conexion_bd.php'; // Conexión a la BD
// EJEMPLO B: Datos desde BD (usuarios por mes, por ejemplo)
$sqlUsuariosMes = "SELECT MONTH(fecha_registro) AS mes, COUNT(*) AS total
                   FROM usuario
                   GROUP BY MONTH(fecha_registro)
                   ORDER BY MONTH(fecha_registro)";
$resultMes = $conexion->query($sqlUsuariosMes);

$meses = [];
$totales = [];
while ($row = $resultMes->fetch_assoc()) {
    // Convertimos el número de mes a un texto, p.ej. 1 -> "Enero"
    $numMes = (int) $row['mes'];
    $nombresMes = ["", "Enero","Febrero","Marzo","Abril","Mayo","Junio",
                   "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
    $meses[] = $nombresMes[$numMes];
    $totales[] = $row['total'];
}

// 1) Total de usuarios
$sqlTotalUsuarios = "SELECT COUNT(*) AS total FROM usuario";
$resultUsuarios = $conexion->query($sqlTotalUsuarios);
$totalUsuarios = 0;
if ($row = $resultUsuarios->fetch_assoc()) {
    $totalUsuarios = $row['total'];
}

// 2) Total de eventos
$sqlTotalEventos = "SELECT COUNT(*) AS total FROM evento";
$resultEventos = $conexion->query($sqlTotalEventos);
$totalEventos = 0;
if ($row = $resultEventos->fetch_assoc()) {
    $totalEventos = $row['total'];
}

// 3) Eventos pendientes de aprobación
$sqlEventosPend = "SELECT COUNT(*) AS total FROM evento WHERE estado = 'pendiente'";
$resultPend = $conexion->query($sqlEventosPend);
$totalPendientes = 0;
if ($row = $resultPend->fetch_assoc()) {
    $totalPendientes = $row['total'];
}
// 4) Cantidad de compradores (rol = 2), por ejemplo
$sqlCompradores = "SELECT COUNT(*) AS total FROM usuario WHERE Rol = 2";
$resultComp = $conexion->query($sqlCompradores);
$totalCompradores = 0;
if ($row = $resultComp->fetch_assoc()) {
    $totalCompradores = $row['total'];
}

// 5) Cantidad de organizadores (rol = 3)
$sqlOrganizadores = "SELECT COUNT(*) AS total FROM usuario WHERE Rol = 3";
$resultOrg = $conexion->query($sqlOrganizadores);
$totalOrganizadores = 0;
if ($row = $resultOrg->fetch_assoc()) {
    $totalOrganizadores = $row['total'];
}

// 6) Cantidad de vendedores (rol = 4), por ejemplo
$sqlVendedores = "SELECT COUNT(*) AS total FROM usuario WHERE Rol = 4";
$resultVend = $conexion->query($sqlVendedores);
$totalVendedores = 0;
if ($row = $resultVend->fetch_assoc()) {
    $totalVendedores = $row['total'];
}

?>
<div class="container my-5">
    <h2>Dashboard de Administración</h2>
    <div class="row">
        <!-- Tarjeta: Usuarios -->
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Usuarios Registrados</h5>
                    <p class="card-text fs-4"><?= $totalUsuarios ?></p>
                </div>
            </div>
        </div>
        <!-- Tarjeta: Eventos -->
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Eventos Totales</h5>
                    <p class="card-text fs-4"><?= $totalEventos ?></p>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Pendientes -->
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Eventos Pendientes</h5>
                    <p class="card-text fs-4"><?= $totalPendientes ?></p>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Organizadores -->
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Organizadores</h5>
                    <p class="card-text fs-4"><?= $totalOrganizadores ?></p>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Vendedores -->
        <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Vendedores</h5>
                    <p class="card-text fs-4"><?= $totalVendedores?></p>
                </div>
            </div>
        </div>
        <!-- Tarjeta: Compradores -->
    <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Compradores</h5>
                    <p class="card-text fs-4"><?= $totalCompradores ?></p>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Aquí puedes seguir agregando más tarjetas, o una gráfica, o un calendario... -->
    
    <!-- 📊 Gráfica de usuarios -->
<h3>Usuarios registrados por mes</h3>
    <canvas id="myChart" width="700" height="200"></canvas>
</div>
<!-- 📌 Agregar la librería de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- 📌 Script para dibujar la gráfica -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($meses) ?>,
            datasets: [{
                label: 'Usuarios registrados',
                data: <?= json_encode($totales) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
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
});
</script>
</div>
<?php
include 'footer.php'; 
?>