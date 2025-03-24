<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 1) {
    header("Location: login.php"); // Redirigir si no es administrador
    exit;
}

include 'modelo/conexion_bd.php'; // Incluir la conexión a la base de datos

// Verificar si se ha proporcionado un ID de evento
if (isset($_GET['id'])) {
    $id_evento = $_GET['id'];

    // Preparar la consulta para eliminar el evento
    $sqlDelete = "DELETE FROM evento WHERE Id_evento = ?";
    $stmt = $conexion->prepare($sqlDelete);
    $stmt->bind_param("i", $id_evento);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir con un mensaje de éxito
        header("Location: admin_eventos.php?success=1");
        exit;
    } else {
        // Redirigir con un mensaje de error
        header("Location: admin_eventos.php?error=1");
        exit;
    }
} else {
    // Si no se proporciona un ID, redirigir
    header("Location: admin_eventos.php");
    exit;
}
?>