<?php
/**
 * Updates a users privacy setting in the DB
 * Alexander Bartolomey | 2017
 * @package BvAsozial 1.2
 */

 require('constants.php');
 require_once(ABS_PATH.INC_PATH.'functions.php');

secure_session_start();
if(isset($_POST['allow'], $_POST['uid'])){
  if(login_check($mysqli)){
    if($stmt = $mysqli->prepare("UPDATE person SET privacySettings = ? WHERE uid = ?;")){
      $stmt->bind_param('is', $_POST['allow'], $_POST['uid']);
      $stmt->execute();
      if($stmt->error == ""){
        // Rebuild user session array
        $_SESSION['user']['privacySettings'] = getUser($_SESSION['user']['uid'], $mysqli)['privacySettings'];
        success([]);
      } else {
        error('internalError', 500, "mysqli statement error: " . $stmt->error);
      }
    } else {
      error('internalError', 500, "general mysqli error: " . $mysqli->error);
    }
  }
} else {
  error('clientError', 400, 'Bad Request');
}

?>
