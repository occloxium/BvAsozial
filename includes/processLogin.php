<?php
	/**
	 * processLogin.php
	 * performs the login of any user. To be called by JavaScript. Does not handle redirections in any form
	 * @author Alexander Bartolomey | 2017
	 * @package BvAsozial 1.2
	 */

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
