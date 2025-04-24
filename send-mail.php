<?php
// Mostrar errores para debug (puedes quitar esto en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cabecera para que siempre devuelva JSON
header('Content-Type: application/json');

// Importar clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';


// Validar que se reciban los datos necesarios
if (
    empty($_POST['Name']) ||
    empty($_POST['Email']) ||
    empty($_POST['Phone']) ||
    empty($_POST['Message'])
) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Faltan datos en el formulario.'
    ]);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.ionos.mx';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contacto@pixelperfect.com.mx';
    $mail->Password   = 'Denovan198611@'; // ⚠️ No olvides ocultar esto en producción
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Remitente y destinatario
    $mail->setFrom('contacto@pixelperfect.com.mx', 'Formulario Enviado de Web');
    $mail->addReplyTo($_POST['Email'], $_POST['Name']);
    $mail->addAddress('contacto@pixelperfect.com.mx');

    // Contenido del mensaje
    $mail->isHTML(false);
    $mail->Subject = $_POST['Subject'] ?? 'Formulario de contacto';
    $mail->Body =
        "Nombre: {$_POST['Name']}\n" .
        "Teléfono: {$_POST['Phone']}\n" .
        "Email: {$_POST['Email']}\n\n" .
        "Mensaje:\n{$_POST['Message']}";

    // Enviar correo
    $mail->send();

    // Respuesta en formato JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Mensaje enviado correctamente.'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'No se pudo enviar el mensaje. Error: ' . $mail->ErrorInfo
    ]);
}
