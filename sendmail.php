<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/autoload.php'; //Load Composer's autoloader
require 'functions.php';

////It WORKS ON SERVER
function sendEmail($emailaddress, $subject, $htmltext) {
    $mail = new PHPMailer;
    global $appname;
    $mail->isSMTP();                            // Set mailer to use SMTP
    $mail->Host = 'smtp.mail.ru';             // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                     // Enable SMTP authentication
    $mail->Username = 'dlugenina@inbox.ru';          // SMTP username
    $mail->Password = 'Ewqasd12'; // SMTP password
    $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` only works on mail.ru
    $mail->Port = 465;                          // TCP port to connect to
    $mail->setFrom('dlugenina@inbox.ru', $appname);
//$mail->addReplyTo('justvladick@gmail.com', 'CodexWorld');
    $mail->addAddress($emailaddress);   // Add a recipient
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');
    $mail->CharSet = 'utf-8';
    $mail->isHTML(true);  // Set email format to HTML

    $bodyContent = '<h1>Письмо от команды ' . $appname . '</h1>';
    $bodyContent .= $htmltext;

    $mail->Subject = $subject;
    $mail->Body = $bodyContent;

    if (!$mail->send()) {
        return 'Сообщение не выслано. '
        . 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        return 'На адрес '.$emailaddress.' выслано сообщение.';
    }
}

?>