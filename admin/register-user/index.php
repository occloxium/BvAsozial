<?php
	include_once '../includes/db_connect.php';
	include_once '../includes/functions.php';

	secure_session_start();
	if(login_check($mysqli) == true):
?>
<!DOCTYPE html>
<html lang="de">
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

		<link rel="stylesheet" href="/css/bvasozial.mdl.src.css">
		<link rel="stylesheet" href="/css/sidewide.css">
		<link rel="stylesheet" href="/css/overview.css">
		<link rel="stylesheet" href="../css/admin.css">
		<link rel="stylesheet" href="../css/registerUser.css">

		<script src="/js/jquery-2.1.4.min.js"></script>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
		  <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
				<?php get_nav('register-user'); ?>
		  </div>
		  <main class="mdl-layout__content mdl-color--grey-100">
			  <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top">
				<p class="mdl-typography--headline">Benutzer einladen</p>
				<form class="register_form" action="../includes/registerUser.php" method="post">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input class="mdl-textfield__input" type="text" name="name" id="name">
						<label class="mdl-textfield__label" for="name">Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="uidContainer">
						<input class="mdl-textfield__input" type="text" name="uid" id="uid">
						<label class="mdl-textfield__label" for="name">Benutzername</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" class="mdl-textfield__input" name="email" id="email">
						<label class="mdl-textfield__label" for="email">E-Mail-Adresse</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty" id="passwordContainer">
						<input class="mdl-textfield__input" type="text" name="password" id="password" value="">
						<label class="mdl-textfield__label" for="password">Passwort</label>
					</div>
					<div class="mdl-tooltip" for="passwordContainer">
						Zufälliges Passwort. Kann später vom Benutzer geändert werden
					</div>
					<button class="mdl-button mdl-js-button mdl-js-ripple-effect" type="button">
						Einladung erstellen
					</button>
				</form>
			  </div>
		  </main>
		</div>
		<script src="../js/registerUser.js"></script>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
<?php
	else : header("Location: ../login/"); exit;
	endif;
?>
