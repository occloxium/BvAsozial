<?php
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	secure_session_start();

	$user = createUserObject($_POST);

	if(login_check($mysqli) && $_SESSION['user']['is_admin']){
		if($user != null){
			if(!userExists($user['uid'], $mysqli)){
				if(createNewUser($user, $mysqli) == true){
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
					$mail->SMTPDebug = 2;
					$mail->Port = INVITE_PORT;
					$mail->Username = INVITE_MAIL;
					$mail->Password = INVITE_PASSWORD;
					$mail->SMTPSecure = 'tls';
          $mail->SMTPOptions = array(
            'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
            )
          );


					$mail->setFrom(INVITE_MAIL, INVITE_NAME);
					$mail->addAddress($_POST['email'], $_POST['name']);

					$mail->isHTML(true);
					$mail->Subject = 'Einladung zu BvAsozial';

					$body = require_once(ABS_PATH.ADMIN_PATH.'register-user/html.mail.php');
					$mail->Body = $body;
					$mail->AltBody = require_once(ABS_PATH.ADMIN_PATH.'register-user/text.mail.php');

					if(!$mail->send()) {
						error('internalError', 500, "Mail could not be sent");
					} else {
						success(["html" => $body]);
					}
				} else {
					error('internalError', 500, 'Unable to create user. See previous error messages for detailed information');
					exit;
				}
			} else {
				error('internalError', 500, 'Benutzer existiert bereits');
			}
		}
	}

?>
