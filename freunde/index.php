<?php
	include_once '../includes/db_connect.php';
	include_once '../includes/functions.php';

	secure_session_start();
	if(login_check($mysqli) == true) :
		$user = getUser($_SESSION['username'], $mysqli);
		$_users = getUsers($mysqli);
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
        <link rel="apple-touch-icon-precomposed" href="../images/ios-desktop.png">

        <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
        <meta name="msapplication-TileColor" content="#252830">

        <link rel="shortcut icon" href="../images/favicon.png">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <link rel="stylesheet" href="/css/bvasozial.mdl.min.css">
        <link rel="stylesheet" href="/css/sidewide.css">
        <link rel="stylesheet" href="/css/friends.css">
        <script src="/js/jquery-2.1.4.min.js"></script>
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
                <?php get_nav('friends'); ?>
            </div>
            <main class="mdl-layout__content mdl-color--grey-100" meta-username="<?php echo $_SESSION['username'] ?>" meta-directory="<?php echo $user['directory'] ?>" meta-friends="<?php echo /*5 - $zaehler*/ 2; ?>">
                <div class="mdl-card friend-list__container friend-list__container--top mdl-color--white mdl-shadow--2dp mdl-card--border">
                    <h3>Nach Freunden suchen</h3>
                    <form class="flex-container" action="./index.php" method="get">
                        <?php if(isset($_GET['search'])) : ?>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">
                            <input class="mdl-textfield__input" type="text" id="search" name="search" value="<?= $_GET['search']?>">
                            <label class="mdl-textfield__label" for="search">Suche nach Freunden...</label>
                        </div>
                        <?php else : ?>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="search" name="search">
                            <label class="mdl-textfield__label" for="search">Suche nach Freunden...</label>
                        </div>
                        <?php endif; ?>
                        <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
							<i class="material-icons">search</i>
						</button>
                    </form>
                </div>
                <?php
					if(isset($_GET['search'])) :
						$found = [];
						$rest = [];
						while($row = $_users->fetch_array(MYSQLI_BOTH)){
							if(stripos($row['name'], $_GET['search']) !== false){
								if($row['uid'] === $_SESSION['username']){
									continue;
								} else {
									$found[] = $row;
								}
							} else {
								if($row['uid'] === $_SESSION['username']){
									continue;
								} else {
									$rest[] = $row;
								}
							}
						}
						if(empty($found)) : ?>
                    <div class="mdl-card friend-list__container mdl-color--white mdl-shadow--2dp mdl-card--border" id="noResults">
                        <div class="flex-container failure">
                            <div class="flex-container__item flex-container__primary-item">
                                <i class="material-icons mdl-color-text--accent">accessibility</i>
                            </div>
                            <div class="flex-container__item flex-container__secondary-item">
                                <h1 class="failure__title mdl-color-text--accent">Oh nein!</h1>
                                <p class="failure__subtitle">Wir konnten den von dir eingegeben Namen nicht in unserer schlauen Tabelle voller Namen finden. Entweder hast du dich schlicht vertippt oder der von dir gesuchte Benutzer ist uns noch unbekannt. In diesem Fall erinnere ihn doch bitte daran, sich zu registrieren!</p>
                            </div>
                        </div>
                    </div>
                    <?php else : ?>
                    <div class="mdl-card friend-list__container mdl-color--white mdl-shadow--2dp mdl-card--border" id="found">
                        <p class="section__title">Auf der Suche nach <b>"<?php echo $_GET['search']?>"</b> haben wir gefunden:</p>
                        <ul class="mdl-list">
                            <?php foreach($found as $person){ ?>
                            <li class="mdl-list__item">
                                <a href="../users/index.php/<?= $person['uid']?>/" class="mdl-list__item-primary-content">
									<img src="/users/<?= $person['directory']?>/avatar.jpg" class="mdl-list__item-avatar">
									<span class="name mdl-color-text--black"><?= $person['name']?></span>
								</a>
                            </li>
                            <?php	}	?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="mdl-card friend-list__container friend-list__container--bottom mdl-color--white mdl-shadow--2dp" id="rest">
                        <p class="section__title">Weitere Menschen, die deine Freunde sein könnten:</p>
                        <ul class="mdl-list">
                            <?php foreach($rest as $person){ ?>
                            <li class="mdl-list__item">
                                <a href="../users/index.php/<?= $person['uid']?>/" class="mdl-list__item-primary-content">
										<img src="/users/<?= $person['directory']?>/avatar.jpg" class="mdl-list__item-avatar">
										<span class="name mdl-color-text--black"><?= $person['name']?></span>
									</a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php
					else :
						$users = [];
						while($row = $_users->fetch_array(MYSQLI_BOTH)){
							if($row['uid'] !== $_SESSION['username']){
								$users[] = $row;
							}
						}
				?>
                    <div class="mdl-card friend-list__container friend-list__container--bottom mdl-color--white mdl-shadow--2dp" id="rest">
                        <p class="section__title">Menschen, die deine Freunde sein könnten:</p>
                        <ul class="mdl-list">
                            <?php
							if(empty($users)){
								?>
                                <li class="mdl-list__item">
                                    Nanu? Keiner da?! <br>Mobilisiere deine Freunde, damit sie sich schnellstmöglich anmelden!
                                </li>
                                <?php
							} else {
								foreach($users as $person){ ?>
                                    <li class="mdl-list__item">
                                        <a href="../users/index.php/<?= $person['uid']?>/" class="mdl-list__item-primary-content">
										<img src="/users/<?= $person['directory']?>/avatar.jpg" class="mdl-list__item-avatar">
										<span class="name mdl-color-text--black"><?= $person['name']?></span>
									</a>
                                    </li>
                                    <?php }
							} ?>

                        </ul>
                    </div>
                    <?php
					endif;
				?>
            </main>
        </div>
        <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>

    </html <?php else : header( 'Location: ../'); endif; ?>
