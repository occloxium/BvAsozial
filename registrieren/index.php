<?php
  require('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start(); // (2)

  // if an already registered user trys to register again whilst being logged in, redirect him immediately
  if(login_check($mysqli)){ // (1a)
    header("Location: ../"); exit;
  }
  if(!isset($_SESSION['is_registering'])) : // (43
    unset($_POST);
    ?>
    <!doctype html>
    <html>
    	<head>
    		<?php _getHead('registrierung'); ?>
    	</head>
    	<body>
    		<div class="mdl-layout__container">
    			<div class="layout-wrapper">
    				<header class="layout__header layout__header--small mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
    					<img class="logo prime" src="/img/logo-cropped.png">
    					<div class="header__inner">
    						<p class="mdl-typography--headline header__title">
    							Herzlich Willkommen!
    						</p>
    						<p class="mdl-typography--title header__subtitle">
    							Bestätige deine Einladung zum BvAsozial-Netzwerk, um fortzufahren
    						</p>
    					</div>
    					<div id="global-progress" class="mdl-progress mdl-js-progress mdl-color--grey-100"></div>
    				</header>
    				<main class="page-content mdl-color--grey-100">
    					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
    						<form action="./index.php" method="post">
    							<input type="hidden" name="step" value="0">
    							<p class="mdl-typography--headline">Hallo!</p>
    							<p class="mdl-typography--body-1">
    								Trage hier bitte die Anmeldedaten ein, die wir dir geschickt haben, um fortzufahren und dein Profil einzurichten
    							</p>
    							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    								<input type="text" class="mdl-textfield__input" id="uid" name="uid">
    								<label for="uid" class="mdl-textfield__label">Benutzername oder E-Mail-Adresse</label>
    							</div>
    							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    								<input type="password" class="mdl-textfield__input" id="password" name="password">
    								<label for="password" class="mdl-textfield__label">Passwort</label>
    							</div>
    							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect" type="submit">
    								Anmelden
    							</button>
    						</form>
    					</div>
    				</main>
    			</div>
    		</div>
    		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    		<script src="/js/registrieren.js"></script>
        <?php
          if(isset($_GET['bva_1'], $_GET['bva_2'])) :
            // Versuche, Benutzer anzumelden
            echo "<script>attemptLogin(atob('{$_GET['bva_1']}'), atob('{$_GET['bva_2']}'));</script>";
          endif;
        ?>
    	</body>
    </html>
    <?php
  else : // (4)
    if(isValidInvite($_SESSION['registering']['uid'], $_SESSION['registering']['password'], $mysqli)) :
      $user = getInvitedUser($_SESSION['registering']['uid'], $mysqli);
      if(isset($_POST['steps']) && $_POST['steps'] == $_SESSION['registering']['steps'] + 1){
        $_SESSION['registering']['steps']++;
      }
      switch($_SESSION['registering']['steps']):
        case 0 :
          ?>
          <!doctype html>
          <html>
          	<head>
          		<?php _getHead('registrierung'); ?>
          	</head>
          	<body>
          		<div class="mdl-layout__container">
          			<div class="layout-wrapper">
          				<header class="layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
          					<img class="logo prime" src="/img/logo-cropped.png">
          					<div class="header__inner">
          						<p class="mdl-typography--headline header__title">
          							Überprüfung
          						</p>
          						<p class="mdl-typography--title header__subtitle">
          							Schritt 1 von 4 ...
          						</p>
          					</div>
          				</header>
          				<main class="page-content mdl-color--grey-100">
          					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
          						<form action="./index.php" method="post">
          							<input type="hidden" value="1" name="step">
          							<p class="mdl-typography--headline">Hallo <?php echo $user['vorname'] ?>!</p>
          							<p class="mdl-typography--body-1">
          								Bitte überprüfe deine persönlichen Daten. Wir haben uns Mühe gegeben, sie korrekt einzutragen, aber Fehler schleichen sich immer ein
          							</p>
          							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
          								<input type="text" class="mdl-textfield__input" value="<?php echo $user['name']?>" id="name" name="name">
          								<label for="name" class="mdl-textfield__label">Voller Name</label>
          							</div>
          							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
          								<input type="text" class="mdl-textfield__input" id="email" value="<?php echo $user['email'] ?>" name="email">
          								<label for="email" class="mdl-textfield__label">E-Mail-Adresse</label>
          							</div>
          							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect" type="submit">
          								Weiter <i class="material-icons">keyboard_arrow_right</i>
          							</button>
                        <input name="steps" value="1" type="hidden" hidden />
          						</form>
          					</div>
          				</main>
          			</div>
          		</div>
          		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
          		<script src="/js/registrieren.js"></script>
          	</body>
          </html>
          <?php
          break;
        case 1 :
          ?>
          <!doctype html>
          <html>
          	<head>
          		<?php _getHead('registrierung'); ?>
          	</head>
          	<body>
          		<div class="mdl-layout__container">
          			<div class="layout-wrapper">
          				<header class="layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
          					<img class="logo prime" src="/img/logo-cropped.png">
          					<div class="header__inner">
          						<p class="mdl-typography--headline header__title">
          							Personalisierung
          						</p>
          						<p class="mdl-typography--title header__subtitle">
          							Schritt 2 von 4 ...
          						</p>
          					</div>
          				</header>
          				<main class="page-content mdl-color--grey-100">
          					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
          						<form action="index.php" method="post" enctype="multipart/form-data">
          							<p class="mdl-typography--headline">
          								Dein Avatar!
          							</p>
          							<p class="mdl-typography--body-1">
          								Wenn du möchtest, dass deine Freunde dich sofort an einem Profilbild erkennen, kannst du hier dein eigenes hochladen.
                          Das Bild wird, solltest du kein anderes an das Team übermitteln, für die Abizeitung benutzt.
          							</p>
                        <input type="hidden" name="MAX_FILE_SIZE" value="80000" />
          							<input type="file" hidden class="avatar" accepts="image/*" name="avatar">
          							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
          								Weiter <i class="material-icons">keyboard_arrow_right</i>
          							</button>
                        <input name="steps" value="2" type="hidden" hidden />
          						</form>
          					</div>
          				</main>
          			</div>
          		</div>
          		<script src="/js/avatar.registration.js"></script>
          		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
          		<script src="/js/registrieren.js"></script>
          	</body>
          </html>
          <?php
          break;
        case 2 :
          // handles potential file upload
          if(isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) :
            $target = ABS_PATH . "/users/{$_SESSION['registering']['uid']}/avatar.jpg";
            $filename = basename($_FILES['avatar']['name']);

            if(strpos("image/jpg;image/jpeg;image/png;image/gif;image/bmp", strtolower($_FILES['avatar']['type'])) != false) :
              if(convertImage($_FILES['avatar'], $target)) :
                ?>
                <!doctype html>
                <html>
                	<head>
                		<?php _getHead('registrierung'); ?>
                	</head>
                	<body>
                		<div class="mdl-layout__container">
                			<div class="layout-wrapper">
                				<header class="layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
                					<img class="logo prime" src="/img/logo-cropped.png">
                					<div class="header__inner">
                						<p class="mdl-typography--headline header__title">
                							Personalisierung
                						</p>
                						<p class="mdl-typography--title header__subtitle">
                							Schritt 3 von 4 ...
                						</p>
                					</div>
                				</header>
                				<main class="page-content mdl-color--grey-100">
                					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
                						<form action="index.php" method="post" enctype="multipart/form-data">
                							<p class="mdl-typography--headline">
                								Dein Avatar!
                							</p>
                							<p class="mdl-typography--body-1">
                                Dein Avatar wurde erfolgreich geändert. Du erscheinst jetzt so:
                							</p>
                              <div class="image-block">
                                <img class="avatar__image" src="<?php echo "/users/{$_SESSION['registering']['uid']}/avatar.jpg"?>">
                              </div>
                							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
                								Weiter <i class="material-icons">keyboard_arrow_right</i>
                							</button>
                              <input name="steps" value="1" type="hidden" hidden />
                						</form>
                					</div>
                				</main>
                			</div>
                		</div>
                		<script src="/js/avatar.registration.js"></script>
                		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
                		<script src="/js/registrieren.js"></script>
                	</body>
                </html>
                <?php
              else : error('internalError', 500, 'Error while creating the image. No changes to the avatar where made');
              endif;
              ?>
              <?php
            else :
              $_SESSION['registering']['step'] = 0;
              ?>
              <!doctype html>
              <html>
                <head>
                  <?php _getHead('registrierung'); ?>
                </head>
                <body>
                  <div class="mdl-layout__container">
                    <div class="layout-wrapper">
                      <header class="layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
                        <img class="logo prime" src="/img/logo-cropped.png">
                        <div class="header__inner">
                          <p class="mdl-typography--headline header__title">
                            Personalisierung
                          </p>
                          <p class="mdl-typography--title header__subtitle">
                            Schritt 3 von 4 ...
                          </p>
                        </div>
                      </header>
                      <main class="page-content mdl-color--grey-100">
                        <div class="mdl-card container mdl-color--white mdl-shadow--2dp">
                          <form action="index.php" method="post" enctype="multipart/form-data">
                            <p class="mdl-typography--headline">
                              Dein Avatar!
                            </p>
                            <p class="mdl-typography--body-1">
                              Die von Dir hochgeladene Datei wird nicht unterstützt. Probiere es bitte erneut mit jpg, jpeg oder png.
                              <small>Für die ganz freakigen: gif und bmp (wer benutzt das überhaupt noch?) werden auch (noch) unterstützt.</small>
                            </p>
                            <div class="image-block">
                              <img class="avatar__image" src="<?php echo "/users/{$_SESSION['registering']['uid']}/avatar.jpg"?>">
                            </div>
                            <a href="./" class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
                              Zurück <i class="material-icons">keyboard_arrow_right</i>
                            </a>
                            <input name="steps" value="2" type="hidden" hidden />
                          </form>
                        </div>
                      </main>
                    </div>
                  </div>
                  <script src="/js/avatar.registration.js"></script>
                  <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
                  <script src="/js/registrieren.js"></script>
                </body>
              </html>
          <?php
            endif;
          else : ?>
          <!doctype html>
          <html>
            <head>
              <?php _getHead('registrierung'); ?>
            </head>
            <body>
              <div class="mdl-layout__container">
                <div class="layout-wrapper">
                  <header class="layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
                    <img class="logo prime" src="/img/logo-cropped.png">
                    <div class="header__inner">
                      <p class="mdl-typography--headline header__title">
                        Personalisierung
                      </p>
                      <p class="mdl-typography--title header__subtitle">
                        Schritt 3 von 4 ...
                      </p>
                    </div>
                  </header>
                  <main class="page-content mdl-color--grey-100">
                    <div class="mdl-card container mdl-color--white mdl-shadow--2dp">
                      <form action="index.php" method="post" enctype="multipart/form-data">
                        <p class="mdl-typography--headline">
                          Dein Avatar!
                        </p>
                        <p class="mdl-typography--body-1">
                          Du hast deinen Avatar noch nicht geändert. Das ist kein Problem, das kannst du später immer noch über dein Profil machen.
                        </p>
                        <button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
                          Weiter <i class="material-icons">keyboard_arrow_right</i>
                        </button>
                        <input name="steps" value="3" type="hidden" hidden />
                      </form>
                    </div>
                  </main>
                </div>
              </div>
              <script src="/js/avatar.registration.js"></script>
              <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
              <script src="/js/registrieren.js"></script>
            </body>
          </html>
          <?php
          endif;
          break;
        case 3 :
          ?>
          <!doctype html>
          <html>
          	<head>
          		<?php _getHead('registrierung'); ?>
          	</head>
          	<body>
          		<div class="mdl-layout__container">
          			<div class="layout-wrapper">
          				<header class="layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
          					<img class="logo prime" src="/img/logo-cropped.png">
          					<div class="header__inner">
          						<p class="mdl-typography--headline header__title">
          							Fragenauswahl
          						</p>
          						<p class="mdl-typography--title header__subtitle">
          							Schritt 4 von 4 ...
          						</p>
          					</div>
          				</header>
          				<main class="page-content mdl-color--grey-100">
          					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
          					<p class="mdl-typography--headline">
          						Fragenkatalog
          					</p>
          					<input type="hidden" value="2" name="step">
          					<p class="mdl-typography--body-1">
          						Jetzt ans Eingemachte! Wähle deine Fragen aus, deine eigenen und die für deine Freunde. Maximal jedoch 8 aus jeder Kategorie. Du kannst deine Fragen auch später noch ändern.
                    </p>
          					<form class="form--fill-wrapper" method="post" action="./index.php">
          						<p class="mdl-typography--title">Fragen für Dich</p>
          						<table class="fragenkatalog-table mdl-shadow--2dp" id="eigeneFragen">
          							<?php
          								$jsonstr = file_get_contents('fragenkatalog.json');
          								$obj = json_decode($jsonstr, true);
          								echo "<tbody>";
                          $type = 'e';
                          foreach($obj['eigeneFragen'] as $index=>$frage) {
                            $num = $index + 1;
                            echo require(ABS_PATH . INC_PATH . 'fragen.registrierung.php');
          								}
          								echo "</tbody>";
          							?>
          						</table>
          						<p class="mdl-typography--title">Fragen für deine Freunde</p>
          						<table class="fragenkatalog-table mdl-shadow--2dp" id="freundesfragen">
          							<?php
          								echo "<tbody>";
                          $type = 'f';
          								foreach($obj['freundesfragen'] as $index=>$frage) {
                            $num = $index + 1;
                            echo require(ABS_PATH . INC_PATH . 'fragen.registrierung.php');
          								}
          								echo "</tbody>";
          							?>
          						</table>
          						<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
          							Weiter <i class="material-icons">keyboard_arrow_right</i>
          						</button>
                      <input name="steps" value="4" type="hidden" hidden />
          					</form>
          					</div>
          				</main>
          			</div>
          		</div>
          		<div class="mdl-snackbar mdl-js-snackbar">
          			<div class="mdl-snackbar__text"></div>
          			<button class="mdl-snackbar__action" type="button"></button>
          		</div>
          		<script src="/js/fragen.registrierung.js"></script>
          		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
          	</body>
          </html>
          <?php
          break;
        case 4 :
          $user = $_SESSION['registering'];
          // iterate over $_POST and add questions to <user>.json
          $obj = json_decode(file_get_contents('fragenkatalog.json'), true);

          $userfile = json_decode(file_get_contents("../users/{$user['uid']}/{$user['uid']}.json"), true);

          $userfile['freundesfragen'] = [];
          $userfile['eigeneFragen'] = [];
          $userfile['spruch'] = "";
          $userfile['rufnamen'] = [];

          foreach($_POST as $key=>$element){
            if(preg_match("/^[ef]-frage-[1-9][0-9]*/", $key)){
              if(substr($key, 0, 1) == "f"){
                // Freundesfrage
                $num = intVal(substr($key,8));
                $userfile['freundesfragen'][] = [
                  "frage" => $obj['freundesfragen'][$num - 1],
                  "antworten" => []
                ];
              }
              else if(substr($key, 0, 1) == "e"){
                // Eigene Frage
                $num = intVal(substr($key,8));
                $userfile['eigeneFragen'][] = [
                  "frage" => $obj['eigeneFragen'][$num - 1],
                  "antwort" => ""
                ];
              }
            }
          }
          // Save changes
          if(file_put_contents(ABS_PATH . "/users/{$user['uid']}/{$user['uid']}.json", json_encode($userfile, JSON_PRETTY_PRINT)) <= 0){
            exit;
          }

          // Create user entries in Database
          $query = "INSERT INTO person (name, uid, directory, registered_since) VALUES ('{$user['name']}','{$user['uid']}','{$user['uid']}', CURRENT_DATE);";
          $mysqli->query($query);
          $query = "INSERT INTO login (uid, password, email) VALUES ('{$user['uid']}', '{$user['password']}', '{$user['email']}');";
          $mysqli->query($query);
          // remove invite
          // Remove invite after successfully inserting user into db
          $mysqli->query("DELETE FROM ausstehende_einladungen WHERE uid = '{$user['uid']}'");
          // clear session array of potentially dangerous user data

          // Bypass login with settings still in place
          if(login($user['uid'], $user['password'], $mysqli)){
            unset($_SESSION['registering']);
            header('Location: ../index.php');
            exit;
          } else {
            unset($_SESSION['registering']);
            header('Location: ../anmelden/index.php');
            exit;
          }
        break;
        default : unset($_SESSION); unset($_POST); unset($_GET); header('Location: ./'); break;// Post wurde manipuliert. Lösche alle daten aus Request und starte neu
      endswitch;
    else :
      // Einladung ist abgelaufen. Lösche alle bisherigen Daten aus dem Request
      unset($_SESSION); unset($_POST); unset($_GET); header('Location: ./');
    endif;
  endif;
?>
