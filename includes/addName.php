<?php
	/**
	 * Adds a certain additional name of a user
	 * Alexander Bartolomey - 2017
	 *
	 * @package BvAsozial 1.2
	 */

	require('constants.php');
 	require_once(ABS_PATH.INC_PATH.'functions.php');
 	secure_session_start();

	if(isset($_POST['for'], $_POST['name'], $_POST['postedBy'])){
		if(login_check($mysqli) && (is_privileged($_POST['postedBy'], $_POST['for']) || isFriendsWith($_POST['for'], $_POST['postedBy'], $mysqli))){
			$directory = '../users/' . $user['directory'] . '/' . $user['uid'] . '.json';
			$json = json_decode(file_get_contents($directory), true);
			if(!isset($_POST['undo'])){
				if(!in_array(strtolower($_POST['name']), array_map('strtolower', $json['rufnamen']))){
					array_push($json['rufnamen'], $_POST['name']); // Success!
          $html = rufnamenliste($user, $_SESSION['user']['uid'], $json['rufnamen'], $mysqli);
          success(["html" => $html]);
				} else {
					error('clientError', 400, 'Bad Request', ["Der angegebene Rufname ist ein Dublikat und bereits vorhanden"]);
				}
			} else {
				// undo post
				// find requested 'name' in 'rufnamen'-array and remove the entry.
				$list = $json['rufnamen'];
				$key = array_search($_POST['name'], $list);
				if($key !== false){
					unset($list[$key]);
					// echo response object
	        $html = rufnamenliste($_POST['username'], $_SESSION['user']['uid'], $list, $mysqli);
					success(["html" => $html]);
					$json['rufnamen'] = $list;
				} else {
	        $html = rufnamenliste($user, $_SESSION['user']['uid'], $list);
					success(["html" => $html, "request" => $_request, "error" => "Konnte zu widerrufenden Rufnamen nicht mehr in der Liste finden"]);
				}
			}
			file_put_contents($directory, json_encode($json, JSON_PRETTY_PRINT));
		} else {
			error('clientError', 403, 'Forbidden');
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}
?>
