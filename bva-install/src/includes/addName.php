<?php 
	include_once 'db_connect.php';
	include_once 'functions.php';
    
    secure_session_start();
    if(!isset($_SESSION['username'])){
        error('clientError', 403, 'Forbidden');
        exit;
    }
	if(isset($_POST['for'], $_POST['name'], $_POST['postedBy'])){
		// Posted
		// get users profile .json file from his directory : directory request form database -> file_get_contents($dir) -> json_decode($jsonstr)
		
		$_request = [
			"for" => $_POST['for'],
			"name" => $_POST['name'],
			"postedBy" => $_POST['postedBy']
		];
		$user = getUser($_POST['for'], $mysqli);
		$directory = '../users/' . $user['directory'] . '/' . $user['uid'] . '.json';
		$json = json_decode(file_get_contents($directory), true);
		if(!isset($_POST['undo'])){
			// regular name post
			if(isFriendsWith($_POST['for'], $_POST['postedBy'], $mysqli)){
				if(!in_array(strtolower($_POST['name']), array_map('strtolower', $json['rufnamen']))){
					array_push($json['rufnamen'], $_POST['name']); // Success!
                    $html = rufnamenliste($user, $_SESSION['username'], $json['rufnamen']);
                    success(["html" => $html, "new_name" => $_request['name']]);
				} else {
					error('clientError', 400, 'Bad Request', ["Der angegebene Rufname ist ein Dublikat und bereits vorhanden"]);
				}
			} else {
				error('clientError', 400, 'Bad Request', ["Du bist nicht mit dem Benutzer befreundet"]);
			}
		} else {
			// undo post
			// find requested 'name' in 'rufnamen'-array and remove the entry.
			$list = $json['rufnamen'];
			$key = array_search($_POST['name'], $list);
			if($key !== false){
				unset($list[$key]);
				// echo response object
                $html = rufnamenliste($user, $_SESSION['username'], $list);
				success(["html" => $html, "request" => $_request]);
				$json['rufnamen'] = $list;
			} else {
                $html = rufnamenliste($user, $_SESSION['username'], $list);
				success(["html" => $html, "request" => $_request, "error" => "Konnte zu widerrufenden Rufnamen nicht mehr in der Liste finden"]);
			}
		}
		file_put_contents($directory, json_encode($json, JSON_PRETTY_PRINT));
        exit;
	} else {
		// refuse communication.
		error('clientError', 400, 'Bad Request');
        exit;
	}
?>
