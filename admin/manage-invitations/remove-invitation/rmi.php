<?php
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	secure_session_start();
	if(isset($_POST['uid'])){
		if(login_check($mysqli) && $_SESSION['user']['is_admin']){
			if(isUser($_POST['uid'], $mysqli)){
				$uid = $_POST['uid'];
				$mysqli->query("INSERT INTO entfernte_einladungen (uid) VALUES ('$uid')");
				$mysqli->query("DELETE FROM ausstehende_einladungen WHERE uid = '$uid'");
				unlink(ABS_PATH . "/users/$uid/$uid.json");
				unlink(ABS_PATH . "/users/$uid/avatar.jpg");
				rmdir(ABS_PATH . "/users/$uid/");
				success(["message"=>"Einladung wurde entfernt"]);
			} else {
				error('internalError', 500, 'Cannot find user in database');
			}
		} else {
			error('clientError', 403, 'Forbidden');
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}
?>
