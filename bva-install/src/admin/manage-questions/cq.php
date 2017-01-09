<?php
	include_once '../includes/db_connect.php';
	include_once '../includes/functions.php';
	secure_session_start();

	if(isset($_POST['id'])){
		if(login_check($mysqli)){
          $fragenkatalog = json_decode(file_get_contents("../../registrieren/fragenkatalog.json"), true);
          $id = $_POST['id'];
          try {
           
          } catch(Exception $e) {
            echo error('internalError', 500, $e->message);
          }
		} else {
			echo error('clientError', 403, 'Forbidden');
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
?>
