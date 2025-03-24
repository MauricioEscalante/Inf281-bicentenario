<?php
session_start();
include 'modelo/conexion_bd.php';
$title = "Calendario";
include 'admin_header.php';

// Verificar rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 1) {
    header("Location: otra1_interface.php");
    exit;
}

// Ajustar el charset si es necesario
$conexion->set_charset("utf8");

// Consulta para obtener los eventos
$sql = "SELECT Id_evento, Nombre, Fecha, Descripcion, Estado FROM evento";
$resultado = $conexion->query($sql);

// Construir array de eventos para FullCalendar
$events = [];

while ($row = $resultado->fetch_assoc()) {
    // Determinar color según el estado
    $color = '#6c757d'; // Gris por defecto
    switch ($row['Estado']) {
        case 'Aprobado':
            $color = '#28a745'; // Verde
            break;
        case 'Pendiente':
            $color = '#ffc107'; // Amarillo
            break;
        case 'Rechazado':
            $color = '#dc3545'; // Rojo
            break;
    }

    // Estructura mínima para un evento en FullCalendar
    $events[] = [
        'id' => $row['Id_evento'],
        'title' => $row['Nombre'],
        'start' => $row['Fecha'],
        // Si tu evento dura varios días, añade 'end' => $row['Fecha_fin'] (ajusta tu BD)
        
        // Campos extra (propiedades personalizadas) para mostrar en el click
        'description' => $row['Descripcion'],
        'estado'      => $row['Estado'],

        // Colores
        'backgroundColor' => $color,
        'borderColor'     => $color,
        'textColor'       => '#ffffff' // Texto blanco para contraste
    ];
}

// Convertir el array de eventos a JSON
$eventsJson = json_encode($events);
?>
<!--
<!DOCTYPE html>
<html lang="es">
<head> 
  <meta charset="UTF-8">
  <title>Calendario de Eventos</title>
 
  -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  
  <style>
    body {
      margin: 20px;
      font-family: Arial, sans-serif;
    }
    #calendar {
      max-width: 900px;
      margin: 0 auto;
    }
    /* Estilos para el modal (opcional si usas Bootstrap) */
    .modal-content {
      padding: 20px;
    }
  </style>
<!--</head>-->
  <h1 class="text-center mb-4">Calendario de Eventos</h1>

  <!-- Contenedor del calendario -->
  <div id="calendar"></div>

  <!-- Modal para mostrar detalles (opcional si quieres usar Bootstrap) -->
  <div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="eventoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <h5 class="modal-title" id="eventoModalLabel">Detalles del Evento</h5>
        <div class="modal-body">
          <p><strong>Nombre:</strong> <span id="eventoNombre"></span></p>
          <p><strong>Fecha:</strong> <span id="eventoFecha"></span></p>
          <p><strong>Estado:</strong> <span id="eventoEstado"></span></p>
          <p><strong>Descripción:</strong></p>
          <p id="eventoDescripcion"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
<?php include 'footer.php'?>
 
  <!-- FullCalendar JS (CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <!-- Locale español (opcional, pero recomendado para la localización completa) -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      
      // Inicializa el calendario
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es', // Idioma español
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: <?php echo $eventsJson; ?>,

        // Manejar clic en un evento
        eventClick: function(info) {
          // Opcional 1: Simple alert
          // alert(
          //   'Evento: ' + info.event.title + 
          //   '\nEstado: ' + info.event.extendedProps.estado +
          //   '\nDescripción: ' + info.event.extendedProps.description
          // );

          // Opcional 2: Usar un modal de Bootstrap
          var evento = info.event;
          document.getElementById('eventoNombre').textContent = evento.title;
          document.getElementById('eventoFecha').textContent = evento.startStr; 
          document.getElementById('eventoEstado').textContent = evento.extendedProps.estado;
          document.getElementById('eventoDescripcion').textContent = evento.extendedProps.description;

          var modal = new bootstrap.Modal(document.getElementById('eventoModal'), {});
          modal.show();
        }
      });
      
      // Renderizar el calendario
      calendar.render();
    });
  </script>

