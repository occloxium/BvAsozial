<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();

	if(isset($_POST['uid'])){
		if(login_check($mysqli) && $_SESSION['user']['is_admin']){
			if(isUser($_POST['uid'], $mysqli)){
				$uid = $_POST['uid'];
				// START recovery
				$user = getUser($uid, $mysqli);
				$user['friends'] = getFriends($uid, $mysqli);
				$addData = json_decode(file_get_contents(ABS_PATH . "/users/$uid/$uid.json"), true);

				$backup = [
					"user_data" => $user,
					"additional_data" => $addData
				];
				// Hash uid for removal file name
				$filename = md5($uid);
				$file = json_encode($backup, JSON_PRETTY_PRINT);
				file_put_contents(ABS_PATH . "/backup/$filename.bak");
				$mysqli->query("INSERT INTO entfernte_profile (uid, recovery_file) VALUES ('$uid', '$filename')");
				// END recovery
				$mysqli->query("DELETE FROM person WHERE uid = '$uid'");
				unlink(ABS_PATH . "/users/$uid/$uid.json");
				unlink(ABS_PATH . "/users/$uid/avatar.jpg");
				rmdir(ABS_PATH . "/users/$uid/");
				echo success(["message"=>"Benutzer wurde entfernt"]);
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
