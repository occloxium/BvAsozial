<?php
include_once 'db_connect.php';
include_once 'functions.php';

secure_session_start();

if(login_check($mysqli) && $_SESSION['username'] == $_POST['uid']){
	if(isset($_POST['fragen'])){
		foreach($_POST['fragen'] as $frage){
			$user = getMinimalUser($frage['for'], $mysqli);
			if(isFriendsWith($user['uid'], $_POST['uid'], $mysqli)) : 
				$obj = json_decode(file_get_contents("../users/{$user['directory']}/{$user['uid']}.json"), true);

					$frage['frageID'] = intVal($frage['frageID']) - 1;
					if($frage['type'] == 'freundesfragen'){
						$obj['freundesfragen'][$frage['frageID']]['antworten'][$frage['von']] = $frage['antwort'];
                    } else {
                        if(strlen($frage['antwort']) > 1){
                            $obj['eigeneFragen'][$frage['frageID']]['beantwortet'] = true;
                        } else {
                            $obj['eigeneFragen'][$frage['frageID']]['beantwortet'] = false;
                        }
						$obj['eigeneFragen'][$frage['frageID']]['antwort'] = $frage['antwort'];
					}
					file_put_contents("../users/{$user['directory']}/{$user['uid']}.json", json_encode($obj, JSON_PRETTY_PRINT));
			else : 
				echo error('internalError', 500, 'Not relationship found');
				return;
			endif;
		}	
		echo json_encode([
					"success" => true,
					"message" => "Frage beantwortet",
					"code" => 200
		], JSON_PRETTY_PRINT);
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
} else {
	echo error('clientError', 403, 'Forbidden');
}
?>