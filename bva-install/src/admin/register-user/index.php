<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	secure_session_start();
	if(login_check($mysqli) == true && $_SESSION['user']['is_admin']) :
?>
<!DOCTYPE html>
<html>
	<head>
		<?php _getHead('dashboard','admin'); ?>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
		  <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
				<?php _getNav('register-user'); ?>
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
		<script src="/js/registerUser.js"></script>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
<?php else : header("Location: ../"); exit; endif; ?>
