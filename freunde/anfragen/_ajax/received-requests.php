<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');
  secure_session_start();
	if(login_check($mysqli) && isset($_GET['uid']) && $_GET['uid'] == $_SESSION['user']['uid']) :
		$requests = [];
		if(isset($_GET['amount'])){
			// fetch a limited amount of data
			$limit = intval($_GET['amount']);
			$stmt = fetch("von", "anfragen", "an", $_GET['uid'], $mysqli, "LIMIT $limit");
			while($stmt->fetch()){
				$requests[] = $an;
			}
		} else {
			// fetch all data
			$stmt = fetch("von", "anfragen", "an", $_GET['uid'], $mysqli);
			while($stmt->fetch()){
				$requests[] = $von;
			}
		}
		$output = "";
		if(!empty($requests)) :
			foreach($requests as $element){
        if(is_visible($element, $_SESSION['user']['uid'], $mysqli)){
          $fetched_user = getMinimalUser($element, $mysqli);
  				$output .= '<li class="mdl-list__item">
  											<span class="mdl-list__item-primary-content">
  												<img class="mdl-list__item-icon request__user-avatar" src="/users/data/' . $fetched_user['uid'] . '/avatar.jpg">
  												' . $fetched_user['name'] . '
  											</span>
  											<span class="mdl-list__item-secondary-action request__label--pending">
  												<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon accept-request" data-requestor="' . $fetched_user['uid'] . '">
  													<i class="material-icons">person_add</i>
  												</button>
  											</span>
  										</li>';
        }
			}
      echo $output;
		else :
      echo '<p class="no-entries">Keine Anfragen vorhanden</p>';
		endif;

	else : echo error('clientError', 403, 'Forbidden');
	endif;
?>
