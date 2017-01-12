<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');

  secure_session_start();
  if(login_check($mysqli) == true && $_SESSION['user']['is_admin'] == true) :
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <?php _getHead('admin'); ?>
  </head>
  <body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
		    <?php _getNav('/'); ?>
      </div>
      <main class="mdl-layout__content mdl-color--grey-100">
				<div class="mdl-card mdl-color--white mdl-shadow--2dp container container--margin-top container--wide">
					<h1 class="mdl-typography--headline">Statistik</h1>
						<div id="users">
							<?php // user statistics
                $nums = [];
                if($mysqli->multi_query('SELECT COUNT(uid) AS registrierte_benutzer FROM person; SELECT COUNT(uid) AS ausstehende_einladungen FROM ausstehende_einladungen; SELECT COUNT(uid) AS akzeptierte_freundschaftsanfragen FROM freunde; SELECT COUNT(von) AS versendete_anfragen FROM anfragen;')){
                  do {
                    if($result = $mysqli->store_result()){
                      while ($row = $result->fetch_row()) {
                        $nums[] = $row[0];
                      }
                    }
                  } while($mysqli->next_result());
                  ?>
                  <p>Registrierte Benutzer: <?php echo $nums[0] ?></p>
                  <p>Ausstehende Einladungen: <?php echo $nums[1] ?></p>
                  <p>Akzeptierte Freundschaftsanfragen: <?php echo $nums[2] ?></p>
                  <p>Versendete Freundschaftsanfragen: <?php echo $nums[3] ?></p>
                  <?php
                } else {
                  ?>
                  <p>
                    Momentan stehen keine Statistiken bereit.
                  </p>
                  <?php
                }
              ?>
						</div>
					</div>
          <div class="mdl-card mdl-color--white mdl-shadow--2dp container container--wide">
            <div id="kürzliche-registrierungen">
              <p class="mdl-typography--headline">Kürzliche Registrierungen</p>
              <ul class="mdl-list">
                <?php
                  $query = 'SELECT * FROM person WHERE registered_since + INTERVAL 3 DAY > UTC_DATE()';
                  if (!($stmt = $mysqli->prepare($query))) :
                    echo 'Es ist ein Fehler aufgetreten!';
                  else :
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows < 1) :
                ?>
                <p class="none">Keine kürzlichen Registrierungen. <a class="none__anchor" href="./register-user/">Lade neue Leute ein!</a></p>
                <?php
                  else :
                    $stmt->bind_result($id, $name, $uid, $erhaltene_anfragen, $gesendete_anfragen, $verzeichnis, $datum);
                    while ($stmt->fetch()) :
                ?>
              	<li class="mdl-list__item mdl-list__item--two-line">
                  <span class="mdl-list__item-primary-content">
										<i class="material-icons mdl-list__item-avatar">person</i>
										<span><?php echo $name ?></span>
                  	<span class="mdl-list__item-sub-title"><?php echo $uid ?></span>
                  </span>
                  <span class="mdl-list__item-secondary-content">
										<button id="<?php echo 'extra-'.$uid ?>" class="mdl-button mdl-js-button mdl-button--icon">
											<i class="material-icons">more_vert</i>
										</button>
										<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="<?php echo 'extra-'.$uid ?>">
											<li class="mdl-menu__item">Name: <?php echo $name ?></li>
											<li class="mdl-menu__item">Benutzername: <?php echo $uid ?></li>
										</ul>
									</span>
              	</li>
                <?php
                      endwhile;
                      $stmt->close();
                    endif;
                  endif;
                ?>
              </ul>
            </div>
            <div id="ausstehende-registrierungen">
              <p class="mdl-typography--headline">Ausstehende Registrierungen</p>
              <ul class="mdl-list">
                <?php
                  $query = 'SELECT * FROM ausstehende_einladungen LIMIT 10';
                  if (!($stmt = $mysqli->prepare($query))) :
                    echo 'Es ist ein Fehler aufgetreten!';
                  else :
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows < 1) :
                ?>
                  <p class="none">Keine ausstehenden Registrierungen. <a href="./register-user/" class="none__anchor">Lade neue Leute ein!</a></p>
                  <?php
                    else :
                      $stmt->bind_result($id, $uid, $password, $name, $email, $directory);
                      while ($stmt->fetch()) :
                  ?>
                  <li class="mdl-list__item mdl-list__item--two-line">
                      <span class="mdl-list__item-primary-content">
      									<i class="material-icons mdl-list__item-avatar">person</i>
      									<span><?php echo $name?></span>
                      	<span class="mdl-list__item-sub-title"><?php echo $uid ?></span>
                      </span>
                      <span class="mdl-list__item-secondary-content">
      									<button id="<?php echo 'extra-'.$uid ?>" class="mdl-button mdl-js-button mdl-button--icon">
      										<i class="material-icons">more_vert</i>
      									</button>
      									<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="<?php echo 'extra-'.$uid ?>">
      										<li class="mdl-menu__item">Name: <?php echo $name ?></li>
      										<li class="mdl-menu__item">E-Mail: <?php echo $email ?></li>
      										<li class="mdl-menu__item">Benutzername: <?php echo $uid ?></li>
      										<li class="mdl-menu__item">Password: <?php echo $password ?></li>
      									</ul>
      								</span>
                  	</li>
                  <?php
                      endwhile;
                      $stmt->close();
                    endif;
                  endif;
                  ?>
                </ul>
              </div>
            </div>
          </main>
        </div>
      <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php else : header('Location: ../anmelden/'); exit; endif; ?>
