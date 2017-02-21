<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
	if(login_check($mysqli)){
		$output = "";
		// fetch all data
		$stmt = $mysqli->prepare('SELECT uid FROM person');
		$stmt->execute();
		$stmt->bind_result($uid);
		$stmt->store_result();
		if($stmt->num_rows > 0){
			while($stmt->fetch()){
        $user = getMinimalUser($uid, $mysqli);
        $output .= '<li class="mdl-list__item">
                      <a class="seamless-anchor mdl-list__item-primary-content" href="/users/index.php/' . $user['uid'] . '/">
                        <span class="inherit-flex">
                          <img class="mdl-list__item-icon request__user-avatar" src="/users/' . $user['uid'] . '/avatar.jpg">
                          <span>' . $user['name'] . '</span>
                        </span>
                      </a>
                      <span class="mdl-list__item-secondary-action request__label--pending">
                        <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--white mdl-button--primary edit" href="/mod/edit/?_='.$uid.'">
                          Bearbeiten
                        </a>
                      </span>
                    </li>';
			}
		} else {
			$output .= '<p class="no-entries">
                    Es konnten keine Benutzer gefunden werden. Wenn du ein Administrator bist, füge schnell Personen hinzu!
                  </p>';
		}
		echo success(["output" => $output]);
	} else {
		echo error('clientError', 403, 'Forbidden');
	}
?>
