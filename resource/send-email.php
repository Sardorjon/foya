<?php
require 'class.phpmailer.php';
require 'PHPMailerAutoload.php';
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = 'smtp';
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->IsHTML(true);

$mail->Username = "sardortemirov120@gmail.com";
$mail->Password = "sardor!!3290093";






//Sender Info
$mail->From = "sardortemirov120@gmail.com";
$mail->FromName = "FOYA";
