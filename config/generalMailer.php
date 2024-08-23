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
    $mail->Host = "mail.ewiz.gq";
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Port = "587";
    $mail->Username = 'account-creation-final-test@ewiz.gq';
    $mail->Password = "{+aQ@2-%Bm}w";
    $mail->isHTML(true);
    $mail->setFrom("account-creation-final-test@ewiz.gq");
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->addAddress($target);
    if ($mail->send()) {
        return true;
    }
    return false;
}
