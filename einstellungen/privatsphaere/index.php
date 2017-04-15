<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
  if(login_check($mysqli) == true) :
    $json = json_decode(file_get_contents(ABS_PATH . "/users/data/{$_SESSION['user']['uid']}/{$_SESSION['user']['uid']}.json"), true);
?>
<!DOCTYPE html>
<html>
  <head>
    <?php _getHead('privatsphaere'); ?>
  </head>
  <body>
    <div class="mdl-layout__container">
      <div class="layout-wrapper">
        <header class="layout__header layout__header--small mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
          <img class="logo prime" src="/img/logo-cropped.png">
          <div class="header__inner">
            <p class="mdl-typography--headline header__title">
              Moderator suspendieren
            </p>
          </div>
        </header>
        <main class="page-content mdl-color--grey-100">
          <div class="mdl-card container mdl-color--white mdl-shadow--2dp">
            <div class="breadcrumb">
              <li class="breadcrumb__item">
                <a href="../../">Übersicht</a>
              </li>
              <li class="breadcrumb__item">
                <a href="../">Einstellungen</a>
              </li>
              <li class="breadcrumb__item">
                Privatsphäre anpassen
              </li>
            </div>
            <form action="./caul.php">
              <p class="mdl-typography--headline">Privatsphäre anpassen</p>
              <p class="mdl-typography--body-1">
                Wenn du speziell anpassen willst, wer dein Profil sehen kann, kannst du hier auswählen, welcher deiner Freunde auf dein Profil zugreifen kann. Kreuze einfach an, wen du es sehen lassen willst.
              </p>
              <ul class="friend-list">
                <?php
                  foreach($_SESSION['user']['freunde'] as $freund){
                    $freund = getMinimalUser($freund, $mysqli);
                    ?>
                      <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="<?= $freund['uid']?>">
                        <input class="mdl-checkbox__input" id="<?= $freund['uid']?>" type="checkbox" <?php echo (in_array($freund['uid'], $json['allowedUsers']) ? "checked" : "") ?> value="<?= $freund['uid']?>" name="<?= $freund['uid']?>">
                        <span class="mdl-checkbox__label"><?= $freund['name'] ?></span>
                      </label>
                    <?php
                  }
                ?>
              </ul>
              <button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect" type="button">
                Liste speichern
              </button>
            </form>
          </div>
        </main>
      </div>
    </div>
    <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    <script>
      $('.mdl-button').click(function(){
        $('#response').detach();
        $.ajax({
          data: $('form').serialize(),
          type: 'post',
          url: 'caul.php',
          success: function(data){
            try {
              var obj = JSON.parse(data);
              if(obj.success){
                $('form .mdl-button').detach();
                $('form').append($('<p>Liste wurde angepasst.</p>'));
                $('form').append($('<a></a>').addClass('mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect').attr('href','../').prepend($('<i></i>').addClass('material-icons').text('keyboard_arrow_left')).text('Zurück'));
                $('main').append($('<div></div>').addClass('mdl-card container mdl-color--white mdl-shadow--2dp').attr('response').append(obj.html));
              } else {
                $('main').append($('<div></div>').addClass('mdl-card container mdl-color--white mdl-shadow--2dp').attr('response').append($('<pre></pre>').text(data)));
              }
            } catch (e) {
              console.log(data);
            }
          },
          error: function(data){
            console.log(data);
          }
        })
      });
    </script>
  </body>
</html>
<?php else : header('Location: ../'); exit; endif; ?>
