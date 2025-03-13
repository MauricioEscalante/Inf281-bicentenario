<?php
// Importar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;  
// Cargar el autoloader de Composer (si usaste Composer)
require 'vendor/autoload.php';
// Define una función para enviar el correo
function enviarCorreoVerificacion($email, $nombre, $token) {
   // $codigo = rand(100000, 999999);//aumente estooo
   // global $conexion;
   // $query = "UPDATE usuario SET codigo_verificacion = '$codigo' WHERE codigo = '$token'";
   // mysqli_query($conexion, $query);//hasta aqui aumente

    $mail = new PHPMailer(true);
    
    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Servidor SMTP de Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'haldirescalante@gmail.com'; // Tu correo de Gmail
        $mail->Password   = 'aonl bmxu nsst vqnu'; // Contraseña de aplicación de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8'; // Para acentos y caracteres especiales
        
        // Destinatarios
        $mail->setFrom('haldirescalante@gmail.com', 'Mauricio Escalante');
        $mail->addAddress($email, $nombre);
        
        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Verificación de cuenta';
        
        // URL de verificación (ajusta según tu dominio)
        $url = "http://localhost/bicen/validar.php?token=" . $token;
        
        // Cuerpo del mensaje
        $mail->Body = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .button { background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
                .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
                .footer { font-size: 12px; color: #6c757d; margin-top: 30px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Verificación de cuenta</h2>
                </div>
                <p>Hola ' . $nombre . ',</p>
                <p>Gracias por registrarte en nuestro sitio. Para completar tu registro y activar tu cuenta, por favor haz clic en el siguiente enlace:</p>
                <p style="text-align: center;">
                    <a href="' . $url . '" class="button">Verificar mi cuenta</a>
                </p>
                <p>O copia y pega el siguiente enlace en tu navegador:</p>
                <p>' . $url . '</p>
                <p>Si no has solicitado esta cuenta, puedes ignorar este correo.</p>
                <div class="footer">
                    <p>Saludos,<br>Tu Empresa</p>
                    <p>Este es un correo automático, por favor no responder.</p>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->AltBody = 'Hola ' . $nombre . ', gracias por registrarte. Para verificar tu cuenta, visita: ' . $url;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
?>