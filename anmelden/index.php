
<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';
 ?>
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
    <link rel="stylesheet" href="/css/overview.css">

    <script src="/js/jquery-2.1.4.min.js"></script>
</head>

<body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
        <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
            <?php get_nav('login'); ?>
        </div>
        <main class="mdl-layout__content mdl-color--blue-grey-900">
            <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top">
                <form method="post" action="/includes/processLogin.php">
                    <p class="mdl-typography--headline">Anmelden</p>
                    <p class="mdl-typography--body-">Melde dich an, um auf dein Profil zuzugreifen und Fragen beantworten zu können</p>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input type="text" class="mdl-textfield__input" name="uid" id="uid">
                        <label for="uid" class="mdl-textfield__label">Benutzername oder E-Mail-Adresse</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input type="password" class="mdl-textfield__input" name="password" id="password">
                        <label for="password" class="mdl-textfield__label">Passwort</label>
                    </div>
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white">
        							Anmelden
        						</button>
                </form>
            </div>
        </main>
    </div>
    <div class="mdl-snackbar mdl-js-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
    <script src="/js/anmelden.js"></script>
    <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
</body>
</html>
