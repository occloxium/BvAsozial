<?php
		// Prime registration site
		// Verifying purpose by entering login data sent per email
	
		include_once '../includes/db_connect.php';
		include_once '../includes/functions.php';
		secure_session_start();	
		/*print_r($_SESSION);
		echo '<br>';
		print_r($_POST);*/
		unset($_SESSION['step']);
		if(login_check($mysqli) == true){
			header("Location: ../index.php");
			exit;
		}
		if(!isset($_POST, $_POST['step'])) : 
?>
<!doctype html>
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
							Herzlich Willkommen!
						</p>
						<p class="mdl-typography--title header__subtitle">
							Bestätige deine Einladung zum BvAsozial-Netzwerk, um fortzufahren
						</p>
					</div>
					<div id="global-progress" class="mdl-progress mdl-js-progress mdl-color--grey-100"></div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
						<form action="./index.php" method="post">
							<input type="hidden" name="step" value="0">
							<p class="mdl-typography--headline">Hallo!</p>
							<p class="mdl-typography--body-1">
								Trage hier bitte die Anmeldedaten ein, die wir dir geschickt haben, um fortzufahren und dein Profil einzurichten
							</p>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="text" class="mdl-textfield__input" id="uid" name="uid">
								<label for="uid" class="mdl-textfield__label">Benutzername oder E-Mail-Adresse</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="password" class="mdl-textfield__input" id="password" name="password">
								<label for="password" class="mdl-textfield__label">Passwort</label>
							</div>
							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect" type="submit">
								Anmelden
							</button>
						</form>
					</div>
				</main>
			</div>
		</div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
		<script>
			$('#global-progress')[0].addEventListener('mdl-componentupgraded', function() {
				this.MaterialProgress.setProgress(0);
			});
		</script>
	</body>
</html>
<?php elseif(isset($_POST['step'])) :
		
		// Verify user by checking from 'ausstehende_einladungen'
		
		if($_POST['step'] == 0) :
			if(!isValidInvite($_POST['uid'], $_POST['password'], $mysqli)){
				header('Location: ./index.php?e=invalid-login-attempt');
				exit;
			}
			// User validated
			// Let him check his personal data
			$user = getInvitedUser($_POST['uid'], $mysqli);
			$user['password'] = $_POST['password'];
			$_SESSION['user'] = $user;
?>
<!doctype html>
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
				<header class="layout__header mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
					<img class="logo" src="/img/logo-cropped.png">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Überprüfung
						</p>
						<p class="mdl-typography--title header__subtitle">
							Schritt 1 von 3 ...
						</p>
					</div>
					<div id="global-progress" class="mdl-progress mdl-js-progress mdl-color--grey-100"></div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
						<form action="./index.php" method="post">
							<input type="hidden" value="1" name="step">
							<p class="mdl-typography--headline">Hallo <?php echo $user['vorname'] ?>!</p>
							<p class="mdl-typography--body-1">
								Bitte überprüfe deine persönlichen Daten. Wir haben uns Mühe gegeben, sie korrekt einzutragen, aber Fehler schleichen sich immer ein
							</p>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
								<input type="text" class="mdl-textfield__input" value="<?php echo $user['name']?>" id="name" name="name">
								<label for="name" class="mdl-textfield__label">Voller Name</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
								<input type="text" class="mdl-textfield__input" id="email" value="<?php echo $user['email'] ?>" name="email">
								<label for="email" class="mdl-textfield__label">E-Mail-Adresse</label>
							</div>
							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect" type="submit">
								Weiter <i class="material-icons">keyboard_arrow_right</i>
							</button>
						</form>
					</div>
				</main>
			</div>
		</div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
		<script>
			$('#global-progress')[0].addEventListener('mdl-componentupgraded', function() {
				this.MaterialProgress.setProgress(33);
			});
		</script>
	</body>
</html>
<?php 
		elseif($_POST['step'] == 1) :
			$_POST['uid'] = $_SESSION['user']['uid'];
			$_POST['password'] = $_SESSION['user']['password'];
			if(!isValidInvite($_POST['uid'], $_POST['password'], $mysqli)){
				header('Location: ./index.php?e=invalid-login-attempt');
				exit;
			}
			$user = $_SESSION['user'];
			// update personal data, he possibly changed something
			
			// Avatar upload
