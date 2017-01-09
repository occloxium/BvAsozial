<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
?>
<!DOCTYPE html>
<html lang="de">
	<head>
    <?php _getHead(); ?>
</head>

<body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
        <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
            <?php _getNav('hilfe'); ?>
        </div>
        <main class="mdl-layout__content mdl-color--blue-grey-900">
            <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top mdl-color--grey-200" style="margin-top: 12em; margin-bottom: 6em;">
                <p class="mdl-typography--headline" style="font-size: 250%; font-weight: bold; margin: 1em 0 2em; text-align: center">Support</p>
                <p class="mdl-typography--body-1" style="width: 80%; margin: 0 auto; font-size: 100%; line-height: 2em;">
                    Wir haben uns Mühe gegeben, alles fehlerfrei und -unanfällig zu programmieren, aber auch wir sind nur Menschen und keine Maschinen, wir machen Fehler.
                    <br> Wenn ihr daher über einen stolpert, nicht wisst, wie euch geschieht oder die Website plötzlich wilde Sachen macht, berichtet uns davon, per Mail an <a href="mailto:occloxium@gmail.com" target="_blank">occloxium@gmail.com</a>, per Whatsapp an die Administratoren oder persönlich, falls ihr uns sehen solltet. Berichtet, wo der Fehler aufgetreten ist, was ihr davor gemacht habt und was passiert ist. Im Regelfall sollte das aber immer <i>nichts</i> sein ;).
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
