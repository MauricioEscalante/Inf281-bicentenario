<?php
session_start();
include("modelo/conexion_bd.php");
require_once("php/mail.php");

if(isset($_POST['enviar'])) {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    
    // Verificar si el correo existe
    $query = "SELECT * FROM usuario WHERE Correo='$email'";
    $resultado = mysqli_query($conexion, $query);
    
    if(mysqli_num_rows($resultado) > 0) {
        // Generar token y establecer fecha de expiración (por ejemplo, 1 hora)
        $token = bin2hex(random_bytes(50));
        $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));
        
        // Actualizar el registro del usuario con el token de recuperación
        $queryUpdate = "UPDATE usuario SET token_recuperacion='$token', token_expira='$expira' WHERE Correo='$email'";
        mysqli_query($conexion, $queryUpdate);
        
        // Enviar correo con el enlace de recuperación
        require_once 'php/mail.php';  // O el archivo donde tengas la función para enviar correos
        $urlReset = "http://localhost/bicen/reset_password.php?token=" . $token;
        
        // Aquí podrías crear una función similar a enviarCorreoVerificacion para enviar el correo de recuperación
        $asunto = "Recuperación de contraseña";
        $mensaje = "
        <html>
        <head>
          <title>Recuperación de contraseña</title>
        </head>
        <body>
          <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
          <p><a href='{$urlReset}'>Restablecer contraseña</a></p>
          <p>Este enlace expirará en 1 hora.</p>
        </body>
        </html>";
        
        $envio = enviarCorreoRecuperacion($email, $mensaje, $asunto);
        
        if($envio === true) {
            $_SESSION['mensaje'] = array('tipo' => 'success', 'texto' => 'Se ha enviado un enlace de recuperación a tu correo.');
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'Error al enviar el correo de recuperación.');
        }
    } else {
        $_SESSION['mensaje'] = array('tipo' => 'warning', 'texto' => 'No se encontró el correo en nuestros registros.');
    }
    
    header("Location: recuperar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperar contraseña</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body {
      background: url('img/bicen1.jpeg') no-repeat center center fixed;
      background-size: cover;
    }
    .card {
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4" style="max-width: 400px; width: 100%;">
    <h2 class="text-center mb-4">Recuperar Contraseña</h2>
    
    <?php if(isset($_SESSION['mensaje'])): ?>
      <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?>">
        <?php 
          echo $_SESSION['mensaje']['texto']; 
          unset($_SESSION['mensaje']);
        ?>
      </div>
    <?php endif; ?>
    
    <form action="recuperar.php" method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input 
          type="email" 
          class="form-control" 
          name="email" 
          id="email" 
          placeholder="Ingresa tu correo" 
          required
        >
      </div>
      <div class="d-grid">
        <button type="submit" name="enviar" class="btn btn-primary">Enviar enlace de recuperación</button>
      </div>
    </form>
  </div>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
