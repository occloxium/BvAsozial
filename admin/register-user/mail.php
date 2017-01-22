<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	if (login_check($mysqli) && isset($_POST) && $_SESSION['user']['is_admin']){
		$vorname = explode(' ', $_POST['name'])[0];
		$e_uid = $_POST['uid'];
		$e_pw = $_POST['password'];
		$e_name = $_POST['name'];
		$invite_name = INVITE_NAME;

		$mail = new PHPMailer;

		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host = INVITE_HOST;
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 1;
		$mail->Port = INVITE_PORT;
		$mail->Username = INVITE_MAIL;
		$mail->Password = INVITE_PASSWORD;
		$mail->SMTPSecure = 'tls';

		$mail->setFrom(INVITE_MAIL, INVITE_NAME);
		$mail->addAddress($_POST['email'], $_POST['name']);

		$mail->isHTML(true);
		$mail->Subject = 'Einladung zu BvAsozial';
		$mail->Body = require_once('html.mail.php');
		$mail->AltBody = require_once('text.mail.php');

		if(!$mail->send()) {
			echo error('internalError', 500, "Mail could not be sent");
			return "";
		} else {
			return $body;
		}
	} else {
		error('clientError', 403, 'Forbidden');
	}
?>
