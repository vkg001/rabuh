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
    $mail->Host = "";
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Port = "587";
    $mail->Username = '';
    $mail->Password = "";
    $mail->isHTML(true);
    $mail->setFrom("");
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->addAddress($target);
    if ($mail->send()) {
        return true;
    }
    return false;
}
