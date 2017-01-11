<?php
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	if(isset($_POST['uid'],$_POST['password'])){
		$username = $_POST['uid'];
		$password = $_POST['password'];
		secure_session_start();
		if(login($username, $password, $mysqli) == true){
			success([$_SESSION['user']]);
		}
		else {
			error('internalError', 500, 'Ungültiger Benutzername oder Passwort falsch');
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}

?>
