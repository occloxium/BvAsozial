<?php
	include_once '../../../includes/db_connect.php';
	include_once '../../../includes/functions.php';

	define("DEBUG", false);

	secure_session_start();

	if(login_check($mysqli) == true){
		$user = getUser($_SESSION['username'], $mysqli);
		if(isset($_POST)){
			$obj = json_decode(file_get_contents('../../../registrieren/fragenkatalog.json'), true);
			$userfile = json_decode(file_get_contents("../../../users/{$user['directory']}/{$user['uid']}.json"), true);
			$new_userfile = [];
			foreach($_POST as $key=>$element){
				if(preg_match("/^e-frage-[1-9][0-9]*/", $key)){
					// Eigene Frage
					$found = false;
					$pos = 0;
					$num = intVal(substr($key,8));
					foreach($userfile['eigeneFragen'] as $key2=>$user_frage){
						if(stripos($user_frage['frage'], $obj['eigeneFragen'][$num - 1]) !== false){
							$found = true;
							(DEBUG ? echo "Match found: {$obj['eigeneFragen'][$num - 1]}<br>");
							$pos = $key2;
						}
					}
					if($found){
						$new_userfile[] = $userfile['eigeneFragen'][$pos];
						(DEBUG ? echo "Existing element appended: {$userfile['eigeneFragen'][$pos]['frage']}<br><br>");
					} else {
						$new_obj = [
							"frage" => $obj['eigeneFragen'][$num - 1],
							"antwort" => ""
						];
						$new_userfile[] = $new_obj;
						(DEBUG ? echo "New element appended: {$new_obj['frage']}<br><br>");
					}
				}
			}
			$userfile['eigeneFragen'] = $new_userfile;
			(DEBUG ? echo "<pre>" . json_encode($userfile, JSON_PRETTY_PRINT) . "<pre>");
			if(file_put_contents("../../../users/{$user['directory']}/{$user['uid']}.json", json_encode($userfile, JSON_PRETTY_PRINT)) > 0){
				//header("Location: ../../");
				//echo success(["message" => "Fragen geändert"]);
			} else {
				echo error("internalError", 500, "Unable to write to file. No changes were made. If you receive this error as a common user, please report it to a Alex");
			}
		} else {
			echo error('clientError', 400, 'Bad Request');
		}
	} else {
		echo error('clientError', 403, 'Forbidden');
	}