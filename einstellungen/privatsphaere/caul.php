<?php
/**
 * Updates a users allowedUsers list
 * Alexander Bartolomey | 2017
 * @package BvAsozial 1.2
 */

  require('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
  if(isset($_POST)){
    if(login_check($mysqli)){
      $json = json_decode(file_get_contents(ABS_PATH . '/users/data/' . $_SESSION['user']['uid'] . '/' . $_SESSION['user']['uid'] . '.json'), true);
      $json['allowedUsers'] = [];
      foreach($_POST as $key => $value){
        $json['allowedUsers'][] = $value;
      }
      file_put_contents(ABS_PATH . '/users/data/' . $_SESSION['user']['uid'] . '/' . $_SESSION['user']['uid'] . '.json', json_encode($json, JSON_PRETTY_PRINT));
      success(["allowedUsers" => $json['allowedUsers']]);
    } else {
      error('clientError', 403, 'Forbidden');
    }
  } else {
   error('clientError', 400, 'Bad Request');
  }
?>
