
<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php')
 ?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <?php _getHead('anmelden'); ?>
  </head>
  <body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <?php _getNav('login'); ?>
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
