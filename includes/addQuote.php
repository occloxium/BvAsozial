<?php
	/**
	 * Adds a qoute for a user
	 * Alexander Bartolomey - 2017
	 *
	 * @package BvAsozial 1.2
	 */

	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();

	if(isset($_POST['uid'], $_POST['quote'])){
		if(login_check($mysqli) == true){
			$path = ABS_PATH . "/users/{$_POST['uid']}/{$_POST['uid']}.json";
			$jsonstr = file_get_contents($path);
			$o = json_decode($jsonstr, true);
			$o['spruch'] = $_POST['quote'];
			file_put_contents($path, json_encode($o, JSON_PRETTY_PRINT));
			success(['spruch' => $_POST['quote']]);
		} else {
			error('clientError', 403, 'Forbidden');
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}
?>
