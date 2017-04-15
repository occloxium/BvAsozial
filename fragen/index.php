<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	require_once(ABS_PATH.INC_PATH.'frage.php');

	secure_session_start();
	if(login_check($mysqli) == true) :
		$user = $_SESSION['user'];
    $path = ABS_PATH . "/users/data/{$user['directory']}/{$user['uid']}.json";
    $jsonstr = file_get_contents($path);
    $json = json_decode($jsonstr, true);
?>
<!doctype html>
<html>
  <head>
  	<?php _getHead('fragen'); ?>
  </head>
  <body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <?php require_once(ABS_PATH . '/_sections/drawer.php'); ?>
        <?php _getNav('fragen'); ?>
      </div>
      <main class="mdl-layout__content mdl-color--blue-grey-900" data-username="<?php echo $user['uid'] ?>">
        <section class="mdl-card fragen-container fragen-container--margin-top mdl-color--white mdl-shadow--2dp mdl-card--border">
          <p class="mdl-typography--headline">Fragen</p>
          <nav class="quicknav">
            <span>Sektionen</span>
            <a href="#meine-fragen">Meine eigenen Fragen</a>
            <a href="#antworten-freunde">Antworten meiner Freunde</a>
            <a href="#fragen-freunde">Fragen meiner Freunde</a>
          </nav>
        </section>
          <div class="mdl-card fragen-container mdl-color--white mdl-shadow--2dp mdl-card--border" id="meine-fragen">
            <h1 class="mdl-typography--title">Meine eigenen Fragen<a class="highlight highlight--inline" href="./edit/eigene-fragen/"><i class="material-icons">edit</i></a></h1>
            <ul>
              <?php
								$obj = $json['eigeneFragen'];
								$i = 1;
								foreach($obj as $frage){
									echo frage($frage, $user, $user, $i, 0);
									$i++;
								}
							?>
            </ul>
            <button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect save-all">
							Alles abspeichern
						</button>
          </div>
          <?php
						$freunde = filterUsers($user['uid'], $user['freunde'], $mysqli);
					?>
            <div class="mdl-card fragen-container mdl-color--white mdl-shadow--2dp mdl-card--border" id="antworten-freunde">
              <h1 class="mdl-typography--title">Antworten meiner Freunde <a class="highlight highlight--inline" href="/fragen/edit/freundesfragen/"><i class="material-icons">edit</i></a></h1>
              <ul>
                <?php
                  $i = 1;
                  $obj = $json['freundesfragen'];
                  foreach($obj as $frage){
  									echo frage($frage, $user, $freunde, $i, 1, "", true, true, true);
  									$i++;
  								}
                ?>
              </ul>
            </div>
            <?php
              /** TODO Elegantere Lösung als Subnavigation finden, um Freunde iterabel und managebar bei großer Anzahl zu machen */
            ?>
            <div class="mdl-card fragen-container mdl-color--white mdl-shadow--2dp mdl-card--border" id="fragen-freunde">
              <h1 class="mdl-typography--title">Fragen meiner Freunde</h1>
              <?php if(count($user['freunde']) > 0) : ?>
                <h6>
                  Meine Freunde:
                </h6>
                <ul class="mdl-list">
                  <?php
                    $i = 0;
                    foreach($freunde as $key=>$freund){
                      $friend = getUser($freund, $mysqli);
                      if($i === 0){
                        ?>
                        <label class="mdl-radio first uid mdl-js-radio mdl-js-ripple-effect" for="option-<?=$i?>">
                          <input type="radio" id="option-<?=$i?>" class="mdl-radio__button" data-uid="<?= $friend['uid'] ?>" value="1" checked>
                          <span class="mdl-radio__label"><?= $friend['name'] ?></span>
                        </label>
                        <?php
                      } else {
                        ?>
                        <label class="mdl-radio uid mdl-js-radio mdl-js-ripple-effect" for="option-<?=$i?>">
                          <input type="radio" id="option-<?=$i?>" class="mdl-radio__button" value="0">
                          <span class="mdl-radio__label"><?=$friend['vorname']?></span>
                        </label>
                        <?php
                      }
                      $i++;
                    }
                  ?>
                </ul>
                <div class="container--freundesfragen">

                </div>
              <?php else : ?>
                <p class="mdl-typography--body-1 no-friends">Nanu? Du hast noch keine Freunde, für die Du Fragen beantworten könntest! Füge schnell welche hinzu: <a href="/freunde/">Freunde finden!</a></p>
              <?php endif; ?>
            </div>
      		</main>
  			</div>
      <script src="/js/autogrow.js"></script>
      <script src="/js/fragen.js"></script>
      <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php else :
		header('Location: ../');
	endif; ?>
