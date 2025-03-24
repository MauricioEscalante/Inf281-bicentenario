<?php
// Asegúrate de tener Dompdf instalado con Composer: composer require dompdf/dompdf
require 'vendor/autoload.php';  // Cargar Dompdf
require 'modelo/conexion_bd.php';  // Ajusta la ruta si tu archivo de conexión está en otro lugar

use Dompdf\Dompdf;
use Dompdf\Options;

// Configuración de Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');

// Habilitar carga de archivos remotos (si tu imagen está en una ruta HTTP)
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Verificar la conexión a la BD (opcional pero recomendado)
if (!$conexion) {
    die('Error de conexión a la BD: ' . mysqli_connect_error());
}

// Consulta a la base de datos
$query = "SELECT Id_usuario, Nombre, Apellido, Correo, Rol FROM usuario";
$result = mysqli_query($conexion, $query);

// Verificar si la consulta falla (opcional pero recomendado)
if (!$result) {
    die('Error en la consulta: ' . mysqli_error($conexion));
}

// Construimos el HTML
// Ajusta la ruta del logo, el nombre del sistema y los estilos según tus preferencias
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header .logo {
            width: 100px; /* Ajusta el tamaño de tu logo */
        }
        .nombre-sistema {
            text-align: right;
            font-family: "Georgia", serif;  /* Cambia aquí la fuente */
            font-size: 28px;               /* Cambia aquí el tamaño de letra */
        }
        h2, h3 {
            margin: 0;
            padding: 0;
        }
        .titulo-reporte {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }
    </style>
</head>
<body>

<header>
    <div>
        <!-- Ajusta la ruta a tu logo -->
        <img src="http://localhost/bicen/img/logo.png" alt="Logo" class="logo">
    </div>
    <div class="nombre-sistema">
        <h2>BOL 200</h2>
    </div>
</header>

<hr>

<div class="titulo-reporte">
    <h3>Reporte de Usuarios</h3>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Correo</th>
        <th>Rol</th>
    </tr>';

// Recorremos los resultados de la consulta
while ($row = mysqli_fetch_assoc($result)) {
    // Convertir el número de rol a texto
    $rolTexto = '';
    switch ($row['Rol']) {
        case 1:
            $rolTexto = 'Administrador';
            break;
        case 2:
            $rolTexto = 'Comprador';
            break;
        case 3:
            $rolTexto = 'Organizador';
            break;
        case 4:
            $rolTexto = 'Vendedor';
            break;
        default:
            $rolTexto = 'Desconocido';
            break;
    }

    // Agregar fila a la tabla
    $html .= '
    <tr>
        <td>' . $row['Id_usuario'] . '</td>
        <td>' . htmlspecialchars($row['Nombre']) . '</td>
        <td>' . htmlspecialchars($row['Apellido']) . '</td>
        <td>' . htmlspecialchars($row['Correo']) . '</td>
        <td>' . htmlspecialchars($rolTexto) . '</td>
    </tr>';
}

$html .= '
</table>

<footer>
    <p>Reporte generado el ' . date('Y-m-d H:i:s') . '</p>
</footer>

</body>
</html>';

// Cargamos el HTML en Dompdf
$dompdf->loadHtml($html);

// Definimos tamaño y orientación de página
$dompdf->setPaper('A4', 'portrait');

// Renderizamos el PDF
$dompdf->render();

// Mostramos el PDF en el navegador
// Para forzar descarga, cambia 'Attachment' => true
$dompdf->stream('Reporte_Usuarios.pdf', ['Attachment' => false]);
?>
