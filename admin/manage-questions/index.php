<?php
    include_once '../includes/db_connect.php';
    include_once '../includes/functions.php';

    ini_set('display_errors', 1);

    secure_session_start();
    if (login_check($mysqli) == true) :
?>
    <!DOCTYPE html>
    <html lang="de">

    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

        <title>BvAsozial &gt; Admin &gt; Dashboard</title>

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

        <link rel="stylesheet" href="/css/bvasozial.mdl.src.css">
        <link rel="stylesheet" href="/css/sidewide.css">
        <link rel="stylesheet" href="../css/admin.css">

        <script src="/js/jquery-2.1.4.min.js"></script>
    </head>
    <body>
        <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
            <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
                <?php get_nav('manage-questions'); ?>
            </div>
            <main class="mdl-layout__content mdl-color--grey-100">
                <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top container--wide">
                    <div class="mdl-card__title">
                        <h3>Fragen verwalten</h3>
                    </div>
                    <div class="mdl-card__action">
                        <a class="mdl-button mdl-js-button mdl-color-text--white mdl-color--primary" href="./add-question/">Frage hinzufügen</a>
                        <a class="mdl-button mdl-js-button mdl-color-text--white mdl-color--accent" href="./remove-all-questions/">Alle Fragen entfernen</a>
                    </div>
                    <div class="mdl-card mdl-card__text">
                        <ul class="mdl-list">
                            <?php
                                $fragenkatalog = file_get_contents('../../registrieren/fragenkatalog.json');
                                if (!($fragenkatalog = json_decode($fragenkatalog, true))) :
                                    echo 'Es ist ein Fehler aufgetreten!';
                                else :
                                  $fragen = $fragenkatalog['eigeneFragen'];
                                  $i = 1;
                            ?>
                                <h4>Eigene Fragen</h4>
																	<?php
                                    foreach ($fragen as $frage) {
                                  ?>
                                  <li class="mdl-list__item mdl-list__item--two-line" id="<?php echo substr(md5('eigeneFragen'), 0, 8).'-0-'.$i ?>">
                                      <span class="mdl-list__item-primary-content">
																				<i class="material-icons mdl-list__item-avatar">help</i>
																				<span><?php echo $frage ?></span>
                                      	<span class="mdl-list__item-sub-title">ID: <?php echo substr(md5('eigeneFragen'), 0, 8).'-0-'.$i ?></span>
                                      </span>
                                      <span class="mdl-list__item-secondary-content">
                                    		<a id="<?php echo substr(md5('eigeneFragen'), 0, 8).'-0-'.$i ?>" class="mdl-button mdl-js-button mdl-button--icon change-question"><i class="material-icons">edit</i></a>
																				<a id="<?php echo substr(md5('eigeneFragen'), 0, 8).'-0-'.$i ?>" class="mdl-button mdl-js-button mdl-button--icon remove-question"><i class="material-icons">close</i></a>
                                      </span>
                                  </li>
	                                <?php
                                      $i += 1;
                                    }
                                    $fragen = $fragenkatalog['freundesfragen'];
                                    $j = 1;
                                    ?>
                                <h4>Freundesfragen</h4>
                              		<?php
                                    foreach ($fragen as $frage) {
                                  ?>
                                  <li class="mdl-list__item mdl-list__item--two-line" id="<?php echo substr(md5('freundesfragen'), 0, 8).'-0-'.$j ?>">
                                      <span class="mdl-list__item-primary-content">
																				<i class="material-icons mdl-list__item-avatar">help_outline</i>
																				<span class="mdl-list__item-title"><?php echo $frage ?></span>
																			  <span class="mdl-list__item-sub-title">ID: <?php echo substr(md5('freundesfragen'), 0, 8).'-1-'.$j ?></span>
                                      </span>
                                      <span class="mdl-list__item-secondary-content">
                                      	<a id="<?php echo substr(md5('freundesfragen'), 0, 8).'-0-'.$j ?>" class="mdl-button mdl-js-button mdl-button--icon change-question"><i class="material-icons">edit</i></a>
																				<a id="<?php echo substr(md5('freundesfragen'), 0, 8).'-0-'.$j ?>" class="mdl-button mdl-js-button mdl-button--icon remove-question"><i class="material-icons">close</i></a>
                                      </span>
                                  </li>
                                <?php
                                    $j += 1;
                                  }
                                  endif;
                                ?>
                        </ul>
                    </div>
                </div>
            </main>
            <a href="./add-question/" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored mdl-color--primary">
						  <i class="material-icons">add</i>
						</a>
        </div>
        <script src="../js/manage-questions.js"></script>
        <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>
  </html>
<?php else : header('Location: ../login/'); exit;
    endif; ?>
