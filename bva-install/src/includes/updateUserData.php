<?php
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	secure_session_start();
	if(isset($_POST['name'], $_POST['email'], $_POST['uid'])){
		if(login_check($mysqli)){
			if($stmt = $mysqli->prepare("UPDATE person SET name = ? WHERE uid = ?;")){
				$stmt->bind_param('ss', $_POST['name'], $_POST['uid']);
				$stmt->execute();
				if($stmt->affected_rows == 1){
					success([]);
				} else {
					error('internalError', 500, $stmt->error);
				}
			} else {
				error('internalError', 500, $mysqli->error);
			}
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}
?>
