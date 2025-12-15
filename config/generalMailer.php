<?php
include "PHPMailer/src/PHPMailer.php";
include "PHPMailer/src/Exception.php";
include "PHPMailer/src/SMTP.php";
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function sendMail($target, $subject, $body) {

    global $conn;
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = "rabuh.noreply@gmail.com";
    $mail->Password   = "";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom("rabuh.noreply@gmail.com", 'Rabuh');
    $mail->addAddress($target);

    $mail->isHTML(true);



    // $mail->isSMTP();
    // $mail->Host = "rabuh.noreply@gmail.com";
    // $mail->SMTPAuth = true;
    // $mail->SMTPSecure = "tls";
    // $mail->Port = "465";
    // $mail->Username = 'rabuh.noreply@gmail.com';
    // $mail->Password = "rirhfeunltcmknqj";
    // $mail->isHTML(true);
    // $mail->setFrom("rabuh.noreply@gmail.com");
    // $mail->addAddress($target);
    
    
    $mail->Subject = $subject;
    $mail->Body = $body;
    if ($mail->send()) {
        return true;
    } else {
        errlog("EMAIL ERROR", $mail->ErrorInfo);
    }
    return false;
}
