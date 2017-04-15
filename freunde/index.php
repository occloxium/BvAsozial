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
      <?php _getHead('freunde'); ?>
    </head>
    <body>
      <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
        <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
          <?php require_once(ABS_PATH . '/_sections/drawer.php'); ?>
          <?php _getNav('freunde'); ?>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100" meta-username="<?php echo $user['uid'] ?>" meta-directory="<?php echo $user['uid'] ?>">
          <div class="mdl-card container container--margin-top mdl-color--white mdl-shadow--2dp mdl-card--border">
            <h3>Nach Freunden suchen</h3>
            <form class="flex-container" action="./index.php" method="get">
              <?php if(isset($_GET['search'])) : ?>
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">
                <input class="mdl-textfield__input" type="text" id="search" name="search" value="<?= $_GET['search']?>">
                <label class="mdl-textfield__label" for="search">Suche nach Freunden...</label>
              </div>
              <?php else : ?>
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="search" name="search">
                <label class="mdl-textfield__label" for="search">Suche nach Freunden...</label>
              </div>
              <?php endif; ?>
              <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
      					<i class="material-icons">search</i>
      				</button>
            </form>
          </div>
          <?php
					if(isset($_GET['search'])) :
						$found = [];
						$rest = [];
						while($row = $_users->fetch_array(MYSQLI_BOTH)){
							if(stripos($row['name'], $_GET['search']) !== false){
								if($row['uid'] === $_SESSION['user']['uid']){
									continue;
								} else {
									$found[] = $row;
								}
							} else {
								if($row['uid'] === $_SESSION['user']['uid']){
									continue;
								} else {
									$rest[] = $row;
								}
							}
						}
	          if(empty($found)) : ?>
              <div class="mdl-card container mdl-color--white mdl-shadow--2dp mdl-card--border" id="noResults">
                <div class="flex-container failure">
                  <div class="flex-container__item flex-container__primary-item">
                    <i class="material-icons mdl-color-text--accent">accessibility</i>
                  </div>
                  <div class="flex-container__item flex-container__secondary-item">
                    <h1 class="failure__title mdl-color-text--accent">Oh nein!</h1>
                    <p class="failure__subtitle">Wir konnten den von dir eingegeben Namen nicht in unserer schlauen Tabelle voller Namen finden. Entweder hast du dich schlicht vertippt oder der von dir gesuchte Benutzer ist uns noch unbekannt. In diesem Fall erinnere ihn doch bitte daran, sich zu registrieren!</p>
                  </div>
                </div>
              </div>
            <?php else : ?>
              <div class="mdl-card container mdl-color--white mdl-shadow--2dp mdl-card--border" id="found">
                <p class="section__title">Auf der Suche nach <b>"<?php echo $_GET['search']?>"</b> haben wir gefunden:</p>
                <ul class="mdl-list">
                  <?php foreach(filterUsers($_SESSION['user']['uid'], $found, $mysqli) as $person){ ?>
                  <li class="mdl-list__item">
                    <a href="/users/index.php/<?= $person['uid']?>/" class="mdl-list__item-primary-content">
      								<img src="/users/data/<?= $person['uid']?>/avatar.jpg" class="mdl-list__item-avatar">
      								<span class="name mdl-color-text--black"><?= $person['name']?></span>
      							</a>
                  </li>
                  <?php	}	?>
                </ul>
              </div>
            <?php endif; ?>
              <div class="mdl-card container friend-list__container--bottom mdl-color--white mdl-shadow--2dp" id="rest">
                <p class="section__title">Weitere Menschen, die deine Freunde sein könnten:</p>
                <ul class="mdl-list">
                  <?php foreach(filterUsers($_SESSION['user']['uid'], $rest, $mysqli) as $person){ ?>
                  <li class="mdl-list__item">
                    <a href="/users/index.php/<?= $person['uid']?>/" class="mdl-list__item-primary-content">
  										<img src="/users/data/<?= $person['uid']?>/avatar.jpg" class="mdl-list__item-avatar">
  										<span class="name mdl-color-text--black"><?= $person['name']?></span>
  									</a>
                  </li>
                  <?php } ?>
                </ul>
              </div>
            <?php
      				else :
      					$users = [];
      					while($row = $_users->fetch_array(MYSQLI_BOTH)){
      						if($row['uid'] !== $_SESSION['user']['uid']){
      							$users[] = $row;
      						}
      					}
              ?>
                <div class="mdl-card container friend-list__container--bottom mdl-color--white mdl-shadow--2dp" id="rest">
                  <p class="section__title">Menschen, die deine Freunde sein könnten:</p>
                  <ul class="mdl-list">
                    <?php
        							if(empty($users)){
    								?>
                      <li class="no-entries">
                        Nanu? Keiner da?! <br>Mobilisiere deine Freunde, damit sie sich schnellstmöglich anmelden!
                      </li>
                    <?php
      							} else {
      								foreach(filterUsers($_SESSION['user']['uid'], $users, $mysqli) as $person){ ?>
                        <li class="mdl-list__item">
                            <a href="/users/index.php/<?= $person['uid']?>/" class="mdl-list__item-primary-content">
          										<img src="/users/data/<?= $person['uid']?>/avatar.jpg" class="mdl-list__item-avatar">
          										<span class="name mdl-color-text--black"><?= $person['name']?></span>
          									</a>
                        </li>
                      <?php
                      }
                    } ?>
                  </ul>
                </div>
              <?php
	           endif;
            ?>
        </main>
      </div>
      <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>
  </html>
<?php else : header( 'Location: ../'); endif; ?>
