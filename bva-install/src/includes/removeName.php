<?php 
	include_once 'db_connect.php';
	include_once 'functions.php';
    
    secure_session_start();
    if(!isset($_SESSION['username'])){
        error('clientError', 403, 'Forbidden');
        exit;
    }
	if(isset($_POST['username'], $_POST['name'], $_POST['directory'])){
		$_request = [
			"username" => $_POST['username'],
			"name" => $_POST['name'],
			"directory" => $_POST['directory']
		];
		$user = getUser($_request['username'], $mysqli);
		$directory = '../users/' . $user['directory'] . '/' . $user['uid'] . '.json';
		$json = json_decode(file_get_contents($directory), true);
        $list = $json['rufnamen'];
        $key = array_search($_request['name'], $list);
        if($key !== false){
            unset($list[$key]);
            // echo response object
            $html = rufnamenliste($user, $_SESSION['username'], $list);
            success(["html" => $html, "json"=>$list]);
            $json['rufnamen'] = $list;
        } else {
            $html = rufnamenliste($user, $_SESSION['username'], $list);
            success(["error" => "Konnte zu entfernenden Rufnamen nicht mehr in der Liste finden", "html" => $html]);
        }
		file_put_contents($directory, json_encode($json, JSON_PRETTY_PRINT));
        exit;
	} else {
		// refuse communication.
		error('clientError', 400, 'Bad Request');
        exit;
	}
?>
