<?php
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	if(isset($_POST['uid'], $_POST['password'])){
		if(isValidInvite($_POST['uid'], $_POST['password'], $mysqli)){
			if(!isset($_SESSION)){
				secure_session_start();
				$_SESSION = [
					'is_registering' => true,
					'registering' => [
						'steps' => 0,
						'uid' => $_POST['uid'],
						'password' => $_POST['password'],
						'email' => getInvitedUser($_POST['uid'], $mysqli)['email'],
						'name' => getInvitedUser($_POST['uid'], $mysqli)['name']
					]
				];
			}
  		success(["isValid" => true]);
    } else {
			if(isInvited($_POST['uid'], $mysqli)){
				success(["isValid" => false, "message" => "Wrong password"]);
			} else {
				if(isExpiredInvite($_POST['uid'], $mysqli)){
					success(["isValid" => false, "message" => "invite expired"]);
				} else {
					success(["isValid" => false, "message" => "Unknown username and / or password"]);
				}
			}
    }
	} else {
		error('clientError', 400, 'Bad Request');
	}

?>
