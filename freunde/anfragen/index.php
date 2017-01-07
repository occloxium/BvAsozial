<?php
	include_once '../../includes/functions.php';
	include_once '../../includes/db_connect.php';
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
        <link rel="stylesheet" href="/css/friends.css">
        <link rel="stylesheet" href="/css/requests.friends.css">
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
                <?php get_nav('requests'); ?>
            </div>
            <main class="mdl-layout__content mdl-color--grey-100" data-username="<?php echo $_SESSION['username'] ?>" data-directory="<?php echo $user['directory'] ?>" meta-friends="">
                <div class="mdl-card container--top-margin mdl-color--white mdl-shadow--2dp" id="meine-anfragen">
                    <p class="mdl-typography--title element--margin-bottom element--border-bottom">Freundschaftsanfragen</p>
                    <p class="mdl-typography--headline">Erhaltene Anfragen</p>
                    <ul class="mdl-list" id="received">
                        <script>
                            $.ajax({
                                method: 'get',
                                url: './_ajax/received-requests.php?uid=<?php echo $user['uid']?>',
                                success: function(data) {
                                    $('.mdl-list#received').append(data);
                                },
                                cache: false
                            });

                        </script>
                    </ul>
                </div>
                <div class="mdl-card mdl-color--white mdl-shadow--2dp" id="gesendete-anfragen">
                    <p class="mdl-typography--headline">Gesendete Anfragen</p>
                    <ul class="mdl-list" id="sent">
                        <script>
                            $.ajax({
                                method: 'get',
                                url: './_ajax/sent-requests.php?uid=<?php echo $user['uid']?>',
                                success: function(data) {
                                    $('.mdl-list#sent').append(data);
                                },
                                cache: false
                            });

                        </script>
                    </ul>
                </div>
            </main>
        </div>
        <script src="/js/anfragen.freunde.js"></script>
        <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>

    </html>
    <?php else :
				header('Location: ../../');
			endif; ?>
