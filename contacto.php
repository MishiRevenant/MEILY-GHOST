<?php
// ---- INICIO DE LA LÓGICA MEJORADA CON PHPMailer ----
ini_set('display_errors', 1);
error_reporting(E_ALL);
// 1. Importar las clases de PHPMailer al inicio del script
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 2. Cargar los archivos necesarios de la librería PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Variable para mostrar mensajes al usuario (éxito o error)
$mensaje = '';
$clase_mensaje = ''; // Variable para el estilo del mensaje

// Verificamos si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recogemos y limpiamos los datos del formulario (esto no cambia)
    $nombre = strip_tags(trim($_POST["nombre"]));
    $correo = filter_var(trim($_POST["correo"]), FILTER_SANITIZE_EMAIL);
    $texto_mensaje = trim($_POST["mensaje"]);

    // Validación simple de los campos (esto no cambia)
    if (empty($nombre) || !filter_var($correo, FILTER_VALIDATE_EMAIL) || empty($texto_mensaje)) {
        $mensaje = 'Por favor, completa todos los campos correctamente.';
        $clase_mensaje = 'error';
    } else {
        // 3. Usamos PHPMailer en lugar de la función mail()
        $mail = new PHPMailer(true);

        try {
            // ---- Configuración del servidor SMTP de Gmail ----
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            // TU CORREO DE GMAIL
            $mail->Username   = 'jm5372533@gmail.com'; 
            // TU CONTRASEÑA DE APLICACIÓN GENERADA EN GOOGLE
            $mail->Password   = 'hwxr vfvo rhlt gktw'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8'; // Para que acepte tildes y caracteres especiales

            // ---- Configuración de los correos (quién envía y quién recibe) ----
            $mail->setFrom('tu_correo@gmail.com', 'Contacto Web Meily Ghost'); // El remitente
            $mail->addAddress('jm5372533@gmail.com', 'Meily Ghost');     // A quién le llega el correo
            $mail->addReplyTo($correo, $nombre); // Permite responder directamente al usuario

            // ---- Contenido del correo ----
            $mail->isHTML(false); // Enviamos como texto plano, que es más seguro
            $mail->Subject = "Nuevo mensaje de contacto de: $nombre";
            $mail->Body    = "Has recibido un nuevo mensaje desde tu página web.\n\n" .
                             "Nombre: $nombre\n" .
                             "Correo: $correo\n\n" .
                             "Mensaje:\n$texto_mensaje";

            $mail->send();
            $mensaje = '¡Gracias! Tu mensaje ha sido enviado.';
            $clase_mensaje = 'exito';

        } catch (Exception $e) {
            // Si algo falla, PHPMailer nos dará un error detallado
            $mensaje = "Lo sentimos, hubo un error. El mensaje no se pudo enviar. Error: {$mail->ErrorInfo}";
            $clase_mensaje = 'error';
        }
    }
}
// ---- FIN DE LA LÓGICA MEJORADA ----
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto | Meily Ghost</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="https://scontent.fcuz2-1.fna.fbcdn.net/v/t39.30808-6/476437022_122116658288648912_4755061113525152841_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=cc71e4&_nc_eui2=AeHOVONoddacloSa-9euEaNd6M0UMd6JblLozRQx3oluUgIBmybeAJPz3kU1-LY_gvbdVqGO-wNWDqIssLlwIIzX&_nc_ohc=T514HRelql0Q7kNvwGvM2FU&_nc_oc=AdlT8wRwiJuMjNNW807PuWouP_3WZrI3LSpRs_rNzWVP0w8_GI9CWZN4B_C2fWgbqZo&_nc_zt=23&_nc_ht=scontent.fcuz2-1.fna&_nc_gid=aZ2cD_78IJEfvvTr3YeoEQ&oh=00_AfYZFoPprW7hMg5uHqnwnKPYWVIMiGHmWpHgrX4jIPRgaw&oe=68DECCF3" type="image/x-icon">

    <style>
        .feedback-message {
            padding: 15px;
            margin: 0 auto 20px auto;
            max-width: 380px;
            border-radius: 8px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
        }
        .feedback-message.exito {
            background-color: #6045d3;
            color: #ffe766;
        }
        .feedback-message.error {
            background-color: #8a0303;
            color: #fff;
        }
    </style>
</head>
<body>
<nav id="sidebar" class="sidebar">
    <a href="index.html"><i class="fas fa-home"></i><span>Inicio</span></a>
    <a href="tienda.php"><i class="fas fa-store"></i><span>Tienda</span></a>
    <a href="inspiracion.html"><i class="fas fa-lightbulb"></i><span>Inspiración</span></a>
    <a href="acerca.html"><i class="fas fa-info-circle"></i><span>Acerca de</span></a>
    <a href="contacto.php" class="active"><i class="fas fa-envelope"></i><span>Contacto</span></a>
    <a href="admin.php"><i class="fas fa-user-shield"></i><span>Admin</span></a>

</nav>

<header>
    <img src="https://scontent.fcuz2-1.fna.fbcdn.net/v/t39.30808-6/468282342_586989107106293_9188685479668068517_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeGqaBqQuSIFdrSGvSeCK_SngyhqWNOuwTqDKGpY067BOvLpiHjJtqPjYQUTvRZUJhLMmiFs40xKKr6rZNwmCsUw&_nc_ohc=3bEk8xeZqmYQ7kNvwGefWmA&_nc_oc=AdmmvKQnCo2mKYnal3r9CuTk6eUCw43GxIoC50uF6AZydtUGiJxEAeZAhZbh35Oj1dQ&_nc_zt=23&_nc_ht=scontent.fcuz2-1.fna&_nc_gid=2d1C21sehre24ha-Q3TTLw&oh=00_AfYTjjRgGKhTZebiABbwRUNgh62Jq5s0xd4jeQ6X4XglZA&oe=68DE986D">
    <h1>Contacto</h1>
    <p class="intro-coraline">¡Cuéntanos tu idea, pedido especial o solo salúdanos!</p>
</header>

<section class="main-section">
    
    <?php if (!empty($mensaje)): ?>
        <div class="feedback-message <?php echo $clase_mensaje; ?>">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <form class="contact-form" method="POST" action="contacto.php">
        <input type="text" name="nombre" placeholder="Tu nombre" required>
        <input type="email" name="correo" placeholder="Tu correo" required>
        <textarea name="mensaje" placeholder="Escribe tu mensaje..." rows="5" required></textarea>
        <button type="submit">Enviar mensaje</button>
    </form>
    
    <div class="contact-social">
        <a href="https://www.facebook.com/profile.php?id=61569467367987" target="_blank">Facebook</a>
        <a href="https://wa.me/51952148941" target="_blank">WhatsApp</a>
        <a href="mailto:jm5372533@gmail.com" target="_blank">Email</a>
    </div>
</section>
<footer class="footer">
    &copy; 2025 Meily Ghost - Creaciones únicas hechas a mano
</footer>

<script src="sidebar.js"></script>
</body>
</html>