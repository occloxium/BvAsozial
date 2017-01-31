<?php
  require_once('constants.php');
  require_once(ABS_PATH . INC_PATH . 'functions.php');

  secure_session_start();
  if(login_check($mysqli) && $_SESSION['user']['is_admin']) :
?>
<html>
  <head>
    <?php _getHead('admin'); ?>
  </head>
  <body>
    <div class="mdl-layout__container">
      <div class="layout-wrapper">
        <header class="layout__header layout__header--small mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
          <div class="header__inner">
            <p class="mdl-typography--headline header__title">
              Frage hinzufügen
            </p>
          </div>
        </header>
        <main class="page-content mdl-color--grey-100">
          <div class="mdl-card container mdl-color--white mdl-shadow--2dp">
            <form action="./rmaq.php" method="post">
              <p class="mdl-typography--headline">Frage hinzufügen</p>
              <p class="mdl-typography--body-1">
                Dieser Schritt kann nur sehr schwer bis gar nicht rückgängig gemacht werden. Wähle diesen Weg also nur mit Bedacht, zum Beispiel, wenn du der Plattform einen neuen Fragenkatalog verpassen willst. <br /><b>Alle Fragen in Benutzerprofilen werden hierbei entfernt, um eine Integrität der Daten zu gewährleisten. Ansonsten wären Änderungen der Fragen der Benutzer nicht mehr möglich.</b>
              </p>
              <button class="mdl-button mdl-js-button mdl-color--accent mdl-color-text--white mdl-js-ripple-effect" type="submit">
                Alle Fragen löschen
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
    <script src="/js/remove-all-question.js"></script>
    <script src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php else : header('Location: ../'); exit; endif;
?>