?>
<!doctype html>
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
				<header class="layout__header mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
					<img class="logo" src="/img/logo-cropped.png">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Personalisierung
						</p>
						<p class="mdl-typography--title header__subtitle">
							Schritt 2 von 3 ...
						</p>
					</div>
					<div id="global-progress" class="mdl-progress mdl-js-progress mdl-color--grey-100"></div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
						<form action="index.php" method="post" enctype="multipart/form-data">
							<p class="mdl-typography--headline">
								Avatar!
							</p>
							<input type="hidden" value="2" name="step">
							<p class="mdl-typography--body-1">
								Wenn du möchtest, dass deine Freunde dich sofort an einem Profilbild erkennen, kannst du hier dein eigenes hochladen. Dein Name sollte es aber auch tun
							</p>
							<input hidden type="text" value="<?php echo $user['uid']?>" name="uid">
							<input hidden type="text" value="<?php echo $_POST['password']?>" name="password">
							<input type="file" hidden class="avatar" accepts="image/*" name="profile_picture">
							<img class="avatar" src="/users/<?php echo $user['directory'] ?>/avatar.jpg">
							<p class="mdl-typography--caption">
								Über die Laufzeit dieser Plattform werden wir die Bilder, die ihr uns gesendet hab, sukzessiv einbauen und euch zuweisen. Wundere dich also nicht, wenn du plötzlich ein Avatar-Bildchen in Farbe hast
							</p>
							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
								Weiter <i class="material-icons">keyboard_arrow_right</i>
							</button>
						</form>
					</div>
				</main>
			</div>
		</div>
		<script src="/js/avatar.registration.js"></script>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
		<script>
			$('#global-progress')[0].addEventListener('mdl-componentupgraded', function() {
				this.MaterialProgress.setProgress(50);
			});
		</script>
	</body>
</html>
<?php elseif($_POST['step'] == 2) :
				$_POST['uid'] = $_SESSION['user']['uid'];
				$_POST['password'] = $_SESSION['user']['password'];
				if(!isValidInvite($_POST['uid'], $_POST['password'], $mysqli)){
					header('Location: ./index.php?e=invalid-login-attempt');
					exit;
				}
				$user = $_SESSION['user'];					 
?>
<!doctype html>
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
				<header class="layout__header mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
					<img class="logo" src="/img/logo-cropped.png">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Fragenauswahl
						</p>
						<p class="mdl-typography--title header__subtitle">
							Schritt 3 von 3 ...
						</p>
					</div>
					<div id="global-progress" class="mdl-progress mdl-js-progress mdl-color--grey-100"></div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
					<p class="mdl-typography--headline">
						Fragenkatalog
					</p>
					<input type="hidden" value="2" name="step">
					<p class="mdl-typography--body-1">
						Jetzt ans Eingemachte! An dem Regler hier kannst du einstellen, wie sicher du dir bist. Entsprechend danach wird die Maximalanzahl deiner Fragen ausgerechnet. Wähle dann so viele aus der Liste aus, wie du dir zutraust, für dich und für deine Freunde. Ein Maximum sind jeweils 8 Fragen pro Rubrik
					</p>
					<div class="slider-wrapper">
						<label class="slider__min">3 Fragen</label>
						<input class="mdl-slider mdl-js-slider" type="range" min="3" max="8" value="4" tabindex="0">
						<label class="slider__max">8 Fragen</label>
					</div>
					
					<form class="form--fill-wrapper" method="post" action="./index.php">
						<p class="mdl-typography--title">Fragen für Dich</p>
						<table class="fragenkatalog-table mdl-shadow--2dp" id="eigeneFragen">					
							<?php
								$jsonstr = file_get_contents('fragenkatalog.json');
								$obj = json_decode($jsonstr, true);
								echo "<tbody>";
								for($i = 0; $i < count($obj['eigeneFragen']); $i++) {
									$frage = $obj['eigeneFragen'][$i];
									$num = $i + 1;
									echo <<<TR
<tr>
	<td>
		<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="e-frage-$num">
			<input type="checkbox" data-category="eigeneFragen" id="e-frage-$num" class="mdl-checkbox__input" name="e-frage-$num">
		</label>
	</td>
	<td class="frage">$frage</td>
</tr>								
TR;
								}
								echo "</tbody>";
							?>
						</table>
						<p class="mdl-typography--title">Fragen für deine Freunde</p>
						<table class="fragenkatalog-table mdl-shadow--2dp" id="freundesfragen">				
							<?php
								echo "<tbody>";
								for($i = 0; $i < count($obj['freundesfragen']); $i++) {
									$frage = $obj['freundesfragen'][$i];
									$num = $i + 1;
									echo <<<TR
<tr>
	<td>
		<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="f-frage-$num">
			<input type="checkbox" data-category="freundesfragen" id="f-frage-$num" class="mdl-checkbox__input" name="f-frage-$num">
		</label>
	</td>
	<td class="frage">$frage</td>
</tr>								
TR;
								}
								echo "</tbody>";
							?>
						</table>	
						<input type="hidden" value="3" name="step">	
						<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
							Weiter <i class="material-icons">keyboard_arrow_right</i>
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
		<script src="/js/fragen.registration.js"></script>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
		<script>
			$('#global-progress')[0].addEventListener('mdl-componentupgraded', function() {
				this.MaterialProgress.setProgress(66);
			});
		</script>
	</body>
