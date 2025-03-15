<?php
session_start();
include("modelo/conexion_bd.php");

// Verificar que se haya recibido un token válido por GET
if(isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "SELECT * FROM usuario WHERE token_recuperacion='$token' AND token_expira >= NOW()";
    $resultado = mysqli_query($conexion, $query);
    
    if(mysqli_num_rows($resultado) == 0) {
        $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'El enlace ha expirado o es inválido.');
        header("Location: recuperar.php");
        exit;
    }
} else {
    header("Location: recuperar.php");
    exit;
}

// Procesar el formulario de restablecimiento
if(isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $nueva_contraseña = mysqli_real_escape_string($conexion, $_POST['nueva_contraseña']);
    $confirmar_contraseña = mysqli_real_escape_string($conexion, $_POST['confirmar_contraseña']);
    
    if($nueva_contraseña !== $confirmar_contraseña) {
        $_SESSION['mensaje'] = array('tipo' => 'warning', 'texto' => 'Las contraseñas no coinciden.');
    } else {
        $password_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        // Actualizar la contraseña y limpiar el token
        $queryUpdate = "UPDATE usuario SET Contraseña='$password_hash', token_recuperacion=NULL, token_expira=NULL WHERE token_recuperacion='$token'";
        if(mysqli_query($conexion, $queryUpdate)) {
            $_SESSION['mensaje'] = array('tipo' => 'success', 'texto' => 'Tu contraseña se ha actualizado correctamente.');
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'Error al actualizar la contraseña: ' . mysqli_error($conexion));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer Contraseña</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Font Awesome para los íconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    body {
      background: url('img/bicen1.jpeg') no-repeat center center fixed;
      background-size: cover;
    }
    .card-translucent {
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="d-flex flex-column">
  <main class="flex-fill">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
      <div class="card card-translucent p-4" style="max-width: 400px; width: 100%;">
        <h2 class="text-center mb-4">Restablecer Contraseña</h2>
        
        <?php if(isset($_SESSION['mensaje'])): ?>
          <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?>">
            <?php 
              echo $_SESSION['mensaje']['texto']; 
              unset($_SESSION['mensaje']);
            ?>
          </div>
        <?php endif; ?>
        
        <form action="reset_password.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="POST">
          <!-- Campo oculto para el token -->
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
          
          <!-- Nueva Contraseña con input group -->
          <div class="mb-3">
            <label for="nueva_contraseña" class="form-label">Nueva Contraseña</label>
            <div class="input-group">
              <input 
                type="password" 
                class="form-control" 
                name="nueva_contraseña" 
                id="nueva_contraseña" 
                placeholder="Ingresa tu nueva contraseña" 
                required
              >
              <button 
                class="btn btn-outline-secondary" 
                type="button" 
                onclick="toggleVisibility('nueva_contraseña', this)"
              >
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          
          <!-- Confirmar Contraseña con input group -->
          <div class="mb-3">
            <label for="confirmar_contraseña" class="form-label">Confirmar Contraseña</label>
            <div class="input-group">
              <input 
                type="password" 
                class="form-control" 
                name="confirmar_contraseña" 
                id="confirmar_contraseña" 
                placeholder="Confirma tu nueva contraseña" 
                required
              >
              <button 
                class="btn btn-outline-secondary" 
                type="button" 
                onclick="toggleVisibility('confirmar_contraseña', this)"
              >
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          
          <div class="d-grid">
            <button type="submit" name="reset_password" class="btn btn-primary">Restablecer Contraseña</button>
          </div>
        </form>
      </div>
    </div>
  </main>
  
  <!-- Incluye tu footer (por ejemplo, footer.php) -->
  <?php include 'footer.php'; ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleVisibility(fieldId, btn) {
      const field = document.getElementById(fieldId);
      const icon = btn.querySelector('i');
      if (field.type === "password") {
        field.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        field.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>
