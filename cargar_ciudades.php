<?php
header("Content-Type: application/json"); // Asegurar que devuelve JSON
include("modelo/conexion_bd.php");
if (isset($_GET['Id_pais'])&& is_numeric($_GET['Id_pais'])) {
$pais_id = intval($_GET['Id_pais']);


//$result = $conexion->query("SELECT * FROM ciudad WHERE Id_pais= $pais_id");
// Verificar si hay ciudades disponibles para el país seleccionado
$stmt = $conexion->prepare("SELECT Id_ciudad, nombre FROM ciudad WHERE Id_pais = ?");
$stmt->bind_param("i", $pais_id);
$stmt->execute();
$result = $stmt->get_result();


$ciudades = [];

while ($ciudad = $result->fetch_assoc()) {
    $ciudades[] = $ciudad;
}

echo json_encode($ciudades);
} else {
    echo json_encode([]); // Devuelve un array vacío si no se recibe el ID del país
}
?>