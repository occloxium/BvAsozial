<?php
// Test file for mailing

require('constants.php');
require(ABS_PATH.INC_PATH.'functions.php');

// set some variables
$e_uid = "occloxium";
$e_pw ="123456";
$e_name = "Alexander Bartolomey";
$vorname = "Alexander";
$invite_name = "BvAsozial Adm.in";

$mail = new PHPMailer();

$mail->IsSMTP();
$mail->CharSet = 'UTF-8';

$mail->Host = INVITE_HOST;
$mail->SMTPAuth = true;
$mail->SMTPDebug = 0;
$mail->Port = INVITE_PORT;
$mail->Username = INVITE_MAIL;
$mail->Password = INVITE_PASSWORD;
$mail->SMTPSecure = 'tls';

$mail->setFrom(INVITE_MAIL, INVITE_NAME);
$mail->addAddress("occloxium@gmail.com");

$mail->isHTML(true);
$mail->Subject = 'Einladung zu BvAsozial';
$mail->Body = require_once('html.mail.php');
$mail->AltBody = require_once('text.mail.php');

if(!$mail->send()) {
  echo error('internalError', 500, "Mail could not be sent");
  return "";
} else {
  return $mail->Body;
}

 ?>
