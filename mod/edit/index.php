<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
  if(login_check($mysqli) && $_SESSION['user']['is_mod'] && isset($_GET['_'])) :
    $user = getUser($_GET['_'], $mysqli);
    $json_str = file_get_contents(ABS_PATH . "/users/{$user['uid']}/{$user['uid']}.json");
		$json = json_decode($json_str, true);
 ?>
<!doctype html>
<html>
  <head>
    <?php _getHead('edit.mod'); ?>
  </head>
  <body data-mod="<?php echo $_SESSION['user']['uid'] ?>">
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="drawer-header">
          <img class="avatar" src="<?php echo '/users/' . $_SESSION['user']['uid'] . '/avatar.jpg'?>">
        </header>
        <?php _getNav('moderation'); ?>
      </div>
      <main class="mdl-layout__content mdl-color--blue-grey-900" data-username="<?= $user['uid'] ?>">
        <div class="daten-container mdl-card container container--margin-top mdl-color--white mdl-shadow--2dp mdl-card--border">
          <div class="flex-container">
            <div class="flex-container--left">
              <p>Profil von </p>
              <h3><?= $user['name']?></h3>
              <span><?= $user['email']?></span>
              <span><?= $user['uid']?></span>
            </div>
            <div class="flex-container--right">
                <img class="avatar avatar--profile" src="<?php echo '/users/' . $user['uid'] . '/avatar.jpg'?>">
            </div>
          </div>
        </div>
        <div class="rufnamen-container mdl-card container mdl-color--white mdl-shadow--2dp mdl-card--border">
          <h4>Rufnamen</h4>
          <form class="form--rufnamen">
            <ul>
              <?php
               echo rufnamenliste($user['uid'], $_SESSION['user']['uid'], $json['rufnamen'], $mysqli);
              ?>
              <button class="rufnamen__add mdl-color-text--white mdl-color--primary mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" type="button">
                <i class="material-icons">add</i>
                <span class="visuallyhidden">Rufnamen hinzufügen</span>
              </button>
            </ul>
            <div class="mdl-tooltip" for="btnaddname">
              Rufnamen hinzufügen
            </div>
            <button type="button" class="mdl-button save-all mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white">
              Änderungen speichern
            </button>
          </form>
        </div>
        <div class="fragen-container mdl-card container mdl-card--border mdl-color--white mdl-shadow--2dp">
          <h4><?= $user['vorname']; ?>s Fragen</h4>
          <form class="form--eigeneFragen">
            <ul>
              <?php
                require(ABS_PATH . INC_PATH . 'frage.php');
                $i = 1;
                $obj = $json['eigeneFragen'];
                foreach($obj as $frage){
									echo frage($frage, $user, null, $i, 0, $user['vorname']);
									$i++;
								}
              ?>
            </ul>
            <button type="button" class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect save-all">
              Alles speichern
            </button>
          </form>
        </div>
        <div class="fragen-container fragen-container--freundesfragen mdl-card container mdl-card--border mdl-color--white mdl-shadow--2dp">
          <h4><?= $user['vorname']; ?>s Freundesfragen</h4>
          <form class="form--freundesfragen">
            <ul>
              <?php
                $i = 1;
                $obj = $json['freundesfragen'];
                foreach($obj as $frage){
									echo frage($frage, $user, $user['freunde'], $i, 2, "", true, true);
									$i++;
								}
              ?>
            </ul>
            <button type="button" class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect save-all">
              Alles speichern
            </button>
          </form>
        </div>
      </main>
    </div>
    <script defer src="/js/edit.mod.js"></script>
    <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php else :
    header('Location: ../'); exit;
  endif; ?>
