<?php
/**
 * Answers a set of questions
 * Alexander Bartolomey - 2017
 *
 * @package BvAsozial 1.2
 */

require('constants.php');
require_once(ABS_PATH.INC_PATH.'functions.php');
secure_session_start();

if(login_check($mysqli) && $_SESSION['user']['uid'] == $_POST['by']){
	if(isset($_POST['fragen'])){
    $log = "";
		foreach($_POST['fragen'] as $frage){
			if(isFriendsWith($frage['for'], $_POST['by'], $mysqli) || is_privileged($_POST['by'], $frage['for'], $mysqli)){
				$obj = json_decode(file_get_contents(ABS_PATH . "/users/{$frage['for']}/{$frage['for']}.json"), true);
				$frage['frageID'] = intVal($frage['frageID']) - 1;
				if($frage['type'] == 'freundesfrage'){
          if(strlen($frage['antwort']) > 0){
            $obj['freundesfragen'][$frage['frageID']]['antworten'][$frage['von']] = $frage['antwort'];
          } else {
            unset($obj['freundesfragen'][$frage['frageID']]['antworten'][$frage['von']]);
          }
          $log .= $obj['freundesfragen'][$frage['frageID']]['antworten'][$frage['von']] . "\n";
        } else {
          if(strlen($frage['antwort']) > 0){
            $obj['eigeneFragen'][$frage['frageID']]['beantwortet'] = true;
          } else {
            $obj['eigeneFragen'][$frage['frageID']]['beantwortet'] = false;
          }
					$obj['eigeneFragen'][$frage['frageID']]['antwort'] = $frage['antwort'];
				}
        $log .= "Answer from {$frage['von']} for {$frage['for']} saved. \n";
				file_put_contents(ABS_PATH . "/users/{$frage['for']}/{$frage['for']}.json", json_encode($obj, JSON_PRETTY_PRINT));
			} else {
				$log .= "There was no relationship and/or privilege found for the requesting user \n";
			}
		}
    echo success(['log' => $log]);
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
} else {
	echo error('clientError', 403, 'Forbidden');
}
?>
