<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/PHPMailer/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer/SMTP.php';

function sendSystemEmail($to, $subject, $bodyHtml)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP Settings (Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'laylamalakas36@gmail.com'; // Gmail account
        $mail->Password   = 'ivjuagwhniqozgqh';       // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
        $mail->Port       = 587;


        // Email headers
        $mail->setFrom('laylamalakas36@gmail.com', 'Phinma Uniforms System');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;
        $mail->AltBody = strip_tags($bodyHtml);

        $mail->send();
        return true;

    } catch (Exception $e) {
        echo '<p><strong>Mailer Error:</strong> ' . $e->getMessage() . '</p>';
        return false;
    }
}
