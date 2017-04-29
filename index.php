<?php
  require('constants.php');
  require(ABS_PATH . INC_PATH . 'functions.php');
  secure_session_start();
  if (login_check($mysqli) && !$_SESSION['user']['is_admin']) :
?>
<!doctype html>
<html lang="de">
  <head>
    <?php _getHead('dashboard'); ?>
    <script src="/js/pie-chart.src.js"></script>
  </head>
  <body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <?php require_once(ABS_PATH . '/_sections/drawer.php'); ?>
        <?php _getNav('root') ?>
      </div>
      <main class="mdl-layout__content mdl-color--blue-grey-900" data-username="<?php echo $_SESSION['user']['uid'] ?>" data-directory="<?php echo $_SESSION['user']['directory'] ?>">
      <header class="header mdl-layout__header mdl-color--blue-grey-900 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">Übersicht</span>
          <div class="mdl-layout-spacer"></div>
        </div>
      </header>
      <div class="mdl-grid content">
        <div class="mdl-color--white personal-data mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
          <div class="mdl-cell mdl-cell--4-col mdl-cell--4-desktop">
            <img class="avatar" src="<?php echo '/users/data/'.$_SESSION['user']['uid'].'/avatar.jpg'?>">
          </div>
          <div class="mdl-cell mdl-cell--8-col mdl-cell--8-desktop">
            <div class="data-container" id="personalData">
              <span class="data-container__title"><?php echo $_SESSION['user']['name'] ?></span>
              <ul class="data-list">
                <li class="data-list__item">
                  <span class="data-list__item--index">Erhaltene Freundschaftsanfragen</span>
                  <span class="data-list__item--content"><?php echo $_SESSION['user']['received_requests'] ?></span>
                </li>
                <li class="data-list__item">
                  <span class="data-list__item--index">Gesendete Freundschaftsanfragen</span>
                  <span class="data-list__item--content"><?php echo $_SESSION['user']['sent_requests'] ?></span>
                </li>
                <li class="data-list__item">
                  <span class="data-list__item--index">Benutzerverzeichnis</span>
                  <span class="data-list__item--content">/users/<?php echo $_SESSION['user']['directory'] ?>/</span>
                </li>
                <li class="data-list__item">
                  <span class="data-list__item--index">Registriert seit</span>
                  <span class="data-list__item--content"><?php echo date('d.m.Y', strtotime($_SESSION['user']['registered_since'])) ?></span>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col ">
          <h1>Ziele</h1>
          <div class="mdl-grid">
            <div class="chart mdl-cell mdl-cell--4-col mdl-cell--4-col-desktop">
              <svg width="200px" height="200px" class="chart-1"></svg>
              <span>Eigene beantwortete Fragen</span>
            </div>
            <div class="chart mdl-cell mdl-cell--4-col mdl-cell--4-col-desktop">
              <svg width="200px" height="200px" class="chart-2"></svg>
              <span>Von Freunden beantwortete Fragen</span>
            </div>
            <div class="chart mdl-cell mdl-cell--4-col mdl-cell--4-col-desktop">
              <svg width="200px" height="200px" class="chart-3"></svg>
              <span>Persönliche Angaben</span>
            </div>
            <script>
              var pie1 = new PieChart({
                total: 8,
                initial: 0,
                color: '#00bcd4',
                radius: 30,
                strokeWidth: 8,
                container: '.chart-1'
              });
              var pie2 = new PieChart({
                total: 8,
                initial: 0,
                color: '#00bcd4',
                radius: 30,
                strokeWidth: 8,
                container: '.chart-2'
              });
              var pie3 = new PieChart({
                total: 8,
                initial: 0,
                color: '#00bcd4',
                radius: 30,
                strokeWidth: 8,
                container: '.chart-3'
              });
            </script>
          </div>
        </div>
      </div>
    </main>
  </div>
  <script src="/js/progress.js"></script>
  <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php
  else :
    if(login_check($mysqli) && $_SESSION['user']['is_admin']) : header('Location: ./admin/');
    else :
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <?php _getHead('frontend'); ?>
  </head>
  <body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <?php _getNav('root'); ?>
      </div>
      <main class="mdl-layout__content mdl-color--grey-100">
        <div class="container--fullsize mdl-color--blue-grey-900">
          <div>
            <img class="logo logo--center" src="/img/logo-cropped.png">
            <p class="mdl-typography--headline mdl-color-text--white">BvAsozial.de</p>
            <p class="mdl-typography--body-1 mdl-color-text--white">Eine Plattform der Abi-Zeitung des<br>Bettina-von-Arnim-Gymnasiums Dormagen</p>
            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--white" href="/anmelden/">
              Anmelden
            </a>
          </div>
        </div>
      </main>
    </div>
    <div class="mdl-snackbar mdl-js-snackbar">
      <div class="mdl-snackbar__text"></div>
      <button class="mdl-snackbar__action" type="button"></button>
    </div>
    <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php endif; endif; ?>
