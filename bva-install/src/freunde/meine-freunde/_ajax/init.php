<?php
	include_once '../../../includes/db_connect.php';
	include_once '../../../includes/functions.php';
	
	secure_session_start();
	if(login_check($mysqli) == true){
		$output = "";
		// fetch all data
		$stmt = $mysqli->prepare('SELECT friend FROM freunde WHERE uid = ? ORDER BY friendsSince DESC');
		$stmt->bind_param('s', $_SESSION['username']);
		$stmt->execute();
		$stmt->bind_result($von);
		$stmt->store_result();
		if($stmt->num_rows > 0){
			while($stmt->fetch()){
                $fetched_user = getMinimalUser($von, $mysqli);
                $output .= '<li class="mdl-list__item">
                                <a class="seamless-anchor mdl-list__item-primary-content" href="/users/index.php/' . $fetched_user['uid'] . '/">
                                    <span class="inherit-flex">
                                        <img class="mdl-list__item-icon request__user-avatar" src="/users/' . $fetched_user['directory'] . '/avatar.jpg">
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
			$output .= '<li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">
                                Momentan hast Du keine Freunde ;(<br>
                                <b>Füge jetzt Freunde hinzu</b><br>
                                <a href="../index.php">Freunde finden</a>
                            </span>
                        </li>';
		}
		echo $output;
	} else {
		echo error('clientError', 403, 'Forbidden');
	}
?>
