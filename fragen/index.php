<?php
	include_once '../includes/functions.php';
	include_once '../includes/db_connect.php';

	include_once './_ajax/frage.php';

	ini_set('display_errors',1);

	secure_session_start();
	if(login_check($mysqli) == true) :
		$user = getUser($_SESSION['username'], $mysqli);
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
        <link rel="stylesheet" href="/css/questions.css">
        <script src="/js/jquery-2.1.4.min.js"></script>
        <script src="/js/jquery.autogrow.js"></script>
    </head>

    <body>
        <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
            <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
                <header class="drawer-header">
                    <img class="avatar" src="<?php echo '/users/' . $user['directory'] . '/avatar.jpg'?>">
                    <div>
                        <div class="flex-container">
                            <span><?php echo $user['email']; ?></span>
                            <div class="mdl-layout-spacer"></div>
                        </div>
                    </div>
                </header>
                <?php get_nav('questions'); ?>
            </div>
            <main class="mdl-layout__content mdl-color--blue-grey-900" data-username="<?php echo $user['uid'] ?>" data-directory="<?php echo $user['directory'] ?>" data-friends="<?php echo $user['freundesanzahl'] ?>">
                <section class="mdl-card fragen-container fragen-container--margin-top mdl-color--white mdl-shadow--2dp mdl-card--border">
                    <p class="mdl-typography--headline">Fragen</p>
                    <nav class="quicknav">
                        <span>Sektionen</span>
                        <a href="#meine-fragen">Meine eigenen Fragen</a>
                        <a href="#antworten-freunde">Antworten meiner Freunde</a>
                        <a href="#fragen-freunde">Fragen meiner Freunde</a>
                    </nav>

                </section>
                <div class="mdl-card fragen-container mdl-color--white mdl-shadow--2dp mdl-card--border" id="meine-fragen">
                    <h1 class="mdl-typography--title">Meine eigenen Fragen<a class="highlight highlight--hover" href="./edit/eigene-fragen/"><i class="material-icons">edit</i></a></h1>
                    <ol id="my-questions">
                        <?php
							$path = "../users/{$user['directory']}/{$user['uid']}.json";
							$jsonstr = file_get_contents($path);
							$obj = json_decode($jsonstr, true);
							$obj = $obj['eigeneFragen'];
							$i = 1;
							foreach($obj as $frage){
								echo frage($frage, $user, null, $i, 0);
								$i++;
							}

						?>
                    </ol>
                    <button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect save-all">
						Alles abspeichern
					</button>
                </div>
                <?php
					$freunde = $user['freunde'];
				?>
                    <div class="mdl-card fragen-container mdl-color--white mdl-shadow--2dp mdl-card--border" id="antworten-freunde">
                        <h1 class="mdl-typography--title">Antworten meiner Freunde<a class="highlight highlight--hover" href="./edit/freundesfragen/"><i class="material-icons">edit</i></a></h1>
                        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                            <div class="page-navigation" data-for="antworten-freunde">
                                <?php
							if($user['freundesanzahl'] > 4){
								echo '<p class="mdl-typography--body-1">Du hast schon so viele Freunde geaddet, dass wir sie für dich einsortiert haben, um die Ordnung zu erhalten</p>';
								echo '<div class="flex-container">';
								if(isset($_GET['p1'])){
									$pR = ceil($user['freundesanzahl'] / 4);
									if($_GET['p1'] > $pR || $_GET['p1'] <= 0){
										$_GET['p1'] = 1;
									}
									$p = intval($_GET['p1']);
									for($i = 1; $i <= $pR; $i++){
										echo '<a class="navigation__link ' . ($i === $p ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p1=' . $i . '#antworten-freunde">' . $i . '</a>';
									}
								} else {
									$_GET['p1'] = 1;
									$pR = ceil($user['freundesanzahl'] / 4);
									for($i = 1; $i <= $pR; $i++){
										echo '<a class="navigation__link ' . ($i === 1 ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p1=' . $i . '#antworten-freunde">' . $i . '</a>';
									}
								}
								echo '</div>';

							}
							?>
                            </div>
                            <div class="mdl-tabs__tab-bar">
                                <?php
								$p = intval($_GET['p1']);
								$range = [
									"min" => ($p - 1) * 4,
									"max" => $p * 4
								];
								$i = 1;
								$limitedFriends = array_slice($freunde, $range['min'], 4);
								foreach($limitedFriends as $freund){
									$freund = getUser($freund, $mysqli);
									echo '<a href="#' . replaceSpecialCharacters($freund['uid']) . '-panel" class="mdl-tabs__tab ' . ($i === 1 ? 'is-active' : '') . '">' . $freund['vorname'] . '</a>';
									$i++;
								}
							?>
                            </div>
                            <?php
							$path = "../users/{$user['directory']}/{$user['uid']}.json";
							$jsonstr = file_get_contents($path);
							$obj = json_decode($jsonstr, true);
							$obj = $obj['freundesfragen'];
							$i = 1;
							foreach($limitedFriends as $freund){
								$freund = getUser($freund, $mysqli);
								$j = 1;
								echo '<div class="mdl-tabs__panel ' . ($i === 1 ? 'is-active' : '') . '" id="' . replaceSpecialCharacters($freund['uid']) . '-panel"><ol>';
								foreach($obj as $frage){
									if(!isset($frage['antworten'][$freund['uid']])){
										$frage['antworten'][$freund['uid']] = null;
									}
									echo frage($frage, $user, $freund, $j, 1);
									$j++;
								}
								$i++;
								echo '</ol></div>';
							}
						?>
                        </div>
                    </div>
                    <div class="mdl-card fragen-container mdl-color--white mdl-shadow--2dp mdl-card--border" id="fragen-freunde">
                        <h1 class="mdl-typography--title">Fragen meiner Freunde</h1>
                        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                            <div class="page-navigation" data-for="antworten-freunde">
                                <?php
							if($user['freundesanzahl'] > 4){
								echo '<p class="mdl-typography--body-1">Du hast schon so viele Freunde geaddet, dass wir sie für dich einsortiert haben, um die Ordnung zu erhalten</p>';
								echo '<div class="flex-container">';
								if(isset($_GET['p2'])){
									$pR = ceil($user['freundesanzahl'] / 4);
									if($_GET['p2'] > $pR || $_GET['p2'] <= 0){
										$_GET['p2'] = 1;
									}
									$p = intval($_GET['p2']);
									for($i = 1; $i <= $pR; $i++){
										echo '<a class="navigation__link ' . ($i === $p ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p2=' . $i . '#fragen-freunde">' . $i . '</a>';
									}
								} else {
									$_GET['p2'] = 1;
									$pR = ceil($user['freundesanzahl'] / 4);
									for($i = 1; $i <= $pR; $i++){
										echo '<a class="navigation__link ' . ($i === 1 ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p2=' . $i . '#fragen-freunde">' . $i . '</a>';
									}
								}
								echo '</div>';
							}
							?>
                            </div>
                            <div class="mdl-tabs__tab-bar">
                                <?php
								$p = intval($_GET['p2']);
								$range = [
									"min" => ($p - 1) * 4,
									"max" => $p * 4
								];
								$i = 1;
								$limitedFriends = array_slice($freunde, $range['min'], 4);
								foreach($limitedFriends as $freund){
									$freund = getUser($freund, $mysqli);
									echo '<a href="#' . replaceSpecialCharacters($freund['uid']) . '-panel" class="mdl-tabs__tab ' . ($i === 1 ? 'is-active' : '') . '">' . $freund['vorname'] . '</a>';
									$i++;
								}
							?>
                            </div>
                            <?php

							$i = 1;
							foreach($limitedFriends as $freund){
								$freund = getUser($freund, $mysqli);
								$path = "../users/{$freund['directory']}/{$freund['uid']}.json";
								$jsonstr = file_get_contents($path);
								$obj = json_decode($jsonstr, true);
								$obj = $obj['freundesfragen'];
								$j = 1;
								echo '<div class="mdl-tabs__panel ' . ($i === 1 ? 'is-active' : '') . '" id="' . replaceSpecialCharacters($freund['uid']) . '-panel"><ol>';
								foreach($obj as $frage){
									if(!isset($frage['antworten'][$user['uid']])){
										$frage['antworten'][$user['uid']] = "";
									}
									echo frage($frage, $user, $freund, $j, 2);
									$j++;
								}
								$i++;
								echo '</ol></div>';
							}
						?>
                        </div>
                    </div>
            </main>
        </div>
        <script src="/js/fragen.js"></script>
        <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>

    </html>
    <?php else :
				header('Location: ../');
			endif; ?>
