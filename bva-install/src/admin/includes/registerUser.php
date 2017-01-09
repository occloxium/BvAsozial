<?php
	include_once 'db_connect.php';
	include_once 'functions.php';
	
	ini_set('display_errors',1);

	$user = createUserObject($_POST);
	
	

	if($user != null){
		if(!userExists($user['uid'], $mysqli)){
			if(createNewUser($user, $mysqli) == true){
				include_once '../register-user/mail.php';
				
				$e_uid = $user['uid'];
				$e_pw = $_POST['password'];
				$e_name = $user['name'];
				
				$email = include_once "../register-user/html.mail.php";
				
				echo json_encode([
					"success" => true,
					"email" => $email
				]);
			} else {
				echo false;
			}
		} else {
			echo error('clientError', 400, 'Benutzer existiert bereits');
		}
	}

	function createNewUser($user, $mysqli){
		try {
			$directory = createUserDirectory($user['uid'], true);
			if($directory !== false){
				$user['directory'] = $user['uid'];
				$user['registered_since'] = date("Y-m-d");
				if(addUserToDatabase($user, $mysqli) == false){
					throw new Exception('Unable to add user to database');
				}
			} else {
				throw new Exception("Unable to create directory");
			}
			//echo "Erfolg: " . json_encode($user, JSON_PRETTY_PRINT);
			return true;
		} catch(Exception $e) {
			if(undoChanges($user['uid'], $mysqli)){
				echo error('internalError', 500, 'Changes were successfully undone');
			} else {
				echo error('internalError', 500, 'Unable to rewind changes');
			}
			return false;
		}
	}
	
	function undoChanges($username, $mysqli){
		// undo changes to file system
		try {
			$path = $_SERVER['DOCUMENT_ROOT'] . "users/$username/";
			$files = glob($path);
			foreach($files as $file){ // iterate files
				if(is_file($file))
					unlink($file); // delete file
			}
			rmdir($path);
			if(!rmdir($path)){
				throw new Exception("Unable to rewind file system changes", 501);
			}
			// undo changes to database
			$query = "DELETE FROM ausstehende_einladungen WHERE uid = '$username'";
			if(!($result = $mysqli->query($query))){
				throw new Exception("Unable to alter database", 502);
			}
			return true;
		} catch (Exception $e){
			if($e->getCode() !== 501 || $e->getCode() !== 502){
				echo error('internalError', 500, "Could not undo changes to either filesystem of database");
			} else {
				echo error('internalError', 500, $e->getMessage());
			}
		}
	}
	function addUserToDatabase($user, $mysqli){
		if(!isset($user['name'], $user['email'], $user['uid'], $user['password'], $user['directory'])){
			echo error('clientError', 400, 'Not all required information set');
		} else {
			try {
				if($stmt = $mysqli->prepare("INSERT INTO ausstehende_einladungen (name, uid, password, directory, email) VALUES (?,?,?,?,?)")){
					$stmt->bind_param("sssss", $user['name'], $user['uid'], $user['password'], $user['directory'], $user['email']);
					$stmt->execute();
					if($stmt->errno != 0){
						throw new Exception($stmt->error);
					}
				}
				else {
					throw new Exception($mysqli->error);
				}
				return true;
			} catch(Exception $e){
				if(undoChanges($user['uid'], $mysqli)){
					echo error('internalError', 500, $e->getMessage() . "; Changes were undone");
				} else {
					echo error('internalError', 500, $e->getMessage() . "; Changes could not be undone");
				}
				return false;
			}
		}
	}
	function createUserDirectory($username, $override = true){
		$path = "../../users/$username/";
		try {
			if(mkdir($path)){
				// create initial versions of user files
				$json = [
					"spruch" => "",
					"rufnamen" => [],
					"freundesfragen" => [],
					"eigeneFragen" => []
				];
				$jsonfile = json_encode($json, JSON_PRETTY_PRINT);
				if(!file_put_contents($path . $username . ".json", $jsonfile)){
					throw new Exception("Error on user file creation");
				}
				// Copy default avatar from /img/avatar.jpg
				if(!copy($_SERVER['DOCUMENT_ROOT'] . "/img/avatar.jpg", $path . "avatar.jpg")){
					throw new Exception("Error on default avatar creation");
				}
				//echo json_encode(["path" => $path], JSON_PRETTY_PRINT);
				return $path;
				
			} else {
				echo $path;
				throw new Exception("Error on creation");
			}
		} catch (Exception $e){
			if($override){
				// clear directory
				$files = glob($path);
				foreach($files as $file){ // iterate files
					if(is_file($file))
						unlink($file); // delete file
				}
				rmdir($path);
				//createUserDirectory($username, false);				
			}
			echo error('internalError', 500, $e->getMessage());
		}
		return false;
	}
	function createUserObject($obj){
		if(isset($obj['name'], $obj['email'], $obj['uid'], $obj['password'])){
			
			return [
				"name" => $obj['name'],
				"uid" => $obj['uid'],
				"password" => sha1($obj['password']),
				"email" => $obj['email'],
				"directory" => $obj['uid'],
				"registered_since" => date("Y-m-d")
			];
		} else {
			echo error('clientError', 400, 'Not all required information set or POST corrupted');	
			return null;
		}
	}
	
	
?>