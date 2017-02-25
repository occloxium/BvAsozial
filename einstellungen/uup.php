<?php
  /**
   * Updates a users personal data settings in the DB
   * Alexander Bartolomey | 2017
   * @package BvAsozial 1.2
   */
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	secure_session_start();
	if(isset($_POST['name'], $_POST['email'], $_POST['uid'])){
		if(login_check($mysqli)){
      if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        if($stmt = $mysqli->prepare("UPDATE person SET name = ?, email = ? WHERE uid = ?;")){
          $stmt->bind_param('sss', $_POST['name'], $_POST['email'], $_POST['uid']);
          $stmt->execute();
          if($stmt->affected_rows == 1){
            success([]);
          } else {
            error('internalError', 500, $stmt->error);
          }
        } else {
          error('internalError', 500, $mysqli->error);
        }
      } else {
        error('clientError', 400, 'email adress is not valid');
      }
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}
?>
