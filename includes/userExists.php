<?php
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	secure_session_start();
	if(login_check($mysqli) && $_SESSION['user']['is_admin']){
		if(isset($_POST['u'])){
			$exists = false;
			if(userExists($_POST['u'], $mysqli)){
				$exists = true;
			}
			success(["exists" => $exists]);
		} else {
			error('clientError', 400, 'Bad Request');
		}
	} else {
		error('clientError', 403, 'Forbidden');
	}

?>
