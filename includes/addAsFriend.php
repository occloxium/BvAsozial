<?php
	/**
	 * Adds a user's makes a friend request
	 * Alexander Bartolomey - 2017
	 *
	 * @package BvAsozial 1.2
	 */

	require('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();

	if(isset($_POST['user'], $_POST['friend'])){
		if(login_check($mysqli) && $_POST['user'] == $_SESSION['user']['uid']){
			$self = $_POST['user'];
			$friend = $_POST['friend'];
      // Refresh session user array
      $_SESSION['user'] = getUser($_SESSION['user']['uid'], $mysqli);
      _checkGroups($_SESSION['user']['uid'], $mysqli);
			echo addAsFriend($friend, $self, $mysqli);
		} else {
			error('clientError', 403, 'Forbidden');
		}
	} else {
		error('clientError', 400, 'Bad Request');
	}
?>
