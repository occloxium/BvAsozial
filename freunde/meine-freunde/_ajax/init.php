<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
	if(login_check($mysqli)){
		$output = "";
		// fetch all data
		$stmt = $mysqli->prepare('SELECT friend FROM freunde WHERE uid = ? ORDER BY friendsSince DESC');
		$stmt->bind_param('s', $_SESSION['user']['uid']);
		$stmt->execute();
		$stmt->bind_result($von);
		$stmt->store_result();
		if($stmt->num_rows > 0){
			while($stmt->fetch()){
                $fetched_user = getMinimalUser($von, $mysqli);
                $output .= '<li class="mdl-list__item">
                                <a class="seamless-anchor mdl-list__item-primary-content" href="/users/index.php/' . $fetched_user['uid'] . '/">
                                    <span class="inherit-flex">
                                        <img class="mdl-list__item-icon request__user-avatar" src="/users/' . $fetched_user['uid'] . '/avatar.jpg">
                                        <span>' . $fetched_user['name'] . '</span>
                                    </span>
                                </a>
                                <span class="mdl-list__item-secondary-action request__label--pending">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon unfriend" data-uid="' . $fetched_user['uid'] . '">
                                        <i class="material-icons">close</i>
                                    </button>
                                </span>
                            </li>';
			}
		} else {
			$output .= '<p class="no-entries">
                    Momentan hast Du keine Freunde ;(
                  </p>
                  <p class="no-entries">
                    <b>Füge jetzt Freunde hinzu: </b><a href="../index.php">Freunde finden</a>
                  </p>';
		}
		echo $output;
	} else {
		echo error('clientError', 403, 'Forbidden');
	}
?>
