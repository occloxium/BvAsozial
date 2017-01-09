<?php
	include_once '../../../includes/db_connect.php';
	include_once '../../../includes/functions.php';
	secure_session_start();
	if(login_check($mysqli) == true && isset($_GET['uid']) && $_GET['uid'] == $_SESSION['username']) : 		
		$requests = [];
		if(isset($_GET['amount'])){ 
			// fetch a limited amount of data
			$limit = intval($_GET['amount']);
			$stmt = $mysqli->prepare('SELECT an FROM anfragen WHERE von = ? LIMIT ?');
			$stmt->bind_param('si', $_SESSION['username'], $limit);
			$stmt->execute();
			$stmt->bind_result($an);
			$stmt->store_result();
			while($stmt->fetch()){
				$request[] = $an;
			}
			
		} else {
			// fetch all data
			$stmt = $mysqli->prepare('SELECT an FROM anfragen WHERE von = ?');
			$stmt->bind_param('s', $_SESSION['username']);
			$stmt->execute();
			$stmt->bind_result($an);
			$stmt->store_result();
			while($stmt->fetch()){
				$requests[] = $an;
			}
		}
		$output = "";
		if(!empty($requests)) :
			foreach($requests as $element){
				$fetched_user = getMinimalUser($element, $mysqli);
				$output .= '<li class="mdl-list__item request">
															<span class="mdl-list__item-primary-content">
																<a href="/users/index.php/' . $fetched_user['directory'] . '">
																<img class="mdl-list__item-icon request__user-avatar" src="/users/' . $fetched_user['directory'] . '/avatar.jpg">' . $fetched_user['name'] . '
																</a>
															</span>
															<span class="mdl-list__item-secondary-action request__label--pending">
																Warte auf Annahme...
															</span>
														</li>';
			}	
			echo $output;
		else : 
			echo '<li class="mdl-list__item">
								<span class="mdl-list__item-primary-content">
									Keine Anfragen vorhanden
								</span>
							</li>';
		endif;
	else : echo error('clientError', 400, 'Bad Request');
	endif;
?>