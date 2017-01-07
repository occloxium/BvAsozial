<?php
	include_once '../../../includes/db_connect.php';
	include_once '../../../includes/functions.php';
	
	ini_set('display_errors', 1);

	secure_session_start();
	if(login_check($mysqli) == true) :
		$user = getUser($_SESSION['username'], $mysqli);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<title>BvAsozial</title>

		<meta name="mobile-web-app-capable" content="yes">
		<link rel="icon" sizes="192x192" href="/images/android-desktop.png">

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="BvAsozial">
		<link rel="apple-touch-icon-precomposed" href="/images/ios-desktop.png">

		<meta name="msapplication-TileImage" content="/images/touch/ms-touch-icon-144x144-precomposed.png">
		<meta name="msapplication-TileColor" content="#252830">

		<link rel="shortcut icon" href="/images/favicon.png">

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
		<link rel="stylesheet" href="/css/material-icons.css">

		<link rel="stylesheet" href="/css/bvasozial.mdl.min.css">
		<link rel="stylesheet" href="/css/sidewide.css">
		<link rel="stylesheet" href="/css/registration.css">
		<script src="/js/jquery-2.1.4.min.js"></script>
	</head>
	<body>
		<div class="mdl-layout__container">
			<div class="layout-wrapper">
				<header class="layout__header layout__header--small mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
					<img class="logo prime" src="/img/logo-cropped.png">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Eigene Fragen anpassen
						</p>
					</div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
						<form class="form--fill-wrapper" method="post" action="./uf.php">
							<p class="mdl-typography--title">Eigene Fragen</p>
							<p class="mdl-typography--body-1">Hier kannst du deine eigenen Fragen anpassen, solltest du dir vorher unsicher gewesen sein</p>
							<b class="">Wenn du vorher bereits beantwortete Fragen abwählst, verfallen deine Antworten für diese Fragen! Dieser Schritt kann nicht von uns rückgängig gemacht werden!</b>
							<div>&nbsp;</div><div>&nbsp;</div>
							<table class="fragenkatalog-table mdl-shadow--2dp" id="freundesfragen">				
								<?php
									$user_fragen = json_decode(file_get_contents("../../../users/{$user['directory']}/{$user['uid']}.json"), true);
									$obj = json_decode(file_get_contents('../../../registrieren/fragenkatalog.json'), true);
									echo "<tbody>";
									for($i = 0; $i < count($obj['eigeneFragen']); $i++) {
										$frage = $obj['eigeneFragen'][$i];
										$num = $i + 1;
										$found = false;
										foreach($user_fragen['eigeneFragen'] as $user_frage){
											if(stripos($frage, $user_frage['frage']) !== false){
												$found = true;
											}
										}
										echo '<tr>
														<td>
															<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="e-frage-' . $num .'">
																<input type="checkbox" data-category="eigeneFragen" id="e-frage-' . $num .'" class="mdl-checkbox__input" name="e-frage-' . $num .'" ' . ($found ? 'checked' : '') .  '>
															</label>
														</td>
														<td class="frage">' . $frage . '</td>
													</tr>';
									}
									echo "</tbody>";
								?>
							</table>
							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
								Änderungen speichern
							</button>
					</form>
					<a style="max-width: 240px; margin: 2em auto; display: block;" class="mdl-button mdl-js-button mdl-color--accent mdl-color-text--white mdl-js-ripple-effect" href="../../">
						Zurück zu meinen Fragen	
					</a>
					</div>
				</main>
			</div>
		</div>
		<div class="mdl-snackbar mdl-js-snackbar">
			<div class="mdl-snackbar__text"></div>
			<button class="mdl-snackbar__action" type="button"></button>
		</div>
		<script src="/js/eigeneFragen.edit.fragen.js"></script>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>

<?php else : header('Location: ../../../'); exit; 
			endif; ?>
