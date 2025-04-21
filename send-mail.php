<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "tucorreo@dominio.com";  // <-- Cambia aquí por tu correo real
    $subject = "Nuevo mensaje del formulario de contacto";

    $name = strip_tags(trim($_POST["Name"]));
    $phone = strip_tags(trim($_POST["Phone"]));
    $email = filter_var(trim($_POST["Email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["Message"]);

    // Validamos los campos (opcional pero recomendable)
    if (empty($name) || empty($phone) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        echo "Por favor, completa todos los campos correctamente.";
        exit;
    }

    $body = "Nombre: $name\n";
    $body .= "Teléfono: $phone\n";
    $body .= "Correo: $email\n\n";
    $body .= "Mensaje:\n$message\n";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "Mensaje enviado correctamente.";
        // O redirigir:
        // header("Location: gracias.html"); 
    } else {
        echo "Error al enviar el mensaje. Intenta nuevamente.";
    }
}
?>
