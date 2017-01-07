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
    <link rel="stylesheet" href="/css/material-icons.css">

    <link rel="stylesheet" href="/css/bvasozial.mdl.min.css">
    <link rel="stylesheet" href="/css/sidewide.css">
    <link rel="stylesheet" href="/css/overview.css">

    <script src="/js/jquery-2.1.4.min.js"></script>
</head>

<body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
        <div class="drawer mdl-layout__drawer mdl-color--blue-grey-800 mdl-color-text--blue-grey-50">
            <?php get_nav('support'); ?>
        </div>
        <main class="mdl-layout__content mdl-color--blue-grey-800">
            <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top mdl-color--grey-200" style="margin-top: 12em; margin-bottom: 6em;">
                <p class="mdl-typography--headline" style="font-size: 250%; font-weight: bold; margin: 1em 0 2em; text-align: center">Support</p>
                <p class="mdl-typography--body-1" style="width: 80%; margin: 0 auto; font-size: 100%; line-height: 2em;">
                    Wir haben uns Mühe gegeben, alles fehlerfrei und -unanfällig zu programmieren, aber auch wir sind nur Menschen und keine Maschinen, wir machen Fehler.
                    <br> Wenn ihr daher über einen stolpert, nicht wisst, wie euch geschieht oder die Website plötzlich wilde Sachen macht, berichtet uns davon, per Mail an <a href="mailto:abi.zeitung.bva2016@gmail.com" target="_blank">abi.zeitung.bva2016@gmail.com</a>, per Whatsapp an die Administratoren oder persönlich, falls ihr uns sehen solltet. Berichtet, wo der Fehler aufgetreten ist, was ihr davor gemacht habt und was passiert ist. Im Regelfall sollte das aber immer <i>nichts</i> sein ;).
                </p>
                <p class="mdl-typography-body-1" style="width: 80%; margin: 1em auto; font-size: 120%; line-height: 2em;">
                    Vielen Dank für jegliche Rückmeldung<br> Euer <b>Abi-Zeitungsteam</b>
                </p>
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
