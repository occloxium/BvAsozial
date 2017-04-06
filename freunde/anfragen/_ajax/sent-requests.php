<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');
  secure_session_start();
	if(login_check($mysqli) && isset($_GET['uid']) && $_GET['uid'] == $_SESSION['user']['uid']) :
		$requests = [];
		if(isset($_GET['amount'])){
			// fetch a limited amount of data
			$limit = intval($_GET['amount']);
			$stmt = $mysqli->prepare('SELECT an FROM anfragen WHERE von = ? LIMIT ?');
			$stmt->bind_param('si', $_SESSION['user']['uid'], $limit);
			$stmt->execute();
			$stmt->bind_result($an);
			$stmt->store_result();
			while($stmt->fetch()){
				$request[] = $an;
			}
		} else {
			// fetch all data
			$stmt = $mysqli->prepare('SELECT an FROM anfragen WHERE von = ?');
			$stmt->bind_param('s', $_SESSION['user']['uid']);
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
				$output .= '<li class="mdl-list__item request mdl-list__item--two-line">
											<a href="/users/index.php/' . $fetched_user['uid'] . '" class="mdl-list__item-primary-content">
							            <img class="mdl-list__item-icon request__user-avatar" src="/users/' . $fetched_user['uid'] . '/avatar.jpg">
                          <span>'. $fetched_user['name'] . '</span>
                          <span class="mdl-list__item-sub-title">Warte auf Annahme...</span>
											</span>
										</li>';
			}
			echo $output;
		else :
			echo '<p class="no-entries">Keine Anfragen vorhanden</p>';
		endif;
	else : echo error('clientError', 400, 'Bad Request');
	endif;
?>
