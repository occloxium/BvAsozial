<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');

	include_once './_ajax/frage.php';

	secure_session_start();
	if(login_check($mysqli) == true) :
		$user = $_SESSION['user'];
?>
<!doctype html>
<html>
  <head>
  	<?php _getHead('fragen'); ?>
  </head>
  <body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="drawer-header">
          <img class="avatar" src="<?php echo '/users/' . $user['directory'] . '/avatar.jpg'?>">
          <div>
            <div class="flex-container">
              <span><?php echo $user['email']; ?></span>
              <div class="mdl-layout-spacer"></div>
            </div>
          </div>
        </header>
        <?php _getNav('fragen'); ?>
      </div>
      <main class="mdl-layout__content mdl-color--blue-grey-900" data-username="<?php echo $user['uid'] ?>" data-directory="<?php echo $user['directory'] ?>" data-friends="<?php echo $user['freundesanzahl'] ?>">
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
            <h1 class="mdl-typography--title">Meine eigenen Fragen<a class="highlight highlight--hover" href="./edit/eigene-fragen/"><i class="material-icons">edit</i></a></h1>
            <ol id="my-questions">
              <?php
								$path = "../users/{$user['directory']}/{$user['uid']}.json";
								$jsonstr = file_get_contents($path);
								$obj = json_decode($jsonstr, true);
								$obj = $obj['eigeneFragen'];
								$i = 1;
								foreach($obj as $frage){
									echo frage($frage, $user, null, $i, 0);
									$i++;
								}
							?>
            </ol>
            <button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect save-all">
							Alles abspeichern
						</button>
          </div>
          <?php
						$freunde = $user['freunde'];
					?>
            <div class="mdl-card fragen-container mdl-color--white mdl-shadow--2dp mdl-card--border" id="antworten-freunde">
              <h1 class="mdl-typography--title">Antworten meiner Freunde <a class="highlight highlight--hover" href="/fragen/edit/freundesfragen/"><i class="material-icons">edit</i></a></h1>
              <?php if(count($user['freunde']) > 0) : ?>
                <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                  <div class="page-navigation" data-for="antworten-freunde">
                  <?php
										if(count($user['freunde']) > 4){
											echo '<p class="mdl-typography--body-1">Du hast schon so viele Freunde geaddet, dass wir sie für dich einsortiert haben, um die Ordnung zu erhalten</p>';
											echo '<div class="flex-container">';
											if(isset($_GET['p1'])){
												$pR = ceil(count($user['freunde']) / 4);
												if($_GET['p1'] > $pR || $_GET['p1'] <= 0){
													$_GET['p1'] = 1;
												}
												$p = intval($_GET['p1']);
												for($i = 1; $i <= $pR; $i++){
													echo '<a class="navigation__link ' . ($i === $p ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p1=' . $i . '#antworten-freunde">' . $i . '</a>';
												}
											} else {
												$_GET['p1'] = 1;
												$pR = ceil(count($user['freunde']) / 4);
												for($i = 1; $i <= $pR; $i++){
													echo '<a class="navigation__link ' . ($i === 1 ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p1=' . $i . '#antworten-freunde">' . $i . '</a>';
												}
											}
											echo '</div>';
										} else {
                      $_GET['p1'] = 1;
                      $pR = ceil(count($user['freunde']) / 4);
                      for($i = 1; $i <= $pR; $i++){
                        echo '<a class="navigation__link ' . ($i === 1 ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p1=' . $i . '#antworten-freunde">' . $i . '</a>';
                      }
                    }
									?>
								</div>
                <div class="mdl-tabs__tab-bar">
                  <?php
										$p = intval($_GET['p1']);
										$range = [
											"min" => ($p - 1) * 4,
											"max" => $p * 4
										];
										$i = 1;
										$limitedFriends = array_slice($freunde, $range['min'], 4);
										foreach($limitedFriends as $freund){
											$freund = getUser($freund, $mysqli);
											echo '<a href="#' . replaceSpecialCharacters($freund['uid']) . '-panel" class="mdl-tabs__tab ' . ($i === 1 ? 'is-active' : '') . '">' . $freund['vorname'] . '</a>';
											$i++;
										}
									?>
                </div>
                <?php
									$path = "../users/{$user['uid']}/{$user['uid']}.json";
									$jsonstr = file_get_contents($path);
									$obj = json_decode($jsonstr, true);
									$obj = $obj['freundesfragen'];
									$i = 1;
									foreach($limitedFriends as $freund){
										$freund = getUser($freund, $mysqli);
										$j = 1;
										echo '<div class="mdl-tabs__panel ' . ($i === 1 ? 'is-active' : '') . '" id="' . replaceSpecialCharacters($freund['uid']) . '-panel"><ol>';
										foreach($obj as $frage){
											if(!isset($frage['antworten'][$freund['uid']])){
												$frage['antworten'][$freund['uid']] = null;
											}
											echo frage($frage, $user, $freund, $j, 1);
											$j++;
										}
										$i++;
										echo '</ol></div>';
									}
								?>
              </div>
            <?php else : ?>
              <p class="mdl-typography--body-1 no-friends">Nanu? Du hast noch keine Freunde, die deine Fragen beantworten könnten? Füge schnell welche hinzu: <a href="/freunde/">Freunde finden!</a></p>
            <?php endif; ?>
            </div>
            <div class="mdl-card fragen-container mdl-color--white mdl-shadow--2dp mdl-card--border" id="fragen-freunde">
              <h1 class="mdl-typography--title">Fragen meiner Freunde</h1>
              <?php if(count($user['freunde']) > 0) : ?>
                <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                  <div class="page-navigation" data-for="antworten-freunde">
                    <?php
  										if(count($user['freunde']) > 4){
  											echo '<p class="mdl-typography--body-1">Du hast schon so viele Freunde geaddet, dass wir sie für dich einsortiert haben, um die Ordnung zu erhalten</p>';
  											echo '<div class="flex-container">';
  											if(isset($_GET['p2'])){
  												$pR = ceil(count($user['freunde']) / 4);
  												if($_GET['p2'] > $pR || $_GET['p2'] <= 0){
  													$_GET['p2'] = 1;
  												}
  												$p = intval($_GET['p2']);
  												for($i = 1; $i <= $pR; $i++){
  													echo '<a class="navigation__link ' . ($i === $p ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p2=' . $i . '#fragen-freunde">' . $i . '</a>';
  												}
  											} else {
  												$_GET['p2'] = 1;
  												$pR = ceil(count($user['freunde']) / 4);
  												for($i = 1; $i <= $pR; $i++){
  													echo '<a class="navigation__link ' . ($i === 1 ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p2=' . $i . '#fragen-freunde">' . $i . '</a>';
  												}
  											}
  											echo '</div>';
  										} else {
                        $_GET['p2'] = 1;
                        $pR = ceil(count($user['freunde']) / 4);
                        for($i = 1; $i <= $pR; $i++){
                          echo '<a class="navigation__link ' . ($i === 1 ? 'navigation__link--selected mdl-color-text--primary' : '') . '" href="./index.php?p1=' . $i . '#antworten-freunde">' . $i . '</a>';
                        }
                      }
  									?>
                  </div>
                  <div class="mdl-tabs__tab-bar">
                    <?php
  										$p = intval($_GET['p2']);
  										$range = [
  											"min" => ($p - 1) * 4,
  											"max" => $p * 4
  										];
  										$i = 1;
  										$limitedFriends = array_slice($freunde, $range['min'], 4);
  										foreach($limitedFriends as $freund){
  											$freund = getUser($freund, $mysqli);
  											echo '<a href="#' . replaceSpecialCharacters($freund['uid']) . '-panel" class="mdl-tabs__tab ' . ($i === 1 ? 'is-active' : '') . '">' . $freund['vorname'] . '</a>';
  											$i++;
  										}
  									?>
                  </div>
                  <?php
  									$i = 1;
  									foreach($limitedFriends as $freund){
  										$freund = getUser($freund, $mysqli);
  										$path = "../users/{$freund['uid']}/{$freund['uid']}.json";
  										$jsonstr = file_get_contents($path);
  										$obj = json_decode($jsonstr, true);
  										$obj = $obj['freundesfragen'];
  										$j = 1;
  										echo '<div class="mdl-tabs__panel ' . ($i === 1 ? 'is-active' : '') . '" id="' . replaceSpecialCharacters($freund['uid']) . '-panel"><ol>';
  										foreach($obj as $frage){
  											if(!isset($frage['antworten'][$user['uid']])){
  												$frage['antworten'][$user['uid']] = "";
  											}
  											echo frage($frage, $user, $freund, $j, 2);
  											$j++;
  										}
  										$i++;
  										echo '</ol></div>';
  									}
  								?>
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
