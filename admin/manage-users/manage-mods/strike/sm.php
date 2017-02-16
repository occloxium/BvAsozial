<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();

	if(isset($_POST['uid'])){
		if(login_check($mysqli) && $_SESSION['user']['is_admin']){
			if(isUser($_POST['uid'], $mysqli) && is_mod($_POST['uid'], $mysqli)){
				$uid = $_POST['uid'];
				$mysqli->query("UPDATE moderatoren SET strikes = strikes + 1 WHERE boundTo = '$uid'");
				echo success(["message"=>"Moderator wurde verwarnt"]);
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
