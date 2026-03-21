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
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'mail.dataknights.site';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'no-reply@dataknights.site';
        $mail->Password   = 'dk_knights_2026';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Email headers
        $mail->setFrom('no-reply@dataknights.site', 'Dataknights System');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;
        $mail->AltBody = strip_tags($bodyHtml);

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Optional: log error to file (do NOT echo in production)
        // error_log($mail->ErrorInfo);
        return false;
    }
}
