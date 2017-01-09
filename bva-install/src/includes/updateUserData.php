<?php
	// 1. Validiere $_POST
	// Prüft, ob alle Form-Variablen gesetzt sind. Andernfalls ist die Anfrage nicht gültig
	include_once 'db_connect.php';
	include_once 'functions.php';
	if(isset($_POST['name'], $_POST['email'], $_POST['uid'])) :
		
		// Query SQL für UPDATE
		if($stmt = $mysqli->prepare("UPDATE person SET name = ? WHERE uid = ?;")){
			$stmt->bind_param('ss', $_POST['name'], $_POST['uid']);
			$stmt->execute();
			if($stmt->affected_rows == 1){
				echo 0;
			} else {
				echo error('internalError', 500, $stmt->error);
			}
		} else {
			echo error('internalError', 500, $mysqli->error);
		}
	else : 
		echo error('clientError', 400, 'Bad Request');
	endif;
?>