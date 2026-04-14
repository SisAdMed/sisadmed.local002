<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require EMAILER . 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    // Configuración del servidor SMTP

    $mail->isSMTP();
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->CharSet = 'UTF-8';
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'Ventas@corp-mmq.com';
    $mail->Password = 'M0tor$2024!';
    $mail->SMTPSecure = 'Tls';
    $mail->Port = 25;
    // Remitente y destinatario
    $mail->SetFrom('Ventas@corp-mmq.com', 'DEPARTAMENTO DE SISTEMAS - SisAdMed');
    $mail->addAddress('cheovargas@gmail.com', 'Cheito');
    // Contenido del correo
    $mail->IsHTML(true);
    $mail->Subject = 'INSTRUCTIVO ADMISIONES, mi empresa';
    $mail->Body = 'Este es el contenido del correo en <b>HTML</b>';
    $mail->AltBody = 'Texto plano';

    if(!$mail->send()) {
        echo 'El emnsaje no se envió.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Mensaje enviado';
    }

    header('Location:' . base_url . '/Empresas');
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    header('Location:' . base_url . '/Empresas');
}