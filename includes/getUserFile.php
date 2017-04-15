<?php
  /**
   * Returns a JSON encoded string of the user data stored in his file which is no
   * longer simply accessible through HTTP(S) throwing 403 Forbidden
   * Alexander Bartolomey | 2017
   * @package BvAsozial 1.2
   */

  require('constants.php');
  require(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();

  if(login_check($mysqli)){
    if(!is_admin($_SESSION['user']['uid'], $mysqli)){
      echo success(["json" => file_get_contents(ABS_PATH.'/users/data/'.$_SESSION['user']['uid'].'/'.$_SESSION['user']['uid'].'.json')]);
    } else {
      echo error('internalError', 500, 'There is no user data for an admin uid');
    }
  } else {
    echo error('clientError', 403, 'Forbidden');
  }
 ?>
