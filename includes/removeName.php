<?php
  /**
	 * Removes a certain additional name of a user
	 * Alexander Bartolomey - 2017
	 *
	 * @package BvAsozial 1.2
	 */
	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();

	if(isset($_POST['username'], $_POST['name'])){
		if(login_check($mysqli) && is_privileged($_SESSION['user']['uid'], $_POST['username'], $mysqli)){
			$directory = ABS_PATH . "/users/{$_POST['username']}/{$_POST['username']}.json";
			$json = json_decode(file_get_contents($directory), true);
			$list = $json['rufnamen'];
			$key = array_search($_POST['name'], $list);
			if($key !== false){
					unset($list[$key]);
					$html = rufnamenliste($_POST['username'], $_SESSION['user']['uid'], $list, $mysqli);
					$json['rufnamen'] = $list;
					success(["html" => $html, "json"=>$list]);
			} else {
					$html = rufnamenliste($_POST['username'], $_SESSION['user']['uid'], $list, $mysqli);
					success(["error" => "Konnte zu entfernenden Rufnamen nicht mehr in der Liste finden", "html" => $html]);
			}
			file_put_contents($directory, json_encode($json, JSON_PRETTY_PRINT));
		} else {
			error('clientError', 403, 'Forbidden');
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}
?>
