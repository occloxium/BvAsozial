<?php
	include_once 'db_connect.php';
	include_once 'functions.php';

	if(isset($_POST['u'])){
		$exists = false;
		if(userExists($_POST['u'], $mysqli)){
			$exists = true;
		}
		echo json_encode(["exists" => $exists], JSON_FORCE_OBJECT);
	} else {
		echo error('clientError', 400, 'Invalid method or POST corrupted');
	}
?>