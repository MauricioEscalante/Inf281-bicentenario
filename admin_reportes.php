<?php
session_start();
include 'modelo/conexion_bd.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conexion->set_charset("utf8");

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 1) {
    header("Location: otra1_interface.php");
    exit;
}
// Cargar Dompdf
require_once 'php/vendor/autoload.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;  
// Si se envía el formulario para generar el reporte
if (isset($_POST['generar'])) {
    // Recoger filtros enviados
    $fechaInicio = $_POST['fecha_inicio'] ?? '';
    $fechaFin    = $_POST['fecha_fin'] ?? '';
    $categoria   = $_POST['categoria'] ?? '';
    $estado      = $_POST['estado'] ?? '';

    // Consulta base con filtros dinámicos
            $sql = "SELECT 
            e.Id_evento, 
            e.Nombre, 
            e.Fecha, 
            e.Descripcion, 
            e.Estado, 
            l.Nombre AS Lugar, 
            c.Categoria, 
            u.Nombre AS Organizador
        FROM evento e
        JOIN lugar l ON e.Id_lugar = l.Id_lugar
        JOIN categoria_evento c ON e.Id_cat_evento = c.Id_categoria_evento
        JOIN usuario u ON e.Id_organizador = u.Id_usuario WHERE 1=1";

    // Agregar filtro de rango de fechas
    if (!empty($fechaInicio) && !empty($fechaFin)) {
        $sql .= " AND e.Fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
    } elseif (!empty($fechaInicio)) {
        $sql .= " AND e.Fecha >= '$fechaInicio'";
    } elseif (!empty($fechaFin)) {
        $sql .= " AND e.Fecha <= '$fechaFin'";
    }

    // Agregar filtro por categoría (si se seleccionó)
    if (!empty($categoria)) {
        $sql .= " AND c.Categoria = '$categoria'";
    }

    // Agregar filtro por estado (si se seleccionó)
    if (!empty($estado)) {
        $sql .= " AND e.Estado = '$estado'";
    }

    $sql .= " ORDER BY e.Fecha ASC";

    $resultado = $conexion->query($sql);
// Construir contenido HTML para el PDF
    // Puedes personalizar estilos y estructura según tus necesidades
    $html = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de Eventos</title>
        <style>
    @page {
      margin: 80px 50px; /* Deja espacio para header y footer */
    }
    body {
      font-family: DejaVu Sans, sans-serif;
      margin: 0;
      padding: 0;
    }
    /* Encabezado */
    .header {
      top: 0;
      left: 0;
      right: 0;
      height: 90px;
      display: flex;
      align-items: center;
      padding: 0 10px;
      background-color: #f2f2f2;
      border-bottom: 1px solid #ccc;
    }
    .header .logo {
      height: 90px;
      margin-right: 15px;
    }
    .header .titulo-sistema {
      font-size: 35px;
      font-weight: bold;
    }

    /* Footer */
    .footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 30px;
      text-align: center;
      font-size: 12px;
      color: #555;
      border-top: 1px solid #ccc;
      line-height: 30px;
    }

    /* Contenido principal */
    .content {
      margin: 80px 20px 40px 20px; /* Deja espacio para header y footer */
    }

    h1 {
      text-align: center;
      margin-top: 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
  </style>
    </head>
    <body>
    <div class="header">
    <img src="http://localhost/bicen/img/logo.png" alt="Logo" class="logo" />
        <div class="titulo-sistema">BOL 200</div>
    </div>
    <!-- Footer con fecha de generación -->
  <div class="footer">
    Reporte generado el '.date("Y-m-d").'
  </div>

  <div class="content">
        <h1>Reporte de Eventos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Evento</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Lugar</th>
                    <th>Categoría</th>
                    <th>Organizador</th>
                </tr>
            </thead>
            <tbody>';

    // Llenar la tabla con los resultados
    while ($fila = $resultado->fetch_assoc()) {
        $html .= '<tr>
            <td>'. $fila['Id_evento'] .'</td>
            <td>'. $fila['Nombre'] .'</td>
            <td>'. $fila['Fecha'] .'</td>
            <td>'. $fila['Descripcion'] .'</td>
            <td>'. $fila['Estado'] .'</td>
            <td>'. $fila['Lugar'] .'</td>
            <td>'. $fila['Categoria'] .'</td>
            <td>'. $fila['Organizador'] .'</td>
        </tr>';
    }

    $html .= '
            </tbody>
        </table>
        </div>
    </body>
    </html>';

    // Configurar Dompdf
    $options = new Options();
    // Habilitar interpretación de HTML5 y soporte de caracteres
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans'); 
    // Si quieres, puedes habilitar contenido remoto (CSS externos, imágenes, etc.)
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    // Cargar el HTML
    $dompdf->loadHtml($html, 'UTF-8');
    // Definir tamaño y orientación de la hoja
    $dompdf->setPaper('A4', 'landscape');
    // Renderizar
    $dompdf->render();

    // Mostrar en el navegador (vista previa)
    // "Attachment" => false significa que NO forza la descarga inmediata
    $dompdf->stream("reportes_eventos.pdf", array("Attachment" => false));
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Reporte de Eventos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('../img/bicen1.jpeg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .form-container {
            margin-top: 50px;
            background: rgba(255,255,255,0.9);
            padding: 20px;
            border-radius: 8px;
        }
    </style>
<div class="container form-container">
    <h1 class="mb-4">Generar Reporte de Eventos</h1>
    <p>Configure los filtros deseados y haga clic en "Generar Reporte PDF" para tener una vista previa del reporte.</p>
    <form method="post" action="">
        <!-- Filtro de rango de fechas -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
            </div>
            <div class="col-md-6">
                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
            </div>
        </div>

        <!-- Filtro por categoría -->
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría del Evento</label>
            <select class="form-select" id="categoria" name="categoria">
                <option value="" selected>Todos</option>
                <option value="Academico">Academico</option>
                <option value="Cultural">Cultural</option>
                <option value="Gastronomico">Gastronomico</option>
                <option value="Deportivo">Deportivo</option>
                <!-- Agrega más opciones según tus categorías -->
            </select>
        </div>

        <!-- Filtro por estado -->
        <div class="mb-3">
            <label for="estado" class="form-label">Estado del Evento</label>
            <select class="form-select" id="estado" name="estado">
                <option value="" selected>Todos</option>
                <option value="Aprobado">Aprobado</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Rechazado">Rechazado</option>
                <!-- Agrega más opciones según los estados que manejes -->
            </select>
        </div>

        <div class="d-grid">
           <!-- Botón para generar el reporte PDF -->
           <button type="submit" name="generar" class="btn btn-primary">Generar Reporte PDF</button>
        </div>
    </form>
    <div class="mt-3">
        <a href="admin_dashboard.php" class="btn btn-secondary">Volver al Panel de Administración</a>
    </div>
</div>
<?php include ("footer.php")?>

