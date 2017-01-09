<?php
	include_once 'db_connect.php';
	include_once 'functions.php';

	secure_session_start();

	if(!isset($_POST['user'], $_POST['friend']) || /*!login_check($mysqli)  ||*/ $_POST['user'] != $_SESSION['username']){
		echo error('clientError', 400, 'Bad Request', $_POST);
	} else {
		$self = $_POST['user'];
		$friend = $_POST['friend'];
		echo addAsFriend($friend, $self, $mysqli);
	}
?>