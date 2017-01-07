<?php
	include_once '../includes/db_connect.php';
	include_once '../includes/functions.php';
	if(!isset($_GET['_'])) : 
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		
		<title>BvAsozial </title>
		
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
		<link rel="stylesheet" href="../css/admin.css">
		
		<script src="/js/jquery-2.1.4.min.js"></script>
		<script src="js/notification.js"></script>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
				<header class="drawer-header">
          <img class="logo" src="/admin/img/bvasozial-logo.png">
					<div>
						<div class="flex-container">
							<div class="mdl-layout-spacer"></div>
					</div>
        </header>
				<nav class="navigation mdl-navigation mdl-color--blue-grey-800">
					<?php
						$query = "SELECT uid, name FROM person ORDER BY name ASC";
						if(!($stmt = $mysqli->prepare($query))){
							echo "Es ist ein Fehler aufgetreten!";
						} else {
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result($uid, $name);
							while($stmt->fetch()){
							?>
								<a href="./?_=<?php echo $uid ?>" class="mdl-navigation__link">
									<?php
										if(finalized($uid, $mysqli) == true) : ?>
											<i class="material-icons">check_circle</i>
										<?php else : ?>
											<i class="material-icons">account_circle</i>
										<?php endif; ?>
									<span><?php echo $name ?></span>
								</a>
							<?php
							}
						}
					?>
				</nav>
      </div>
      <main class="mdl-layout__content mdl-color--blue-grey-800">
        <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top container--wide">
					<p class="mdl-typography--headline">Steckbriefe finalisieren</p>	
					<p class="mdl-typography--body-1">Mit dem Tool können die Redakteuere ganz leicht die Informationen auslesen, die die Benutzer durch ihre Fragen bereitgestellt haben. <br>In der Seitenleiste findet Ihr die Personen, bereits abgeschlossene Profile werden mit einem Haken versehen. </p>
				</div>
      </main>
    </div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
<?php else : 
	$uid = $_GET['_'];
	$user = getUser($uid, $mysqli);
	
	if(!finalized($uid, $mysqli)) :
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		
		<title>BvAsozial </title>
		
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
		<link rel="stylesheet" href="../css/admin.css">
		<link rel="stylesheet" href="/admin/css/finalize.css">
		
		<script src="/js/jquery-2.1.4.min.js"></script>
		<script src="js/notification.js"></script>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
				<header class="drawer-header">
          <img class="logo" src="/admin/img/bvasozial-logo.png">
					<div>
						<div class="flex-container">
							<div class="mdl-layout-spacer"></div>
					</div>
        </header>
				<nav class="navigation mdl-navigation mdl-color--blue-grey-800">
					<?php
						$query = "SELECT uid, name FROM person ORDER BY name ASC";
						if(!($stmt = $mysqli->prepare($query))){
							echo "Es ist ein Fehler aufgetreten!";
						} else {
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result($uid, $name);
							while($stmt->fetch()){
							?>
								<a href="./?_=<?php echo $uid ?>" class="mdl-navigation__link">
									<?php
										if(finalized($uid, $mysqli) == true) : ?>
											<i class="material-icons">check_circle</i>
										<?php else : ?>
											<i class="material-icons">account_circle</i>
										<?php endif; ?>
									<span><?php echo $name ?></span>
								</a>
							<?php
							}
						}
						$uid = $_GET['_'];
					?>
				</nav>
      </div>
      <main class="mdl-layout__content mdl-color--blue-grey-800">
				<?php
					$o = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "users/{$user['uid']}/{$user['uid']}.json"), true);
				?>
        <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top container--wide">
					<p class="mdl-typography--headline">Steckbrief von <b><?php echo $user['name'] ?></b></p>
					<p>[<?php echo $user['uid'] ?>]</p>
					<section id="avatar">
						<a href="<?php echo "/users/{$user['uid']}/avatar.jpg";?>" target="_blank"><img src="<?php echo "/users/{$user['uid']}/avatar.jpg";?>"></a>
					</section>
					<form action="finalizeProfile.php" method="post">
						<section class="border border--top" id="namen">
							<p class="mdl-typography--title" id="name"><?php echo $user['name'] ?></p>
							<ul>
								<span>Rufnamen</span>
								<?php
									if(count($o['rufnamen']) > 0){
										foreach($o['rufnamen'] as $name){
											echo "<li>$name</li>";
										}
									} else {
										echo "<li>Keine Rufnamen vorhanden</li>";
									}
								?>
							</ul>
							<p class="spruch">
								<?php echo $o['spruch'] ?>
							</p>
						</section>
						<section class="border border--top" id="fragen">
							<p class="mdl-typography--headline">Fragen</p>
							<p class="mdl-typography--title">Eigene Fragen</p>
							<ul>
								<?php
									$var = 0;
									foreach($o['eigeneFragen'] as $frage){
										if(strlen($frage['antwort']) > 0){
											?>
											<li class="frage">
												<b><?php echo $frage['frage'] ?></b>
												<div class="flex-container">
													<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
														<input type="text" class="mdl-textfield__input" value="<?php echo $frage['antwort']?>" id="item-<?php echo $var ?>" data-item="<?php echo $var ?>">
														<label class="mdl-textfield__label" for="item-<?php echo $var ?>">Deine Antwort</label>
													</div>
												</div>
											</li>
											<?php
											$var++;
										}
									}
								?>
							</ul>
							<p class="mdl-typography--title">Freundesfragen</p>
							<ul>
								<?php
									$var = 0;
									foreach($o['freundesfragen'] as $frage){
										if(count($frage['antworten'] > 0)){
											echo "<li>";
											echo "<p class=\"frage\">{$frage['frage']}</p>";
											echo "<input name=\"f-frage-$var\" value=\"{$frage['frage']}\" hidden>";
											$opt = 0;
											foreach($frage['antworten'] as $from=>$antwort){
												if(strlen($antwort) > 0){
													$friend = getMinimalUser($from, $mysqli);
													echo "<span>{$friend['name']} sagt:</span>";
													echo "<label class=\"mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect\" for=\"f-antwort-$var-$opt\">";
													echo "<input type=\"checkbox\" id=\"f-antwort-$var-$opt\" class=\"mdl-checkbox__input\">";
													echo "<span class=\"mdl-checkbox__label\">$antwort</span>";
													echo "</label>";
													$opt++;
												}
											}
											echo "</li>";
											$var++;
										}
									}
								?>
							</ul>
						</section>
						<section class="border border--top" id="">
							<p><br>Der Steckbrief wird mit diesem Klick finalisiert und der Benutzer aus der Datenbank entfernt. Danach wird er sich nicht mehr anmelden können und auch nicht mehr in den Admin-Panels erscheinen außer hier als finalisierter Benutzer. Wenn du dir sicher bist, dass du diesen Schritt durchführen möchtest, klicke auf den Button unten</p>
							<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--white mdl-color--primary">
								Profil finalisieren
							</button>
						</section>
					</form>
				</div>
      </main>
    </div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>	
