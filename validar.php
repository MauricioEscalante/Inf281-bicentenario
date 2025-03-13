
<?php
session_start();
include("modelo/conexion_bd.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verificar si el token existe en la base de datos
    $query = "SELECT * FROM usuario WHERE token_verificacion = '$token' AND verificado = 0";
    $resultado = mysqli_query($conexion, $query);
    
    if (mysqli_num_rows($resultado) > 0) {
        // Actualizar el estado del usuario a verificado
        $actualizar = "UPDATE usuario SET verificado = 1, token_verificacion = NULL WHERE token_verificacion = '$token'";
        
        if (mysqli_query($conexion, $actualizar)) {
            $_SESSION['mensaje'] = array('tipo' => 'success', 'texto' => '¡Tu cuenta ha sido verificada exitosamente! Ahora puedes iniciar sesión.');
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'Error al verificar la cuenta: ' . mysqli_error($conexion));
        }
    } else {
        $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'El token de verificación no es válido o ya ha sido utilizado.');
    }
    
    header('Location: index.php');
    exit;
} else {
    $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'No se proporcionó un token de verificación.');
    header('Location: index.php');
    exit;
}
?>

<?php
/*session_start();
include("modelo/conexion_bd.php");

// Si viene por URL (verificación automática por enlace)
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Obtener información del usuario con este token
    $query = "SELECT * FROM usuario WHERE codigo = '$token' AND verificado = 0";
    $resultado = mysqli_query($conexion, $query);
    
    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        $codigo = $usuario['codigo_verificacion'];
        
        // Mostrar formulario para ingresar el código
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <title>Verificación de cuenta</title>
            <style>
                body {
                    background-image: url('img/bicen1.jpeg');
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    background-attachment: fixed;
                }
                .form-container {
                    max-width: 500px;
                    margin: 100px auto;
                    padding: 30px;
                    background-color: rgba(255, 255, 255, 0.9);
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .verification-code {
                    display: flex;
                    justify-content: center;
                    margin: 20px 0;
                }
                .verification-code input {
                    width: 50px;
                    height: 60px;
                    margin: 0 5px;
                    text-align: center;
                    font-size: 24px;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="form-container">
                    <h2 class="text-center mb-4">Verificación de cuenta</h2>
                    
                    <p class="text-center">Para completar tu registro, ingresa el código de verificación de 6 dígitos que enviamos a tu correo electrónico.</p>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        
                        <div class="mb-4">
                            <label for="codigo" class="form-label">Código de verificación</label>
                            <input type="text" class="form-control form-control-lg text-center" id="codigo" name="codigo" maxlength="6" placeholder="Ingresa el código de 6 dígitos" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="verificar" class="btn btn-primary btn-lg">Verificar cuenta</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
        exit;
    } else {
        $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'El token de verificación no es válido o ya ha sido utilizado.');
        header('Location: index.php');
        exit;
    }
}

// Si viene por POST (formulario con código)
if (isset($_POST['verificar'])) {
    $token = $_POST['token'];
    $codigo = $_POST['codigo'];
    
    // Verificar si el token y código existen y coinciden
    $query = "SELECT * FROM usuario WHERE codigo = '$token' AND codigo_verificacion = '$codigo' AND verificado = 0";
    $resultado = mysqli_query($conexion, $query);
    
    if (mysqli_num_rows($resultado) > 0) {
        // Actualizar el estado del usuario a verificado
        $actualizar = "UPDATE usuario SET verificado = 1, codigo = NULL, codigo_verificacion = NULL WHERE codigo = '$token'";
        
        if (mysqli_query($conexion, $actualizar)) {
            $_SESSION['mensaje'] = array('tipo' => 'success', 'texto' => '¡Tu cuenta ha sido verificada exitosamente! Ahora puedes iniciar sesión.');
        } else {
            $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'Error al verificar la cuenta: ' . mysqli_error($conexion));
        }
    } else {
        $_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'El código de verificación es incorrecto o ya ha sido utilizado.');
    }
    
    header('Location: index.php');
    exit;
}

// Si no hay token ni POST, redirigir
$_SESSION['mensaje'] = array('tipo' => 'danger', 'texto' => 'No se proporcionó un token de verificación.');
header('Location: index.php');
exit;*/
?>