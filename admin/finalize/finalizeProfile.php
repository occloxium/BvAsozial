<?php
	include_once '../includes/db_connect.php';
	include_once '../includes/functions.php';

	if(isset($_POST['uid'])){
		if(isUser($_POST['uid'], $mysqli)){
			$uid = $_POST['uid'];
			$query = "INSERT INTO fertig (uid, finalized) VALUES (?, ?)";
			if($stmt = $mysqli->prepare($query)){
				$stmt->bind_param("si", $uid, 1);
				$stmt->execute();
				if($stmt->affected_rows == 1){
					echo success(["message"=>"Benutzer finalisiert"]);
				} else {
					echo error("internalError", 500, $stmt->error);
				}
			} else {
				echo error("internalError", 500, $mysqli->error);
			}		
		} else {
			echo error("internalError", 500, "Could not find user");
		}
	} else {
		echo error("clientError", 400, "Bad Request");
	}
?>