</html>
<?php 
		elseif($_POST['step'] == 3) :
			$user = $_SESSION['user'];
			// Final steps
			unset($_POST['step']);
			// iterate over $_POST and add questions to <user>.json
			$obj = json_decode(file_get_contents('fragenkatalog.json'), true);
	
			$userfile = json_decode(file_get_contents("../users/{$user['directory']}/{$user['uid']}.json"), true);

			foreach($_POST as $key=>$element){
				if(preg_match("/^[ef]-frage-[1-9][0-9]*/", $key)){
					if(substr($key, 0, 1) == "f"){
						// Freundesfrage
						$num = intVal(substr($key,8));
						$userfile['freundesfragen'][] = [
							"frage" => $obj['freundesfragen'][$num - 1],
							"antworten" => []
						];
					}
					else if(substr($key, 0, 1) == "e"){
						// Eigene Frage
						$num = intVal(substr($key,8));
						$userfile['eigeneFragen'][] = [
							"frage" => $obj['eigeneFragen'][$num - 1],
							"antwort" => ""
						];
					}
				}
			}
			// Save changes
			if(file_put_contents("../users/{$user['directory']}/{$user['uid']}.json", json_encode($userfile, JSON_PRETTY_PRINT)) <= 0){
				exit;
			}

			$_POST['uid'] = $_SESSION['user']['uid'];
			$_POST['password'] = $_SESSION['user']['password'];
			if(!isValidInvite($_POST['uid'], $_POST['password'], $mysqli)){
				header('Location: ./index.php?e=invalid-login-attempt');
				exit;
			}
					
			// Create user entries in Database
			$query = "INSERT INTO person (name, uid, directory, registered_since) VALUES ('{$user['name']}','{$user['uid']}','{$user['directory']}', CURRENT_DATE);";
			$mysqli->query($query);
			$query = "INSERT INTO login (uid, password, email) VALUES ('{$user['uid']}', SHA1('{$user['password']}'), '{$user['email']}');";
			$mysqli->query($query);
			// remove invite
			// Remove invite after successfully inserting user into db
			$mysqli->query("DELETE FROM ausstehende_einladungen WHERE uid = '{$user['uid']}'");
			// clear session array of potentially dangerous user data
			
			// Bypass login with settings still in place
			if(login($user['uid'], $user['password'], $mysqli)){
				unset($_SESSION['user']);
				header('Location: ../index.php');
				exit;
			} else {
				unset($_SESSION['user']);
				header('Location: ../anmelden/index.php?e=manual-login-required');
				exit;
			}
	endif;
endif; ?>