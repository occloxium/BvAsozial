<?php
	include_once '../../../includes/db_connect.php';
	include_once '../../../includes/functions.php';
	
	secure_session_start();
	if(login_check($mysqli) == true){
		
		if(isset($_GET['friend'])){
			if(strlen($_GET['friend']) < 1){
				$output = "";
				$stmt = $mysqli->prepare('SELECT friend FROM freunde WHERE uid = ? ORDER BY friendsSince ASC');
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
				}
				echo $output;
				exit;
			}
			$output = "";
			$friends = getFriendsByName($_SESSION['username'], $_GET['friend'], $mysqli);
			if(!empty($friends)){
				foreach($friends as $friend){
				$fetched_user = getMinimalUser($friend, $mysqli);
				$output .= '<a class="seamless-anchor" href="/users/index.php/' . $fetched_user['uid'] . '/">
											<li class="mdl-list__item">
												<span class="inherit-flex mdl-list__item-primary-content">
													<img class="mdl-list__item-icon request__user-avatar" src="/users/' . $fetched_user['directory'] . '/avatar.jpg">
													<span>' . $fetched_user['name'] . '</span>
												</span>
												<span class="mdl-list__item-secondary-action request__label--pending">
													<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon unfriend" data-uid="' . $fetched_user['uid'] . '">
														<i class="material-icons">close</i>
													</button>
												</span>
											</li>
										</a>';
				}
			} else {
				$output = '<li class="mdl-list__item">
										<span class="mdl-list__item-primary-content">
											Keine Freunde mit dem Namen gefunden
										</span>
									</li>';
			}
			echo $output;
		} else {
			echo error('clientError', 400, 'Bad Request');
		}
	} else {
		echo error('clientError', 403, 'Forbidden');
	}
?>