<?php
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	require(ABS_PATH.'/vendor/autoload.php');
	secure_session_start();

	$user = createUserObject($_POST);

	if(login_check($mysqli) && $_SESSION['user']['is_admin']){
		if($user != null){
			if(!userExists($user['uid'], $mysqli)){
				if(createNewUser($user, $mysqli) == true){

					// Data for the email
					$e_uid = $user['uid'];
					$e_pw = $_POST['password']; // unhashed password
					$e_name = $user['name'];

					$vorname = explode(' ', $user['name'])[0];
					$body = require_once(ABS_PATH.ADMIN_PATH.'register-user/html.mail.php');

					$mail = new PHPMailer;

					$mail->IsSMTP();
					$mail->CharSet = 'UTF-8';

					$mail->Host       = INVITE_HOST;
					$mail->SMTPAuth   = true;
					$mail->SMTPDebug  = 0;
					$mail->Port       = INVITE_PORT;
					$mail->Username   = INVITE_MAIL;
					$mail->Password   = INVITE_PASSWORD;
					$mail->SMTPSecure = 'tls';

					$mail->setFrom(INVITE_MAIL, INVITE_NAME);
					$mail->addAddress($user['email'], $user['name']);

					$mail->isHTML(true);
					$mail->Subject = 'Einladung zu BvAsozial';
					$mail->Body = $body;

					if(!$mail->send()) {
						echo error('internalError', 500, "Mail could not be sent");
					} else {
						success(["email" => $body]);
					}


					exit;
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
