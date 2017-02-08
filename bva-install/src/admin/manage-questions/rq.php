<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();

	if(isset($_POST['id'])){
		if(login_check($mysqli) && $_SESSION['user']['is_admin']){
      $fragenkatalog = json_decode(file_get_contents(ABS_PATH . "/registrieren/fragenkatalog.json"), true);
			$id = $_POST['id'];
			$type = substr($id, 9, 1);
			$frage = substr($id, 11);
			$id = intval($frage) - 1;
			if($type == 1){
				//freundesfragen
				unset($fragenkatalog['freundesfragen'][$id]);
			} else {
				//eigeneFragen
				unset($fragenkatalog['eigeneFragen'][$id]);
			}
			if(file_put_contents(ABS_PATH . "/registrieren/fragenkatalog.json", json_encode($fragenkatalog, JSON_PRETTY_PRINT)) > 0){
				success(['id' => $_POST['id']]);
			} else {
				error('internalError', 500, "Could not write to file");
			}
		} else {
			error('clientError', 403, 'Forbidden');
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}
?>
