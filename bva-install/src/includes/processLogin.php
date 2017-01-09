<?php
	include_once 'db_connect.php';
	include_once 'functions.php';
	if(isset($_POST['uid'],$_POST['password'])){
		$username = $_POST['uid'];
		$password = $_POST['password'];
		secure_session_start();
		if(login($username, $password, $mysqli) == true){
			echo json_encode([
				"success" => true,
				"uid" => $username,
				"session_id" => session_id()
			], JSON_PRETTY_PRINT|JSON_FORCE_OBJECT);
		}
		else {
			echo error('internalError', 500, 'Ungültiger Benutzername oder Passwort falsch');
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}

?>