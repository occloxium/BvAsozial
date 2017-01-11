<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	secure_session_start();
	if(login_check($mysqli) == true && $_SESSION['user']['is_admin']) : header('Location: ../'); exit;
	else :
?>
<!doctype html>
<html>
	<head>
		<?php _getHead('admin.login', 'admin'); ?>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout">
      <main class="mdl-layout__content mdl-color--blue-grey-900">
        <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top mdl-color--grey-200">
					<form action="/includes/processLogin.php" method="post">
						<h1>Blick hinter die Kulissen</h1>
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
							<input class="mdl-textfield__input" type="text" id="uid" name="uid">
							<label class="mdl-textfield__label" for="uid">Admin-Key</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
							<input class="mdl-textfield__input" type="password" id="password" name="password">
							<label class="mdl-textfield__label" for="password">Passwort</label>
						</div>
						<button class="mdl-button mdl-color--white mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--primary">
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
		<script src="/admin/js/anmelden.js"></script>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
<?php endif; ?>
