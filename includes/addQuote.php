<?php
	include_once 'db_connect.php';
	include_once 'functions.php';

	secure_session_start();
	if(isset($_POST['uid'], $_POST['quote'])){
		if(login_check($mysqli) == true){
			try {
				if(!isset($_POST['uid'], $_POST['quote'])){
					throw new Exception("Invalid POST");
				}
				$directory = getMinimalUser($_POST['uid'], $mysqli)['directory'];
				$path = "../users/$directory/{$_POST['uid']}.json";
				$jsonstr = file_get_contents($path);
				$o = json_decode($jsonstr, true);
				$o['spruch'] = $_POST['quote'];
				file_put_contents($path, json_encode($o, JSON_PRETTY_PRINT));
				echo json_encode(['spruch'=>$_POST['quote']], JSON_PRETTY_PRINT);
			}	
			catch (Exception $e){
				echo error('internalError', 500, $e->getMessage());
			}
		} else {
			echo error('clientError', 403, 'not logged in');
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
?>