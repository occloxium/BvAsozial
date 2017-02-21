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
		<?php _getHead('admin'); ?>
	</head>
	<body>
		<div class="mdl-layout__container">
			<div class="layout-wrapper">
				<header class="layout__header layout__header--small mdl-color--accent mdl-color-text--blue-grey-50">
					<img class="logo prime" src="/img/logo-cropped.png">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Einladung entfernen
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
                <a href="../">Einladungen verwalten</a>
              </li>
              <li class="breadcrumb__item">
                Einladung entfernen
              </li>
            </div>
						<form action="./cpw.php">
							<input type="hidden" name="step" value="0">
							<p class="mdl-typography--headline">Einladung entfernen...</p>
							<p class="mdl-typography--body-1">
								Bist du dir sicher, dass du die Einladung des Benutzers <b><?php echo $uid?></b> vollständig aus dem System entfernen möchtest?
							</p>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="text" class="mdl-textfield__input" value="<?php echo $uid ?>" id="uid" name="uid" readonly>
								<label for="uid" class="mdl-textfield__label">Benutzername</label>
							</div>
              <button class="mdl-button mdl-js-button mdl-color--accent mdl-color-text--white mdl-js-ripple-effect" type="button">
                Entfernen
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
					url: 'rmi.php',
					success: function(data){
						try {
							var obj = JSON.parse(data);
							if(obj.success){
								//$('main').append($('<div></div>').addClass('mdl-card container mdl-color--white mdl-shadow--2dp').attr('response').append(obj.html));
								location.replace("../");
							} else {
								//$('main').append($('<div></div>').addClass('mdl-card container mdl-color--white mdl-shadow--2dp').attr('response').append($('<pre></pre>').text(data)));
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
