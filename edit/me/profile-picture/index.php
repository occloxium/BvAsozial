<?php
    require('constants.php');
    require(ABS_PATH . INC_PATH . 'functions.php');

    secure_session_start();
    if (login_check($mysqli) == true) :
      $user = getUser($_SESSION['user']['uid'], $mysqli);
?>
<!DOCTYPE html>
<html>
	<head>
		<?php _getHead(); ?>
	</head>
	<body>
		<div class="mdl-layout__container">
			<div class="layout-wrapper">
				<header class="layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Profilbild ändern
						</p>
					</div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
            <div class="breadcrumb">
              <li class="breadcrumb__item">
                <a href="/">BvAsozial</a>
              </li>
              <li class="breadcrumb__item">
                Profilbild ändern
              </li>
            </div>
            <p class="mdl-typography--body-1 mdl-typography--text-center">
              Das ist dein aktuelles Profilbild
            </p>
            <div class="image-block">
              <img class="avatar__image" src="<?php echo '/users/' . $user['directory'] . '/avatar.jpg'?>">
            </div>
            <p class="mdl-typography--body-1 mdl-typography--text-center">
              Um es zu ändern, lade zuerst ein neues hoch!
            </p>
            <form action="submit.php" method="post" enctype="multipart/form-data">
              <input hidden name="MAX_FILE_SIZE" value="100000000">
              <input hidden type="file" accept="image/*" class="file" name="img">
              <button class="mdl-button mdl-js-ripple-effect mdl-js-button" type="button">Bild auswählen</button>
            </form>
					</div>
				</main>
			</div>
		</div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
		<script src="/js/edit.me.profile-picture.js"></script>
	</body>
</html>

<?php
  else :
    header('Location: ../../');
    exit;
  endif;
?>
