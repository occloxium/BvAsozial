<?php
	include_once '../includes/db_connect.php';
	include_once '../includes/functions.php';
	secure_session_start();
	if(login_check($mysqli) == true) : header('Location: ../');	exit;
	else :
?>
<!doctype html>
<html lang=en>
	<head>
		<meta http-equiv=Content-Type content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="utf-8">
		<meta property=og:locale content=de_DE />
		<title>BVASOZIAL &gt; Admin &gt; Login</title>
		<link rel="stylesheet" href="/css/bvasozial.mdl.min.css">

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

		<link rel="stylesheet" href="/css/sidewide.css">
		<link rel="stylesheet" href="/css/overview.css">
		<link rel="stylesheet" type="text/css" href="/admin/css/main.css">
		<link rel="stylesheet" type="text/css" href="/admin/css/login.css">
		<script src="/js/jquery-2.1.4.min.js"></script>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout">
      <main class="mdl-layout__content mdl-color--blue-grey-800">
        <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top mdl-color--grey-200" style="margin-top: 12em;">
			<form action="../includes/processLogin.php" method="post">
				<pre>BvAsozial &gt; Admin &gt; Login</pre>
				<h1>Blick hinter die Kulissen</h1>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input class="mdl-textfield__input" type="text" id="uid" name="username">
					<label class="mdl-textfield__label" for="uid">Admin-Key</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input class="mdl-textfield__input" type="password" id="password" name="password">
					<label class="mdl-textfield__label" for="password">Passwort</label>
				</div>
				<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--primary">
					Anmelden
				</button>
			</form>
		</div>
      </main>
    </div>
		<div class="mdl-snackbar mdl-js-snackbar">
			<div class="mdl-snackbar__text"></div>
			<button class="mdl-snackbar__action" type="button"></button>
		</div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
<?php endif; ?>
