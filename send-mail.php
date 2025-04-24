<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Cabecera para que devuelva JSON
header('Content-Type: application/json');

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.ionos.mx';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contacto@pixelperfect.com.mx'; 
    $mail->Password   = 'Denovan198611@'; // ⚠️ IMPORTANTE: nunca subas esto a producción sin ocultar la clave
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Remitente y destinatario
    $mail->setFrom('contacto@pixelperfect.com.mx', 'Formulario Enviado de Web');
    $mail->addReplyTo($_POST['Email'], $_POST['Name']);
    $mail->addAddress('contacto@pixelperfect.com.mx'); 

    // Contenido del mensaje
    $mail->isHTML(false);
    $mail->Subject = $_POST['Subject'] ?? 'Formulario de contacto';
    $mail->Body    = 
        "Nombre: {$_POST['Name']}\n" .
        "Teléfono: {$_POST['Phone']}\n" .
        "Email: {$_POST['Email']}\n\n" .
        "Mensaje:\n{$_POST['Message']}";

    $mail->send();

    echo json_encode(['status' => 'success', 'message' => 'Mensaje enviado correctamente.']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => "No se pudo enviar el mensaje. Error: {$mail->ErrorInfo}"]);
}
