<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');
  secure_session_start();
  if(login_check($mysqli) == true && $_SESSION['user']['is_admin']) :
?>
<!DOCTYPE html>
<html>
  <head>
    <?php _getHead('admin'); ?>
    <script src="/js/jquery-2.1.4.min.js"></script>
    <script src="/js/autogrow.js"></script>
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
            <form action="./aq.php" method="post">
              <p class="mdl-typography--headline">Frage hinzufügen</p>
              <p class="mdl-typography--body-1">
                Wähle aus, ob es sich um eine Frage für Freunde oder für die Person selbst handeln soll oder um beides:
              </p>
              <div class="form__group" id="type">
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="eigeneFragen">
                  <input type="checkbox" id="eigeneFragen" name="eigeneFragen" class="mdl-checkbox__input" checked>
                  <span class="mdl-checkbox__label">Eigene Frage</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="freundesfragen">
                  <input type="checkbox" id="freundesfragen" name="freundesfragen" class="mdl-checkbox__input">
                  <span class="mdl-checkbox__label">Freundesfrage</span>
                </label>
              </div>
              <p class="mdl-typography--body-1">
                Gebe deine Frage ein:
              </p>
              <div class="mdl-textfield mdl-js-textfield">
                <textarea class="mdl-textfield__input" rows="1" name="frage" id="frage"></textarea>
                <label class="mdl-textfield__label" for="frage">
                  Frage...
                </label>
              </div>
              <button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect" type="submit">
                Frage hinzufügen
              </button>
            </form>
          </div>
        </main>
      </div>
    </div>
    <script>
      (function(){
        $('textarea').autogrow({onInitialize: true});
      })();
    </script>
    <script src="/admin/js/add-question.js"></script>
    <script src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php else : header("Location: ../"); exit; endif; ?>
