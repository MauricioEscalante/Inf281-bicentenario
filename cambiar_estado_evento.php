<?php
include '../modelo/conexion_bd.php';
// Se espera recibir el ID del evento y el nuevo estado vía GET:
$id_evento = $_GET['id_evento'];
$nuevo_estado = $_GET['estado']; // 'aprobado' o 'rechazado'

// Actualizar el estado del evento
$sqlUpdate = "UPDATE evento SET Estado = ? WHERE Id_evento = ?";
$stmt = $conexion->prepare($sqlUpdate);
$stmt->bind_param("si", $nuevo_estado, $id_evento);

if($stmt->execute()){
    // Obtener el ID del organizador y el nombre del evento
    $sqlEvento = "SELECT Id_organizador, Nombre FROM evento WHERE Id_evento = ?";
    $stmtEvento = $conexion->prepare($sqlEvento);
    $stmtEvento->bind_param("i", $id_evento);
    $stmtEvento->execute();
    $resultEvento = $stmtEvento->get_result();
    
    if($rowEvento = $resultEvento->fetch_assoc()){
        $id_organizador = $rowEvento['Id_organizador'];
        $nombre_evento = $rowEvento['Nombre'];
        
        // Definir el mensaje según el nuevo estado
        if($nuevo_estado == 'Aprobado'){
            $mensaje = "Su evento '$nombre_evento' ha sido aprobado.";
        } elseif($nuevo_estado == 'Rechazado'){
            $mensaje = "Su evento '$nombre_evento' ha sido rechazado.";
        } else {
            $mensaje = "El estado de su evento '$nombre_evento' ha cambiado.";
        }
        
        // Insertar la notificación
        $sqlNotif = "INSERT INTO notificaciones (id_usuario, mensaje, leido, fecha) 
                    VALUES (?, ?, 0, CURRENT_TIMESTAMP())";
        $stmtNotif = $conexion->prepare($sqlNotif);
        $stmtNotif->bind_param("is", $id_organizador, $mensaje);
        
        if($stmtNotif->execute()){
            // Notificación creada exitosamente
            header("Location: admin_eventos.php?success=1");
        } else {
            // Error al crear la notificación
            header("Location: admin_eventos.php?error=notif&msg=" . $stmtNotif->error);
        }
    } else {
        header("Location: admin_eventos.php?error=event_not_found");
    }
    exit;
} else {
    echo "Error al actualizar el estado: " . $stmt->error;
}
?>