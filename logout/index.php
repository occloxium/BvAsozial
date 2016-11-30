<?php
	include_once '../includes/functions.php';
	secure_session_start();
	$_SESSION = array(); // Reset session variables
	$params = session_get_cookie_params();
	setcookie(session_name(),
					'', time() - 42000,
					$params["path"],
					$params["domain"],
					$params["secure"],
					$params["httponly"]); // Override session cookie
	session_destroy(); // destroy user session
	header("Location: ../");
?>