<?php
session_start();

// Verificar que el usuario sea Organizador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] != 3) {
    header("Location: otra_interface.php");
    exit;
}

include 'modelo/conexion_bd.php'; // Incluir la conexión a la base de datos

// Verificar si se ha proporcionado un ID de evento
if (!isset($_GET['id'])) {
    header("Location: organizador.php");
    exit;
}

$id_evento = $_GET['id'];
$id_organizador = $_SESSION['usuario']['Id_usuario'];

// Eliminar el evento
$sqlDelete = "DELETE FROM evento WHERE Id_evento = ? AND Id_organizador = ?";
$stmt = $conexion->prepare($sqlDelete);
$stmt->bind_param("ii", $id_evento, $id_organizador);

if ($stmt->execute()) {
    header("Location: organizador.php?success=1");
    exit;
} else {
    header("Location: organizador.php?error=1");
    exit;
}
?>