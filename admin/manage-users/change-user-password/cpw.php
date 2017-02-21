<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();
  /**
   * TODO CLIENT-SIDE HASHING!
   */
	if(isset($_POST['uid'], $_POST['password'])){
		if(login_check($mysqli) && $_SESSION['user']['is_admin']){
			$h_pw = hash('sha384', $_POST['password']);
			if($stmt = $mysqli->prepare('UPDATE login SET password = ? WHERE uid = ?')){
				$stmt->bind_param('ss', $h_pw, $_POST['uid']);
				$stmt->execute();
				if($stmt->errno == 00000){
					echo success(["html" => "<p>Das neue Passwort wurde gesetzt<br>Der Benutzer {$_POST['uid']} hat nun das Passwort: <br>{$_POST['password']}</p><a href=\"../\">Zurück</a>"]);
				} else {
					echo error('internalError', 500, 'Could not execute request');
				}
			} else {
				echo error('internalError', 500, 'Could not prepare statement');
			}
		} else {
			echo error('clientError', 403, 'Forbidden');
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
?>
