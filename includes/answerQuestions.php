<?php
/**
 * Answers a certain question
 * Alexander Bartolomey - 2017
 *
 * @package BvAsozial 1.2
 */

require('constants.php');
require_once(ABS_PATH.INC_PATH.'functions.php');
secure_session_start();

if(login_check($mysqli) && $_POST['uid'] == $_SESSION['user']['uid']){
	if(isset($_POST['fragen'])){
		foreach($_POST['fragen'] as $frage){
			$user['uid'] = $frage['for'];
			if(isFriendsWith($user['uid'], $_POST['uid'], $mysqli) || is_privileged($_POST['uid'], $user['uid'])){
				$obj = json_decode(file_get_contents(ABS_PATH . "/users/{$user['uid']}/{$user['uid']}.json"), true);
				$frage['frageID'] = intVal($frage['frageID']) - 1;
				if($frage['type'] == 'freundesfragen'){
					$obj['freundesfragen'][$frage['frageID']]['antworten'][$frage['von']] = $frage['antwort'];
        } else {
          if(strlen($frage['antwort']) > 1){
              $obj['eigeneFragen'][$frage['frageID']]['beantwortet'] = true;
          } else {
              $obj['eigeneFragen'][$frage['frageID']]['beantwortet'] = false;
          }
					$obj['eigeneFragen'][$frage['frageID']]['antwort'] = $frage['antwort'];
				}
				file_put_contents("../users/{$user['directory']}/{$user['uid']}.json", json_encode($obj, JSON_PRETTY_PRINT));
				success(["msg" => "Frage beantwortet"]);
			} else {
				error('internalError', 403, 'Forbidden', ['There was no relationship and/or privilege found for the requesting user']);
			}
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
} else {
	echo error('clientError', 403, 'Forbidden');
}
?>
