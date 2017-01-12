<?php
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();

	if(isset($_POST['from'], $_POST['to'])){
		if(login_check($mysqli) == true){
			$request = [
				"from" => $_POST['from'],
				"to" => $_POST['to']
			];
			if(!requestSent($request['from'], $request['to'], $mysqli)){
				if($e = sendFriendRequest($request['from'], $request['to'], $mysqli)){
          echo success([]);
        } else {
            echo error('internalError', 500, $e);
        }
			} else {
				echo error('internalError', 500, 'Already sent');
			}
		} else {
			echo error('clientError', 403, 'Forbidden');
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}


?>
