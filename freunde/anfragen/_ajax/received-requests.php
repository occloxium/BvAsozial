<?php
	include_once '../../../includes/db_connect.php';
	include_once '../../../includes/functions.php';
	secure_session_start();
	if(login_check($mysqli) == true && isset($_GET['uid']) && $_GET['uid'] == $_SESSION['username']) : 		
		$requests = [];
		if(isset($_GET['amount'])){ 
			// fetch a limited amount of data
			$limit = intval($_GET['amount']);
			$stmt = $mysqli->prepare('SELECT von FROM anfragen WHERE an = ? LIMIT ?');
			$stmt->bind_param('si', $_GET['uid'], $limit);
			$stmt->execute();
			$stmt->bind_result($an);
			$stmt->store_result();
			while($stmt->fetch()){
				$requests[] = $an;
			}
			
		} else {
			// fetch all data
			$stmt = $mysqli->prepare('SELECT von FROM anfragen WHERE an = ?');
			$stmt->bind_param('s', $_GET['uid']);
			$stmt->execute();
			$stmt->bind_result($von);
			$stmt->store_result();
			while($stmt->fetch()){
				$requests[] = $von;
			}
		}
		$output = "";
		if(!empty($requests)):
			foreach($requests as $element){
				$fetched_user = getMinimalUser($element, $mysqli);
				$output = $output . '<li class="mdl-list__item">
															<span class="mdl-list__item-primary-content">
																<img class="mdl-list__item-icon request__user-avatar" src="/users/' . $fetched_user['directory'] . '/avatar.jpg">
																' . $fetched_user['name'] . '
															</span>
															<span class="mdl-list__item-secondary-action request__label--pending">
																<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon accept-request" data-requestor="' . $fetched_user['uid'] . '">
																	<i class="material-icons">person_add</i>
																</button>
															</span>
														</li>';
			}
		else : $output = '<li class="mdl-list__item">
												<span class="mdl-list__item-primary-content">
													Keine Anfragen vorhanden
												</span>
											</li>';
		endif;
		echo $output;
	else : echo error('clientError', 403, 'Forbidden');
	endif;
?>