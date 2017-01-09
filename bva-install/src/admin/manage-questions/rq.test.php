<?php
	include_once '../includes/db_connect.php';
	include_once '../includes/functions.php';
	define("DEBUG", false);
	secure_session_start();
	$_POST['id'] = "ceefd1fe-1-27";
	if(isset($_POST['id'])){
		if(login_check($mysqli)){
          $fragenkatalog = json_decode(file_get_contents("../../registrieren/fragenkatalog.json"), true);
					if(DEBUG){
						echo "<pre>" . json_encode($fragenkatalog, JSON_PRETTY_PRINT) . "</pre>";
						echo "<br>";
					}
					$id = $_POST['id'];
					$type = substr($id, 9, 1);
					$frage = substr($id, 11);
					$id = intval($frage) - 1;
          try {
						if($type == 1){
							//freundesfragen
							echo (DEBUG ? $fragenkatalog['freundesfragen'][$id] : null);
							unset($fragenkatalog['freundesfragen'][$id]);
						} else {
							//eigeneFragen
							echo (DEBUG ? $fragenkatalog['eigeneFragen'][$id] : null);
							unset($fragenkatalog['eigeneFragen'][$id]);
						}
						if(DEBUG){
							echo "<br>";
							echo "<pre>" . json_encode($fragenkatalog, JSON_PRETTY_PRINT) . "</pre>";
							echo "<br>";
						}
						if(file_put_contents("../../registrieren/fragenkatalog.json", json_encode($fragenkatalog, JSON_PRETTY_PRINT)) > 0){
							echo success(['id' => $_POST['id']]);
						} else {
							throw new Exception("Could not write to file");
						}
          } catch(Exception $e) {
            echo error('internalError', 500, $e->message);
          }
		} else {
			echo error('clientError', 403, 'Forbidden');
		}
	} else {
		echo error('clientError', 400, 'Bad Request');
	}
?>
