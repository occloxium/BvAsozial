<?php
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();
	
	$user = createUserObject($_POST);

	if(login_check($mysqli) && $_SESSION['user']['is_admin']){
		if($user != null){
			if(!userExists($user['uid'], $mysqli)){
				if(createNewUser($user, $mysqli) == true){
					include_once '../register-user/mail.php';

					// Data for the email
					$e_uid = $user['uid'];
					$e_pw = $_POST['password']; // unhashed password
					$e_name = $user['name'];

					$email = include_once "../register-user/html.mail.php";
					success(["email" => $email]);
					exit;
				} else {
					exit;
				}
			} else {
				error('clientError', 400, 'Benutzer existiert bereits');
			}
		}
	}

?>
