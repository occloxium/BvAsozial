<?php
  /**
   *  Wrapper for the user search
   *  @author Alexander Bartolomey | 2017
   *  @package BvAsozial 1.2
   */

  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
  if(login_check($mysqli)){
		if(isset($_POST['search'])){
      $found = [];
      $users = getUsers($mysqli);
      if(strlen($_POST['search']) > 0)
        while($row = $users->fetch_assoc()){
          if(stripos($row['name'], $_POST['search']) !== false){
            // search name appears somewhere in a users' full name
            $found[] = $row;
          }
        }
      else {
        $found = $users;
      }
      $output = "";
			if(!empty($found)){
        foreach($found as $person){
          $output .= '<li class="mdl-list__item">
                        <a class="seamless-anchor mdl-list__item-primary-content" href="/users/index.php/' . $person['uid'] . '/">
                          <span class="inherit-flex">
                            <img class="mdl-list__item-icon request__user-avatar" src="/users/' . $person['uid'] . '/avatar.jpg">
                            <span>' . $person['name'] . '</span>
                          </span>
                        </a>
                        <span class="mdl-list__item-secondary-action request__label--pending">
                          <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--white mdl-button--primary edit" href="/mod/edit/?_='.$person['uid'].'">
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
			echo error('clientError', 400, 'Bad Request');
		}
	} else {
		echo error('clientError', 403, 'Forbidden');
	}
?>
