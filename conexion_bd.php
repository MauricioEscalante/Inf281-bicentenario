<?php
$servidor = "localhost"; // Cambia esto si es necesario
$usuario = "root";
$contraseña = "";
$base_datos = "bicentenario";
$conexion = new mysqli($servidor, $usuario, $contraseña, $base_datos);
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}else{
    //echo "Conexión exitosa a la base de datos";
}