<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';

secure_session_start();

if(login_check($mysqli) == true) :
	$user = getUser($_SESSION['user']['uid'], $mysqli);
?>
	<!-- Blank HTML Preset -->
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

		<link rel="stylesheet" href="/css/bvasozial.mdl.min.css">
		<link rel="stylesheet" href="/css/sidewide.css">
		<link rel="stylesheet" href="/css/starterkit.css">

		<script src="/js/jquery-2.1.4.min.js"></script>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
			<header class="header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">Starterkits</span>
        </div>
      </header>
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50 mdl-layout--fixed-drawer">
				<header class="drawer-header">
          <img class="avatar" src="<?php echo '/users/' . $user['directory'] . '/avatar.jpg'?>">
					<div>
							<span><?php echo $user['name']; ?></span>
							<span><?php echo $user['email']; ?></span>
					</div>
        </header>
			 	<nav class="navigation mdl-navigation mdl-color--blue-grey-900">
					<a class="mdl-navigation__link" href="/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Übersicht</a>
					<a class="mdl-navigation__link" href="/users/me/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">face</i>Mein Profil</a>
					<a class="mdl-navigation__link" href="/freunde/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">people</i>Freunde finden</a>
					<a class="mdl-navigation__link" href="/freunde/anfragen/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">person_add</i>Freundschafts-<br>anfragen</a>
					<a class="mdl-navigation__link" href="/fragen/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">question_answer</i>Fragen</a>
					<a class="mdl-navigation__link" href="/logout/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">exit_to_app</i>Abmelden</a>
					<div class="mdl-layout-spacer"></div>
					<a class="mdl-navigation__link" href="/support/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
        </nav>
      </div>
      <main class="mdl-layout__content mdl-color--grey-100">
				<div class="container mdl-color--white mdl-shadow--2dp">
					<form action="uf.php" enctype="multipart/form-data" method="post">
						<table>
							<tbody>
								<tr>
									<td>
										<input accept="image/*" hidden type="file" name="1" class="image-input" id="i1">
										<div class="image-preview" for="1"></div>
									</td>
									<td>
										<input accept="image/*" hidden type="file" name="2" class="image-input" id="i2">
										<div class="image-preview" for="2"></div>
									</td>
								</tr>
								<tr>
									<td>
										<input accept="image/*" hidden type="file" name="3" class="image-input" id="i3">
										<div class="image-preview" for="3"></div>
									</td>
									<td>
										<input accept="image/*" hidden type="file" name="4" class="image-input" id="i4">
										<div class="image-preview" for="4"></div>
									</td>
								</tr>
							</tbody>
						</table>
						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white">
							Bilder hochladen &amp; abspeichern
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
		<script src="/js/starterkit.js"></script>
	</body>
</html>
<?php else : header('Location: ../'); exit;
			endif; ?>
