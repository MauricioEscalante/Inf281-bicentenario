<?php
session_start();
include("modelo/conexion_bd.php");

// Procesar el formulario de login
if (isset($_POST['login'])) {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contraseña = mysqli_real_escape_string($conexion, $_POST['contraseña']);
    
    // Consulta para obtener el usuario
    $query = "SELECT * FROM usuario WHERE Correo = '$correo'";
    $resultado = mysqli_query($conexion, $query);
    
    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        // Verificar la contraseña (suponiendo que se guardó con password_hash)
        if (password_verify($contraseña, $usuario['Contraseña'])) {
            // Guarda la información del usuario en la sesión
            $_SESSION['usuario'] = $usuario;
            // Redirecciona según el rol
            
            switch ($usuario['Rol']) {
                case '1':
                    header("Location: administrador.php");
                    exit;
                case '3':
                    header("Location: organizador.php");
                    exit;
                case '2':
                    header("Location: comprador.php");
                    exit;
                case '4':
                    header("Location: vendedor.php");
                     exit;
    
                // Puedes agregar más roles si los tienes
                default:
                    header("Location: otra_interface.php");
                    exit;
            }
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'Contraseña incorrecta.');
        }
    } else {
        $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'El correo no se encuentra registrado.');
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Font Awesome para el ícono del ojo -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    /* Fondo y estilos generales */
    .bg-image {
      background: url('img/bicen1.jpeg') no-repeat center center fixed;
      background-size: cover;
    }
    .card-translucent {
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    body, html {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
</head>
<body class="d-flex flex-column bg-image">
  <main class="flex-fill d-flex align-items-center justify-content-center">
    <div class="card card-translucent p-4" style="max-width: 400px; width: 100%;">
      <h2 class="text-center mb-4">Iniciar Sesión</h2>
      
      <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?>">
          <?php 
            echo $_SESSION['mensaje']['texto'];
            unset($_SESSION['mensaje']);
          ?>
        </div>
      <?php endif; ?>
      
      <form action="login.php" method="POST">
        <div class="mb-3">
          <label for="correo" class="form-label">Correo Electrónico</label>
          <input type="email" name="correo" id="correo" class="form-control" placeholder="Ingresa tu correo" required>
        </div>
        <!-- Input group para la contraseña con el botón de mostrar/ocultar dentro de la caja -->
        <div class="mb-3">
          <label for="contraseña" class="form-label">Contraseña</label>
          <div class="input-group">
            <input type="password" name="contraseña" id="contraseña" class="form-control" placeholder="Ingresa tu contraseña" required>
            <button class="btn btn-outline-secondary" type="button" onclick="toggleVisibility('contraseña', this)">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        
        <div class="d-grid">
          <button type="submit" name="login" class="btn btn-primary">Iniciar Sesión</button>
        </div>
      </form>
      
      <div class="mt-3 text-center">
        <a href="recuperar.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
      </div>
      <div class="mt-2 text-center">
        <span>¿No tienes cuenta?</span>
        <a href="registro.php" class="text-decoration-none">Regístrate</a>
      </div>
    </div>
  </main>
  
  <!-- Incluye el footer de index -->
  <?php include 'footer.php'; ?>
  
  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Script para ver/ocultar contraseña -->
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
