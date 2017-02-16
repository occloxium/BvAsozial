<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
  if(login_check($mysqli) == true && $_SESSION['user']['is_mod']) :
?>
<!DOCTYPE html>
<html>
  <head>
    <?php _getHead('mod'); ?>
  </head>
  <body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="drawer-header">
          <img class="avatar" src="<?php echo '/users/' . $_SESSION['user']['uid'] . '/avatar.jpg'?>">
        </header>
        <?php _getNav('moderation'); ?>
      </div>
      <main class="mdl-layout__content mdl-color--blue-grey-900">
        <div class="mdl-card container container--margin-top mdl-color--white mdl-shadow--2dp mdl-card--border">
          <h3>Moderation</h3>
          <p class="mdl-typography--body-1">
            Du als Moderator genießt besondere Privilegien und kannst die Eingaben von anderen Benutzern ändern, zum Beispiel um sie zu filtern, zu säubern, auf Anfrage zu ändern und so weiter. <br />
            <b>Aber sei gewarnt! Mit großer Macht kommt große Verantwortung!</b>
          </p>
        </div>
        <div class="mdl-card container mdl-color--white mdl-shadow--2dp mdl-card--border">
          <h3>Benutzer finden</h3>
          <form class="search-form" method="get">
            <div class="mdl-textfield mdl-js-textfield">
              <input type="text" class="mdl-textfield__input" name="search" id="search">
              <label for="search" class="mdl-textfield__label">Freund finden...</label>
            </div>
            <button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
              <i class="material-icons">search</i>
            </button>
          </form>
          <script>
              (function() {
                  $.ajax({
                      url: '_ajax/init.php',
                      method: 'get'
                  }).done(function(data) {
                    try {
                      var obj = JSON.parse(data);
                      if(obj.success){
                        $('.benutzer-container ul').append(obj.output);
                      } else {
                        console.log(data);
                      }
                    } catch(e){
                      console.error(data);
                    }
                  });
              }());
          </script>
        </div>
        <div class="benutzer-container mdl-card container mdl-card--border mdl-color--white mdl-shadow--2dp">
          <ul>

          </ul>
        </div>
      </main>
    </div>
    <script src="/js/search.mod.js"></script>
    <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php else : header('Location: ../'); exit; endif; ?>
