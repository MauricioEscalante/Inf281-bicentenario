
<?php
session_start();
include("modelo/conexion_bd.php");

// Función para mostrar la plantilla HTML con el contenido correcto
function mostrarPlantilla($titulo, $mensaje, $tipo = 'info', $mostrarFormulario = false, $token = '', $redireccionar = false) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
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
            .text-link {
                color: black;
            }
            .icon-large {
                font-size: 60px;
                margin-bottom: 20px;
            }
        </style>
        <?php if ($redireccionar): ?>
        <meta http-equiv="refresh" content="5;url=index.php" />
        <?php endif; ?>
    </head>
    <body>
        <div class="container">
            <div class="form-container">
                <div class="text-center">
                    <?php if ($tipo == 'info'): ?>
                        <i class="fas fa-envelope icon-large text-primary"></i>
                    <?php elseif ($tipo == 'success'): ?>
                        <i class="fas fa-check-circle icon-large text-success"></i>
                    <?php elseif ($tipo == 'warning'): ?>
                        <i class="fas fa-exclamation-triangle icon-large text-warning"></i>
                    <?php else: ?>
                        <i class="fas fa-times-circle icon-large text-danger"></i>
                    <?php endif; ?>
                    
                    <h2 class="mb-4"><?php echo $titulo; ?></h2>
                    
                    <div class="alert alert-<?php echo $tipo; ?>">
                        <?php echo $mensaje; ?>
                    </div>
                    
                    <?php if ($mostrarFormulario): ?>
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
                    <?php endif; ?>
                    
                    <?php if ($redireccionar): ?>
                    <p class="mt-4">Serás redirigido a la página de inicio en 5 segundos...</p>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <a href="index.php" class="text-link">Volver a la página de inicio</a>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}
// Si viene por POST (formulario con código)
if (isset($_POST['verificar'])) {
    $token = $_POST['token'];
    $codigo = $_POST['codigo'];
    
    // Verificar si el token y código existen y coinciden
    $query = "SELECT * FROM usuario WHERE token_verificacion = '$token' AND codigo_verificacion = '$codigo' AND verificado = 0";
    $resultado = mysqli_query($conexion, $query);
    
    if (mysqli_num_rows($resultado) > 0) {
        // Actualizar el estado del usuario a verificado
        $actualizar = "UPDATE usuario SET verificado = 1, token_verificacion = NULL, codigo_verificacion = NULL WHERE token_verificacion = '$token'";
        
        if (mysqli_query($conexion, $actualizar)) {
            mostrarPlantilla(
                '¡Verificación exitosa!',
                'Tu cuenta ha sido verificada correctamente. Ahora puedes iniciar sesión con tus credenciales.',
                'success',
                false,
                '',
                true // Redireccionar después de 5 segundos
            );
        } else {
            mostrarPlantilla(
                'Error en la verificación',
                'Hubo un problema al verificar tu cuenta: ' . mysqli_error($conexion),
                'danger',
                false
            );
        }
    } else {
        mostrarPlantilla(
            'Código incorrecto',
            'El código de verificación es incorrecto o ya ha sido utilizado. Por favor, intenta nuevamente.',
            'warning',
            true,
            $token
        );
    }
    exit;
}

// Verificar si viene del registro (estado=esperando)
if (isset($_GET['estado']) && $_GET['estado'] == 'esperando') {
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    mostrarPlantilla(
        'Verifica tu cuenta',
        'Hemos enviado un correo electrónico con un enlace de verificación y un código a ' . htmlspecialchars($email) . '. ' . 
        'Por favor, revisa tu bandeja de entrada (y la carpeta de spam) para completar el proceso de verificación.',
        'info',
        false
    );
    exit;
}
// Si viene por URL (verificación automática por enlace)
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Obtener información del usuario con este token
    $query = "SELECT * FROM usuario WHERE token_verificacion = '$token' AND verificado = 0";
    $resultado = mysqli_query($conexion, $query);
    if (!$resultado) {
    mostrarPlantilla(
        'Error en la consulta',
        'Error: ' . mysqli_error($conexion),
        'danger',
        false
    );
    exit;
}
    
    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        mostrarPlantilla(
            'Completa la verificación',
            'Haz clic en el enlace y ahora necesitas ingresar el código de 6 dígitos que enviamos a tu correo electrónico para verificar tu cuenta.',
            'info',
            true,
            $token
        );
    } else {
        mostrarPlantilla(
            'Token no válido',
            'El enlace de verificación no es válido o ya ha sido utilizado.',
            'danger',
            false
        );
    }
}
// Si no hay parámetros válidos, mostrar un mensaje genérico
if (!isset($_GET['estado']) && !isset($_GET['token']) && !isset($_POST['verificar'])) {
    mostrarPlantilla(
        'Verificación de cuenta',
        'Si ya te has registrado, por favor revisa tu correo electrónico para encontrar el enlace de verificación.',
        'info',
        false
    );
}
?>

