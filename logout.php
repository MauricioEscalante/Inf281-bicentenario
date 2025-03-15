<?php
session_start();      // Inicia o retoma la sesión
session_destroy();    // Destruye todos los datos de la sesión
header("Location: index.php"); // Redirige al login
exit;                 // Asegura que el script termine aquí
?>
