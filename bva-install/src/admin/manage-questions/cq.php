<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();

	if(isset($_POST['id'])){
		if(login_check($mysqli) && $_SESSION['user']['is_admin']){
      $fragenkatalog = json_decode(file_get_contents(ABS_PATH . "/registrieren/fragenkatalog.json"), true);
      $id = $_POST['id'];
      try {
				// TODO implement search in array and replace question with posted on
      } catch(Exception $e) {
        error('internalError', 500, $e->message);
      }
		} else {
			echo error('clientError', 403, 'Forbidden');
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
?>
