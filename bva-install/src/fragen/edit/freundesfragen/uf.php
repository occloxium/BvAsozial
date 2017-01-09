<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	secure_session_start();

	if(login_check($mysqli) == true){
		$user = getUser($_SESSION['username'], $mysqli);
		if(isset($_POST)){
			$obj = json_decode(file_get_contents(ABS_PATH . '/registrieren/fragenkatalog.json'), true);
			$userfile = json_decode(file_get_contents(ABS_PATH. "/users/{$user['directory']}/{$user['uid']}.json"), true);
			$new_userfile = [];
			foreach($_POST as $key=>$element){
				if(preg_match("/^f-frage-[1-9][0-9]*/", $key)){
					$found = false;
					$pos = 0;
					$num = intVal(substr($key,8));
					foreach($userfile['freundesfragen'] as $key2=>$user_frage){
						if(stripos($user_frage['frage'], $obj['freundesfragen'][$num - 1]) !== false){
							$found = true;
							$pos = $key2;
						}
					}
					if($found){
						$new_userfile[] = $userfile['freundesfragen'][$pos];
					} else {
						$new_obj = [
							"frage" => $obj['freundesfragen'][$num - 1],
							"antworten" => []
						];
						$new_userfile[] = $new_obj;
					}
				}
			}
			$userfile['freundesfragen'] = $new_userfile;
			if(file_put_contents(ABS_PATH . "/users/{$user['directory']}/{$user['uid']}.json", json_encode($userfile, JSON_PRETTY_PRINT)) > 0){
				header("Location: {ABS_PATH}/fragen/");
				exit;
			} else {
				echo error("internalError", 500, "Unable to write to file. No changes were made. If you receive this error as a common user, please report it to an Alex");
			}
		} else {
			echo error('clientError', 400, 'Bad Request');
		}
	} else {
		echo error('clientError', 403, 'Forbidden');
	}
