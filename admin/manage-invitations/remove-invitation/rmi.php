<?php
	include_once '../../includes/db_connect.php';
	include_once '../../includes/functions.php';
	secure_session_start();

	if(isset($_POST['uid'])){
		if(login_check($mysqli)){
			if(isUser($_POST['uid'], $mysqli)){
				$uid = $_POST['uid'];
				$mysqli->query("DELETE FROM ausstehende_einladungen WHERE uid = '$uid'");
				unlink("../../../users/$uid/$uid.json");
				unlink("../../../users/$uid/avatar.jpg");
				rmdir("../../../users/$uid/");
				echo success(["message"=>"Einladung wurde entfernt"]);
			} else {
				echo error('internalError', 500, 'Cannot find user in database');
			}
		} else {
			echo error('clientError', 403, 'Forbidden');
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
?>