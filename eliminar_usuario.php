<?php
// eliminar_usuario.php
include("../modelo/conexion_bd.php");
$id = $_GET['id'];

// Preparar la consulta de eliminación
$sql  = "DELETE FROM usuario WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: administrador.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
?>
