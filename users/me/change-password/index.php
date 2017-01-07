<?php
	include_once '../../../includes/db_connect.php';
	include_once '../../../includes/functions.php';
	secure_session_start();
	$_SESSION['username'] = 'a_barto';
	if(isset($_SESSION['username'], $_POST['oldpassword'], $_POST['password'])) :	?>
	<!doctype html>
	<html>
		<head>
			<meta charset="utf-8">
			<meta name="description" content="">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
			<title>BvAsozial</title>

			<meta name="mobile-web-app-capable" content="yes">
			<link rel="icon" sizes="192x192" href="images/android-desktop.png">

			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta name="apple-mobile-web-app-status-bar-style" content="black">
			<meta name="apple-mobile-web-app-title" content="BvAsozial">
			<link rel="apple-touch-icon-precomposed" href="/images/ios-desktop.png">

			<meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
			<meta name="msapplication-TileColor" content="#252830">

			<link rel="shortcut icon" href="/images/favicon.png">

			<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
			<link rel="stylesheet" href="/css/material-icons.css">

			<link rel="stylesheet" href="/css/bvasozial.mdl.min.css">
			<link rel="stylesheet" href="/css/sidewide.css">
			<link rel="stylesheet" href="/css/changepassword.css">
			<script src="/js/jquery-2.1.4.min.js"></script>
		</head>
		<?php
				// Check old password
				if($stmt = $mysqli->prepare('SELECT password FROM login WHERE uid = ?')){
					$stmt->bind_param('s', $_SESSION['username']);
					$stmt->execute();
					$stmt->store_result();

					$stmt->bind_result($oldpassword);
					$stmt->fetch();
					if($oldpassword == sha1($_POST['oldpassword'])){
						$stmt = $mysqli->prepare('UPDATE login SET password = ? WHERE uid = ?');
						$stmt->bind_param('ss', sha1($_POST['password']), $_SESSION['username']);
						$stmt->execute();
						if($stmt->errno == 0){
							logout();
							?>
								<body>
									<div class="mdl-layout__container">
										<div class="layout-wrapper">
											<header class="layout__header header mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
												<div class="header__inner">
													<p class="mdl-typography--headline header__title">
														Überprüfung...
													</p>						
												</div>
												<div class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>
											</header>
											<main class="page-content mdl-color--grey-100">

											</main>
										</div>
									</div>
									<script>
									setTimeout(function(){
										location.replace('./../../')
									}, 3000);
									</script>
									<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
								</body>
							<?php
						} else {
								$_POST = array();
							?>
								<body>
									<div class="mdl-layout__container">
										<div class="layout-wrapper">
											<header class="layout__header header--small mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
												<div class="header__inner">
													<p class="mdl-typography--headline header__title">
														Passwort ändern
													</p>						
												</div>
											</header>
											<main class="page-content mdl-color--grey-100">
												<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
													<p class="mdl-typography--title">Passwort ändern für <?= $_SESSION['username'] ?></p>
													<form action="./index.php" method="post" class="layout__form">
														<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
															<input type="password" class="mdl-textfield__input" id="oldpassword" name="oldpassword">
															<label class="mdl-textfield__label" for="passwordconfrim">Altes Passwort</label>
														</div>
														<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
															<input type="password" class="mdl-textfield__input" id="password" name="password">
															<label class="mdl-textfield__label" for="password">Neues Passwort</label>
														</div>
														<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
															<input type="password" class="mdl-textfield__input" id="passwordconfrim" name="passwordconfrim">
															<label class="mdl-textfield__label" for="passwordconfrim">Neues Passwort bestätigen</label>
														</div>
														<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white">
															Passwort ändern
														</button>
													</form>
												</div>
											</main>
										</div>
									</div>
									<div class="mdl-snackbar mdl-js-snackbar">
										<div class="mdl-snackbar__text"></div>
										<button class="mdl-snackbar__action" type="button"></button>
									</div>
									<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
									<script src="/js/changepassword.js"></script>
									<script>
										$(document).ready(function(){
											$('.mdl-snackbar')[0].addEventListener('mdl-componentupgraded', function(){
												this.MaterialSnackbar.showSnackbar({message: 'Beim Einfügen ist ein Fehler aufgetreten', timeout: 2000});
											});
										});
									</script>
								</body>
							<?php
						}
					} else {
						?>
							<body>
									<div class="mdl-layout__container">
										<div class="layout-wrapper">
											<header class="layout__header header--small mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
												<div class="header__inner">
													<p class="mdl-typography--headline header__title">
														Passwort ändern
													</p>						
												</div>
											</header>
											<main class="page-content mdl-color--grey-100">
												<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
													<p class="mdl-typography--title">Passwort ändern für <?= $_SESSION['username'] ?></p>
													<form action="./index.php" method="post" class="layout__form">
														<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-invalid">
															<input type="password" class="mdl-textfield__input" id="oldpassword" name="oldpassword">
															<label class="mdl-textfield__label" for="passwordconfrim">Altes Passwort</label>
														</div>
														<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
															<input type="password" class="mdl-textfield__input" id="password" name="password">
															<label class="mdl-textfield__label" for="password">Neues Passwort</label>
														</div>
														<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
															<input type="password" class="mdl-textfield__input" id="passwordconfrim" name="passwordconfrim">
															<label class="mdl-textfield__label" for="passwordconfrim">Neues Passwort bestätigen</label>
														</div>
														<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white">
															Passwort ändern
														</button>
													</form>
												</div>
											</main>
										</div>
									</div>
									<div class="mdl-snackbar mdl-js-snackbar">
										<div class="mdl-snackbar__text"></div>
										<button class="mdl-snackbar__action" type="button"></button>
									</div>
									
									<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
									<script src="/js/changepassword.js"></script>
									<script>
										$(document).ready(function(){
											$('.mdl-snackbar')[0].addEventListener('mdl-componentupgraded', function(){
												this.MaterialSnackbar.showSnackbar({message: 'Alte Passwörter stimmen nicht überein', timeout: 2000});
											});
										});
									</script>
								</body>
						<?php
					}
				} else {
					?>
					<body>
						<div class="mdl-layout__container">
							<div class="layout-wrapper">
								<header class="layout__header header mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
									<div class="header__inner">
										<p class="mdl-typography--headline header__title">
											Überprüfung...
										</p>						
									</div>
									<div class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>
								</header>
								<main class="page-content mdl-color--grey-100">
									<?php
										echo 'Fehler: Server konnte Anfrage nicht erstellen. Sende bitte den nachfolgenden Text an abi.zeitung.bva2016@gmail.com:<br><br>';
										echo base64_encode($mysqli->error . ';' . $mysqli-errno .';' . $mysqli->sqlstate);
									?>
								</main>
							</div>
						</div>
						<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
					</body>
</html>
					<?php
				}
	else :
		// refuse request
		if(login_check($mysqli) == true) : endif;
			$_SESSION['username'] = 'a_barto';
			//$user = getUser($_SESSION['username'], $mysqli);
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<title>BvAsozial</title>
		
		<meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

		<meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="BvAsozial">
    <link rel="apple-touch-icon-precomposed" href="/images/ios-desktop.png">
		
		<meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#252830">
		
		<link rel="shortcut icon" href="/images/favicon.png">
		
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="/css/material-icons.css">
		
		<link rel="stylesheet" href="/css/bvasozial.mdl.min.css">
		<link rel="stylesheet" href="/css/list.css">
		<link rel="stylesheet" href="/css/sidewide.css">
		<link rel="stylesheet" href="/css/changepassword.css">
		<script src="/js/jquery-2.1.4.min.js"></script>
	</head>
	<body>
		<div class="mdl-layout__container">
			<div class="layout-wrapper">
				<header class="layout__header header--small mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Passwort ändern
						</p>						
					</div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
						<p class="mdl-typography--title">Passwort ändern für <?= $_SESSION['username'] ?></p>
						<form action="./index.php" method="post" class="layout__form">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="password" class="mdl-textfield__input" id="oldpassword" name="oldpassword">
								<label class="mdl-textfield__label" for="passwordconfrim">Altes Passwort</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="password" class="mdl-textfield__input" id="password" name="password">
								<label class="mdl-textfield__label" for="password">Neues Passwort</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="password" class="mdl-textfield__input" id="passwordconfrim" name="passwordconfrim">
								<label class="mdl-textfield__label" for="passwordconfrim">Neues Passwort bestätigen</label>
							</div>
							<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white">
								Passwort ändern
							</button>
						</form>
					</div>
				</main>
			</div>
		</div>
		<div class="mdl-snackbar mdl-js-snackbar">
			<div class="mdl-snackbar__text"></div>
			<button class="mdl-snackbar__action" type="button"></button>
		</div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
		<script src="/js/changepassword.js"></script>
	</body>
</html>
<?php endif; ?>
