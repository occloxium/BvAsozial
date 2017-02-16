<?php
  /**
   * Script to add new moderators (VIA AJAX REQUEST)
   * @author Alexander Bartolomey | 2017
   * @package BvAsozial 1.2
   */
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();

	if(isset($_POST)){
		if(login_check($mysqli) && $_SESSION['user']['is_admin']){
      if(count($_POST) > 0){
        $log = "";
        foreach($POST as $uid){
          if(isUser($uid, $mysqli) && !is_mod($uid, $mysqli) && !is_admin($uid, $mysqli)){
            if($stmt = $mysqli->prepare('INSERT INTO moderatoren (boundToWHERE) VALUES (?)')){
      				$stmt->bind_param('s', $user);
      				$stmt->execute();
      				if($stmt->errno != 00000){
      					$log .= "Failed to upgrade $uid \n";
      				} else {
      					$log .= "Successfully upgraded $uid as moderator \n";
      				}
      			} else {
      				echo error('internalError', 500, 'Could not prepare statement');
      			}
          } else {
            echo error('clientError', 400, "$uid is already top-tier and can't be upgraded anymore");
          }
        }
        echo success(['log' => $log]);
      } else {
        echo success(['log' => "There were no users to upgrade"]);
      }
		} else {
			echo error('clientError', 403, 'Forbidden');
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
?>
