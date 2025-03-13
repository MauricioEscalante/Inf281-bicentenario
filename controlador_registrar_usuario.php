<?php
session_start();

// Incluir archivo de envío de correo
require_once("php/mail.php");

if (isset($_POST['registro'])) {
    // Verificar que las contraseñas coincidan
    if ($_POST['contraseña'] != $_POST['rcontraseña']) {
        $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'Las contraseñas no coinciden');
        header('Location: registro.php');
        exit;
    }
    
    // Verificar el reCAPTCHA (si lo tienes configurado)
    if (isset($_POST['g-recaptcha-response'])) {
        $recaptcha = $_POST['g-recaptcha-response'];
        // Aquí puedes añadir la verificación del reCAPTCHA con Google
    }
    
    // Obtener los datos del formulario
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contraseña = mysqli_real_escape_string($conexion, $_POST['contraseña']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $genero = mysqli_real_escape_string($conexion, $_POST['genero']);
    $id_pais = mysqli_real_escape_string($conexion, $_POST['pais']);
    $id_ciudad = mysqli_real_escape_string($conexion, $_POST['ciudad']);
    $rol = mysqli_real_escape_string($conexion, $_POST['rol']);
    
    // Verificar si el correo ya existe
    $verificar_correo = mysqli_query($conexion, "SELECT * FROM usuario WHERE Correo = '$correo'");
    if (mysqli_num_rows($verificar_correo) > 0) {
        $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'Este correo ya está registrado');
        header('Location: registro.php');
        exit;
    }
    
    // Generar token de verificación
    $token = bin2hex(random_bytes(50)); // Genera un token aleatorio
    
    // Encriptar la contraseña
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
    
    // Insertar en la base de datos
    $query = "INSERT INTO usuario (Nombre, Apellido, Correo, Contraseña, Telefono, Genero, Id_pais, Id_ciudad, Rol, token_verificacion, verificado) 
              VALUES ('$nombre', '$apellido', '$correo', '$contraseña_hash', '$telefono', '$genero', '$id_pais', '$id_ciudad', '$rol', '$token', 0)";
    
    if (mysqli_query($conexion, $query)) {
        // Enviar correo de verificación
        $resultado_envio = enviarCorreoVerificacion($correo, $nombre . ' ' . $apellido, $token);
        
        if ($resultado_envio === true) {
            $_SESSION['mensaje'] = array('tipo' => 'success', 'texto' => 'Registro exitoso. Por favor, verifica tu correo electrónico para activar tu cuenta.');
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'warning', 'texto' => 'Registro exitoso, pero hubo un problema al enviar el correo de verificación. ' . $resultado_envio);
        }
        
        header('Location: validar.php');
        exit;
    } else {
        $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'Error al registrar usuario: ' . mysqli_error($conexion));
        header('Location: registro.php');
        exit;
    }
}
?>