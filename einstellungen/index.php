<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
  if(login_check($mysqli)) :
		$user = $_SESSION['user'];
		$_users = getUsers($mysqli);
?>
  <!doctype html>
  <html>
    <head>
      <?php _getHead('einstellungen'); ?>
    </head>
    <body>
      <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
        <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
          <?php require_once(ABS_PATH . '/_sections/drawer.php'); ?>
          <?php _getNav('einstellungen'); ?>
        </div>
        <main class="mdl-layout__content mdl-color--blue-grey-900">
          <div class="mdl-card container container--margin-top mdl-color--white mdl-shadow--2dp mdl-card--border">
            <h1 class="mdl-typography--display-1">Einstellungen</h1>
            <p class="mdl-typography--body-1">
              Hier kannst Du deine persönlichen Daten anpassen und deine Privatsphäre einstellen
            </p>
          </div>
          <div class="mdl-card container mdl-color--white mdl-shadow--2dp mdl-card--border">
            <h2 class="mdl-typography--display-2">Persönliche Daten</h2>
            <p class="mdl-typography--body-1">
              Deine Profildaten wie Name, E-Mail-Adresse und Benutzername.<br /><small>Du kannst deinen Benutzernamen <b>nicht</b> ändern, da damit viele wichtige Eigenschaften assoziiert sind, die sonst nicht mehr zu finden wären. Wir bitten dafür um Verständnis.</small>
            </p>
            <form class="form--personal-data" method="post">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
                    <input value="<?= $user['name']?>" name="name" id="textfieldname" class="mdl-textfield__input">
                    <label class="mdl-textfield__label" for="textfieldname">Dein Name</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
                    <input value="<?= $user['email']?>" name="email" id="textfieldemail" class="mdl-textfield__input">
                    <label class="mdl-textfield__label" for="textfieldname">Deine E-Mail-Adresse</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
                    <input value="<?= $user['uid']?>" name="uid" id="textfielduid" readonly class="mdl-textfield__input">
                    <label class="mdl-textfield__label" for="textfielduid">Dein Benutzername</label>
                </div>
                <div class="mdl-tooltip" for="textfielduid">
                    Deinen Benutzernamen haben wir festgelegt und ihn zu ändern würde nur Probleme machen.
                </div>
                <a href="change-password/" class="highlight">
                    <i class="material-icons">keyboard_arrow_right</i><span>Mein Passwort ändern</span>
                </a>
                <input type="hidden" value="<?php echo $_SESSION['user']['uid']?>" name="uid">
                <button type="button" class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
                  speichern
                </button>
            </form>
          </div>
          <div class="mdl-card container mdl-color--white mdl-shadow--2dp mdl-card--border">
            <h2 class="mdl-typography--display-2">Benachrichtigungs-Einstellungen</h2>
            <form class="form--notification-settings" method="post">
              <?php
                if($_SESSION['user']['allowedEmails']):
              ?>
              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowNotifications">
                <input type="radio" id="allowNotifications" class="mdl-radio__button" name="allowNotifications" value="1" checked>
                <span class="mdl-radio__label">Ja, ich möchte Benachrichtigungen über wichtige Änderungen an meinem Profil durch Moderatoren oder Administratoren, Änderungen an der Plattform, neue Funktionen oder anderweitige Informationen per E-Mail an <?php echo $_SESSION['user']['email']?> erhalten</span>
              </label>
              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="denyNotifications">
                <input type="radio" id="denyNotifications" class="mdl-radio__button" name="allowNotifications" value="0">
                <span class="mdl-radio__label">Nein, ich möchte keine Benachrichtungen über Änderungen an meinem Profil durch Moderatoren oder Administratoren, Änderungen an der Plattform, neue Funktionen oder anderweitige Informationen erhalten</span>
              </label>
              <?php
                else :
              ?>
              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowNotifications">
                <input type="radio" id="allowNotifications" class="mdl-radio__button" name="allowNotifications" value="1">
                <span class="mdl-radio__label">Ja, ich möchte Benachrichtigungen über wichtige Änderungen an meinem Profil durch Moderatoren oder Administratoren, Änderungen an der Plattform, neue Funktionen oder anderweitige Informationen per E-Mail an <?php echo $_SESSION['user']['email']?> erhalten</span>
              </label>
              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="denyNotifications">
                <input type="radio" id="denyNotifications" class="mdl-radio__button" name="allowNotifications" value="0" checked>
                <span class="mdl-radio__label">Nein, ich möchte keine Benachrichtungen über Änderungen an meinem Profil durch Moderatoren oder Administratoren, Änderungen an der Plattform, neue Funktionen oder anderweitige Informationen erhalten</span>
              </label>
              <?php
                endif;
               ?>
               <p class="mdl-typography--body-1">
                 <small>Du kannst diese Einstellung jederzeit ändern, wie Du möchtest. Bitte beachte, dass eine kleine Latenz zwischen dem Senden einer E-Mail und dem Ändern dieser Einstellung besteht. Solltest Du Änderungen abbestellen, kann es ein paar Minuten dauern, bis Du die letzte Mail von uns erhälst, da diese eventuell bereits vor dem Ändern der Einstellung losgesendet wurde.</small>
               </p>
               <input type="hidden" value="<?php echo $_SESSION['user']['uid']?>" name="uid">
              <button type="button" class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
                speichern
              </button>
            </form>
          </div>
          <div class="mdl-card container container--margin-bottom mdl-color--white mdl-shadow--2dp mdl-card--border">
            <h2 class="mdl-typography--display-2">Privatsphäre-Einstellungen</h2>
            <p class="mdl-typography--body-1">
              Wenn Du anpassen möchtest, wer dein Profil alles sehen darf, Rufnamen hinzufügen kann oder Fragen beantworten kann, dann kannst du das hier auswählen.<br /><small class="bemerkung">Bitte beachte, dass Moderatoren immer Zugriff auf dein Profil haben. Wenn du einen Moderator verdächtigst, seine Macht zu missbrauchen, kannst du ihn <a href="/mod/report/" target="_blank" class="highlight highlight--inline">hier melden</a>.<br />Beachte auch, dass eine Auswahl von "Nur Du" zu einem Einfrieren deiner Freundesfragen führt. Niemand deiner Freunde kann ihre/seine Antwort dann noch ändern oder anpassen. </small>
            </p>
            <form class="form--privacy-settings" method="post">
              <p class="mdl-typography--body-1">
                Für wen soll dein Profil sichtbar sein?
              </p>
              <?php
                switch($_SESSION['user']['privacySettings']) :
                  case "3" : ?>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowAll">
                    <input type="radio" id="allowAll" class="mdl-radio__button" name="allow" value="3" checked>
                    <span class="mdl-radio__label">Öffentlich</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowFriends">
                    <input type="radio" id="allowFriends" class="mdl-radio__button" name="allow" value="2">
                    <span class="mdl-radio__label">Nur meine Freunde</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowCustom">
                    <input type="radio" id="allowCustom" class="mdl-radio__button" name="allow" value="1">
                    <span class="mdl-radio__label">Benutzerdefinierte Liste (<a class="small" href="/einstellungen/privatsphaere/">Liste anpassen</a>)</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowNone">
                    <input type="radio" id="allowNone" class="mdl-radio__button" name="allow" value="0">
                    <span class="mdl-radio__label">Nur Ich</span>
                  </label>
                  <?php break;
                  case "2" : ?>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowAll">
                    <input type="radio" id="allowAll" class="mdl-radio__button" name="allow" value="3" >
                    <span class="mdl-radio__label">Öffentlich</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowFriends">
                    <input type="radio" id="allowFriends" class="mdl-radio__button" name="allow" value="2" checked>
                    <span class="mdl-radio__label">Nur meine Freunde</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowCustom">
                    <input type="radio" id="allowCustom" class="mdl-radio__button" name="allow" value="1">
                    <span class="mdl-radio__label">Benutzerdefinierte Liste (<a class="small" href="/einstellungen/privatsphaere/">Liste anpassen</a>)</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowNone">
                    <input type="radio" id="allowNone" class="mdl-radio__button" name="allow" value="0" >
                    <span class="mdl-radio__label">Nur Ich</span>
                  </label>
                  <?php break;
                  case "1" : ?>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowAll">
                    <input type="radio" id="allowAll" class="mdl-radio__button" name="allow" value="3">
                    <span class="mdl-radio__label">Öffentlich</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowFriends">
                    <input type="radio" id="allowFriends" class="mdl-radio__button" name="allow" value="2">
                    <span class="mdl-radio__label">Nur meine Freunde</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowCustom">
                    <input type="radio" id="allowCustom" class="mdl-radio__button" name="allow" value="1" checked>
                    <span class="mdl-radio__label">Benutzerdefinierte Liste (<a class="small" href="/einstellungen/privatsphaere/">Liste anpassen</a>)</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowNone">
                    <input type="radio" id="allowNone" class="mdl-radio__button" name="allow" value="0">
                    <span class="mdl-radio__label">Nur Ich</span>
                  </label>
                  <?php break;
                  case "0" : ?>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowAll">
                    <input type="radio" id="allowAll" class="mdl-radio__button" name="allow" value="3">
                    <span class="mdl-radio__label">Öffentlich</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowFriends">
                    <input type="radio" id="allowFriends" class="mdl-radio__button" name="allow" value="2">
                    <span class="mdl-radio__label">Nur meine Freunde</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowCustom">
                    <input type="radio" id="allowCustom" class="mdl-radio__button" name="allow" value="1">
                    <span class="mdl-radio__label">Benutzerdefinierte Liste (<a class="small" href="/einstellungen/privatsphaere/">Liste anpassen</a>)</span>
                  </label>
                  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="allowNone">
                    <input type="radio" id="allowNone" class="mdl-radio__button" name="allow" value="0" checked>
                    <span class="mdl-radio__label">Nur Ich</span>
                  </label>
                  <?php break;
                endswitch;
               ?>
              <input type="hidden" value="<?php echo $_SESSION['user']['uid']?>" name="uid">
              <button type="button" class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
                speichern
              </button>
            </form>
          </div>
        </main>
      </div>
      <script src="/js/settings.js"></script>
      <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>
  </html>
<?php else : header( 'Location: ../'); endif; ?>
