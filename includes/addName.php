<?php
	/**
	 * Performs an addition of names by batch
	 * Alexander Bartolomey - 2017
	 *
	 * @package BvAsozial 1.2
	 */

	require('constants.php');
 	require(ABS_PATH.INC_PATH.'functions.php');

 	secure_session_start();

	if(isset($_POST['by'], $_POST['for'])){
		if(login_check($mysqli) && (is_privileged($_POST['by'], $_POST['for'], $mysqli) || isFriendsWith($_POST['for'], $_POST['by'], $mysqli))){
      $user = getUser($_POST['for'], $mysqli);
      $directory = ABS_PATH . '/users/data/' . $user['uid'] . '/' . $user['uid'] . '.json';
      $json = json_decode(file_get_contents($directory), true);
      $log = "";
      $json['rufnamen'] = [];
      if(isset($_POST['names']) && count($_POST['names']) > 0){
        foreach($_POST['names'] as $key=>$name){
          if(!in_array(strtolower($name), array_map('strtolower', $json['rufnamen']))){
            $json['rufnamen'][] = $name;
            $log .= "{$name} wurde {$_POST['for']} verpasst. \n";
          } else {
            $log .= "Der angegebene Rufname ist ein Dublikat und wurde deshalb nicht hinzugefügt \n";
          }
        }
      } else {
        $log .= "Es wurden keine hinzufügbaren Daten gesendet";
      }
      $html = rufnamenliste($user['uid'], $_SESSION['user']['uid'], $json['rufnamen'], $mysqli);
      echo success(["log" => $log, "html" => $html]);
			file_put_contents($directory, json_encode($json, JSON_PRETTY_PRINT));
		} else {
			error('clientError', 403, 'Forbidden');
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}
?>
