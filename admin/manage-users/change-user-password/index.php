<?php
	include_once '../../includes/db_connect.php';
	include_once '../../includes/functions.php';

	secure_session_start();
	if(login_check($mysqli) == true && isset($_GET['_'])) :
		$uid = base64_decode($_GET['_']);
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
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

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
							Benutzer-Passwort ändern
						</p>
					</div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
						<form action="./cpw.php">
							<input type="hidden" name="step" value="0">
							<p class="mdl-typography--headline">Passwort neu setzen...</p>
							<p class="mdl-typography--body-1">
								Im Falle von Passwort-Verlust kannst du hiermit ein neues Passwort für den Benutzer setzen. Ein neues zufälliges wird dir direkt vorgeschlagen
							</p>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="text" class="mdl-textfield__input" value="<?php echo $uid ?>" id="uid" name="uid" readonly>
								<label for="uid" class="mdl-textfield__label">Benutzername</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="text" class="mdl-textfield__input" id="password" name="password">
								<label for="password" class="mdl-textfield__label">Neues Passwort</label>
							</div>
							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect" type="button">
								Passwort setzen
							</button>
						</form>
					</div>
				</main>
			</div>
		</div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
		<script>
			$('input#password').val(Math.random().toString(36).substr(2,8));
			$('.mdl-button').click(function(){
				$('#response').detach();
				$.ajax({
					data: $('form').serialize(),
					method: 'post',
					url: 'cpw.php',
					success: function(data){
						try {
							var obj = JSON.parse(data);
							if(obj.success){
								$('main').append($('<div></div>').addClass('mdl-card container mdl-color--white mdl-shadow--2dp').attr('response').append(obj.html));
							} else {
								$('main').append($('<div></div>').addClass('mdl-card container mdl-color--white mdl-shadow--2dp').attr('response').append($('<pre></pre>').text(data)));
							}
						} catch (e) {
							debugger;
							console.log(data);
						}
					},
					error: function(data){
						debugger;
						console.log(data);
					}
				})
			});
		</script>
	</body>
</html>

<?php else : header('Location: ../../login/'); exit; 
			endif; ?>