<?php else : ?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		
		<title>BvAsozial </title>
		
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
		<link rel="stylesheet" href="../css/admin.css">
		<link rel="stylesheet" href="/admin/css/finalize.css">
		
		<script src="/js/jquery-2.1.4.min.js"></script>
		<script src="js/notification.js"></script>
	</head>
	<body>
		<div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
				<header class="drawer-header">
          <img class="logo" src="/admin/img/bvasozial-logo.png">
					<div>
						<div class="flex-container">
							<div class="mdl-layout-spacer"></div>
					</div>
        </header>
				<nav class="navigation mdl-navigation mdl-color--blue-grey-800">
					<?php
						$query = "SELECT uid, name FROM person ORDER BY name ASC";
						if(!($stmt = $mysqli->prepare($query))){
							echo "Es ist ein Fehler aufgetreten!";
						} else {
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result($uid, $name);
							while($stmt->fetch()){
							?>
								<a href="./?_=<?php echo $uid ?>" class="mdl-navigation__link">
									<?php
										if(finalized($uid, $mysqli) == true) : ?>
											<i class="material-icons">check_circle</i>
										<?php else : ?>
											<i class="material-icons">account_circle</i>
										<?php endif; ?>
									<span><?php echo $name ?></span>
								</a>
							<?php
							}
						}
						$uid = $_GET['_'];
					?>
				</nav>
      </div>
      <main class="mdl-layout__content mdl-color--blue-grey-800">
				<?php
					$o = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "users/{$user['uid']}/{$user['uid']}.json"), true);
				?>
        <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top container--wide">
					<p class="mdl-typography--headline">Steckbrief von <b><?php echo $user['name'] ?></b></p>
					<p>[<?php echo $user['uid'] ?>]</p>
					<section id="avatar">
						<a href="<?php echo "/users/{$user['uid']}/avatar.jpg";?>" target="_blank"><img src="<?php echo "/users/{$user['uid']}/avatar.jpg";?>"></a>
					</section>
					<section class="border border--top" id="namen">
						<p class="mdl-typography--title" id="name"><?php echo $user['name'] ?></p>
						<ul>
							<span>Rufnamen</span>
							<?php
								if(count($o['rufnamen']) > 0){
									foreach($o['rufnamen'] as $name){
										echo "<li>$name</li>";
									}
								} else {
									echo "<li>Keine Rufnamen vorhanden</li>";
								}
							?>
						</ul>
						<p class="spruch">
							<?php echo $o['spruch'] ?>
						</p>
					</section>
					<section class="border border--top" id="fragen">
						<p class="mdl-typography--headline">Fragen</p>
						<ul>
							<?php
								foreach($o['eigeneFragen'] as $frage){
									if(strlen($frage['antwort']) > 0){
										echo "<li>";
										echo "<p class=\"frage\">{$frage['frage']}</p>";
										echo "<p class=\"antwort\">{$frage['antwort']}</p>";
										echo "</li>";
									}
								}
							?>
						</ul>
					</section>
				</div>
      </main>
    </div>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>	
<?php endif; ?>
<?php	endif; ?>