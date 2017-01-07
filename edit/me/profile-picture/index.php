<?php
    include_once '../../../includes/db_connect.php';
    include_once '../../../includes/functions.php';

    secure_session_start();
    if (login_check($mysqli) == true) :
      $user = getUser($_SESSION{'username'}, $mysqli);
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
    <link rel="stylesheet" href="/css/edit.me.profile-picture.css">
		<script src="/js/jquery-2.1.4.min.js"></script>
	</head>
	<body>
		<div class="mdl-layout__container">
			<div class="layout-wrapper">
				<header class="layout__header layout__header--small mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Profilbild ändern
						</p>
					</div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
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
