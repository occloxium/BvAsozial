<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();
	if(login_check($mysqli) == true && $_SESSION['user']['is_admin'] && isset($_GET['_'])) :
		$uid = base64_decode($_GET['_']);
?>
<!DOCTYPE html>
<html>
	<head>
		<?php _getHead() ?>
	</head>
	<body>
		<div class="mdl-layout__container">
			<div class="layout-wrapper">
				<header class="layout__header layout__header--small mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
					<img class="logo prime" src="/img/logo-cropped.png">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Benutzer-Passwort ändern
						</p>
					</div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
            <div class="breadcrumb">
              <li class="breadcrumb__item">
                <a href="../../">Admin</a>
              </li>
              <li class="breadcrumb__item">
                <a href="../">Benutzer verwalten</a>
              </li>
              <li class="breadcrumb__item">
                Benutzer-Passwort ändern
              </li>
            </div>
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
                $('.mdl-button').detach();
                $('form').append($('<p>Passwort wurde geändert.</p>'));
                $('form').append($('<a></a>').addClass('mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect').attr('href','../').append($('<i></i>').addClass('material-icons').text('keyboard_arrow_left')).text('Zurück');
              } else {
								$('main').append($('<div></div>').addClass('mdl-card container mdl-color--white mdl-shadow--2dp').attr('response').append($('<pre></pre>').text(data)));
							}
						} catch (e) {
							console.log(data);
						}
					},
					error: function(data){
						console.log(data);
					}
				})
			});
		</script>
	</body>
</html>

<?php else : header('Location: ../'); exit; endif; ?>
