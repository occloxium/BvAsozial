<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	secure_session_start();
	if(login_check($mysqli) == true && $_SESSION['user']['is_admin'] && isset($_GET['_'])) :
    $user = getUser(base64_decode($_GET['_']), $mysqli);
?>
<!DOCTYPE html>
<html>
	<head>
		<?php _getHead(); ?>
	</head>
	<body>
		<div class="mdl-layout__container">
			<div class="layout-wrapper">
				<header class="layout__header layout__header--small mdl-color--accent mdl-color-text--blue-grey-50">
					<img class="logo prime" src="/img/logo-cropped.png">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Moderator suspendieren
						</p>
					</div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
            <div class="breadcrumb">
              <li class="breadcrumb__item">
                <a href="../../../">Admin</a>
              </li>
              <li class="breadcrumb__item">
                <a href="../../">Benutzer verwalten</a>
              </li>
              <li class="breadcrumb__item">
                <a href="../">Moderatoren verwalten</a>
              </li>
              <li class="breadcrumb__item">
                Moderator suspendieren
              </li>
            </div>
						<form action="./rmu.php">
							<p class="mdl-typography--headline">Moderator suspendieren</p>
							<p class="mdl-typography--body-1">
                Wenn ein Moderator nicht seinen Dienst erfüllt, seine Macht missbraucht oder andere Dinge anstellt, so kann er von seiner Tätigkeit suspendiert werden. <br />
								Bist du dir sicher, dass du den Moderator<br /><b><?php echo $user['name']?></b><br />final suspendieren möchtest?<br /> <small>Das kann nur mit Datenbankzugriff rückgängig gemacht werden.</small>
							</p>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="text" class="mdl-textfield__input" value="<?php echo $user['uid'] ?>" id="uid" name="uid" readonly>
								<label for="uid" class="mdl-textfield__label">Benutzername</label>
							</div>
							<button class="mdl-button mdl-js-button mdl-color--accent mdl-color-text--white mdl-js-ripple-effect" type="button">
								Suspendieren
							</button>
						</form>
					</div>
				</main>
			</div>
		</div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
		<script>
			$('.mdl-button').click(function(){
				$('#response').detach();
				$.ajax({
					data: $('form').serialize(),
					method: 'post',
					url: 'sum.php',
					success: function(data){
						try {
							var obj = JSON.parse(data);
							if(obj.success){
                $('form .mdl-button').detach();
                $('form').append($('<p>Moderator wurde suspendiert.</p>'));
                $('form').append($('<a></a>').addClass('mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect').attr('href','../').prepend($('<i></i>').addClass('material-icons').text('keyboard_arrow_left')).text('Zurück'));
								$('main').append($('<div></div>').addClass('mdl-card container mdl-color--white mdl-shadow--2dp').attr('response').append(obj.html));
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
