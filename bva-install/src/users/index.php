<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();
	if(login_check($mysqli) == true) :
?>
  <html>
  <head>
    <?php _getHead('profil'); ?>
  </head>

  <body data-signedInUser="<?= $_SESSION['user']['uid']?>">
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="drawer-header">
          <img class="avatar" src="<?php echo "/users/{$_SESSION['user']['uid']}/avatar.jpg"?>">
          <div>
            <div class="flex-container">
            	<span><?php echo $_SESSION['user']['name']; ?></span>
              <div class="mdl-layout-spacer"></div>
            </div>
          </div>
        </header>
        <?php _getNav('profil'); ?>
      </div>
      <?php
				if(isset($_SERVER['PATH_INFO']) && str_replace('/','', $_SERVER['PATH_INFO']) != $_SESSION['user']['uid'] ) :
					$user = getUser(str_replace('/','',$_SERVER['PATH_INFO']), $mysqli);
					if(!isset($user['uid'], $user['name'])) :
						header('Location: ../');
					endif;
				else :
					$_SERVER['PATH_INFO'] = $_SESSION['user']['uid'];
					$user = $_SESSION['user'];
				endif;
			?>
      <main class="mdl-layout__content mdl-color--grey-100" data-name="<?php echo $user['name'] ?>" data-username="<?php echo $user['uid'] ?>">
        <?php
					$json_str = file_get_contents(ABS_PATH . "/users/{$user['uid']}/{$user['uid']}.json");
					$json = json_decode($json_str, true);
				?>
        <div class="mdl-grid margin--top">
          <div class="profile mdl-cell mdl-color--white mdl-shadow--2dp mdl-cell--12-col mdl-cell--12-col-desktop mdl-grid">
            <div class="image-column mdl-cell mdl-cell--6-col mdl-cell--6-col-desktop">
							<div class="avatar">
								<img class="avatar__image" src="<?php echo "/users/{$user['uid']}/avatar.jpg"?>">
								<?php
									if($_SESSION['user']['uid'] === $user['uid']) :
								?>
									<a href="/edit/me/profile-picture/?from=users" id="edit-picture" class="mdl-button mdl-js-button mdl-button--icon"><i class="material-icons">edit</i></a>
								<?php
									endif;
								?>
							</div>
						</div>
          	<div class="content-column mdl-cell mdl-cell--6-col mdl-cell--6-col-desktop">
              <div class="data-container">
                <header class="data-container__header">
                  <span class="header__prefix visuallyhidden">Profil von:</span>
                  <span class="header__title"><?php echo $user['name'] ?></span>
                </header>
								<div class="data-container__main">
                  <div id="rufnamen" class="data-section">
                    <span class="data-section__title">Auch bekannt als:</span>
                    <ul class="data-section__list">
                      <?php
												if(empty($json['rufnamen'])) :
													echo "<li class=\"list__item\" id=\"noname\">{$user['name']} ist noch anonym</li>";
												else :
													foreach($json['rufnamen'] as $name){
                          	if($_SESSION['user']['uid'] === $user['uid']){
                          		echo "<span class=\"mdl-chip mdl-chip--deletable\" data-name=\"$name\">
                              	<span class=\"mdl-chip__text\">$name</span>
                              	<button type=\"button\" class=\"mdl-chip__action\"><i class=\"material-icons\">cancel</i></button>
                              </span>";
                            } else {
                              echo "<span class=\"mdl-chip mdl-chip\">
                                <span class=\"mdl-chip__text\">$name</span>
                              </span>";
                            }
													}
													endif;
                          $befreundet = isFriendsWith($_SESSION['user']['uid'], $user['uid'], $mysqli);
                          if($befreundet) :	?>
                          	<button id="btnaddname" class="mdl-color-text--white mdl-color--primary data-section__button mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                              <i class="material-icons">add</i>
                              <span class="visuallyhidden">Rufnamen hinzufügen</span>
                            </button>
                            <div class="mdl-tooltip" for="btnaddname">
                              Rufnamen hinzufügen
                            </div>
                      <?php
                        endif;
                      ?>
                    </ul>
                  </div>
									<?php if(requestSent($_SESSION['user']['uid'], $user['uid'], $mysqli) || ($befreundet && !($_SESSION['user']['uid'] == $user['uid']))) :?>
										<div class="data-section data-section--border data-section--no-flex">
											<button class="mdl-button mdl-js-button" id="btnadddisabled" disabled>
												Als Freund hinzufügen
											</button>
											<div class="mdl-tooltip" for="btnadddisabled">
												Du bist mit der Person schon befreundet
											</div>
				  					</div>
				    			<?php elseif($_SESSION['user']['uid'] == $user['uid']) : echo ""; ?>
				    			<?php else : ?>
										<div class="data-section data-section--border data-section--no-flex">
											<button class="mdl-button mdl-js-button btnaddfriend">
												Als Freund hinzufügen
											</button>
										</div>
									<?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php if($_SESSION['user'] != $user) : ?>
        <div class="mdl-grid">
            <div class="mdl-cell personal-data mdl-color--white mdl-shadow--2dp mdl-cell--5-col mdl-cell--5-col-desktop">
                <p class="mdl-typography--headline">Persönliche Daten</p>
                <p class="mdl-typography--title">Freunde</p>
                <?php	if(count($user['freunde']) > 0) : ?>
                  <ul class="mdl-list list--border-bottom list--flex-spacer">
                    <?php
                    $key = 0;
                    foreach($user['freunde'] as $freund){
                      $friend = getUser($user['freunde'][$key], $mysqli);	?>
                        <li class="mdl-list__item">
                          <a class="mdl-list__item-primary-content" href="/users/index.php/<?= $friend['uid']?>">
                            <img src="/users/<?= $friend['uid']?>/avatar.jpg" class="mdl-list__item-avatar">
                              <?php echo $friend['name'] ?>
                          </a>
                        </li>
                    <?php
                    $key++;
                    if($key >= 5){
                      break;
                    }
                  } ?>
                	</ul>
                <?php else : ?>
                  <ul class="mdl-list list--no-entries list--border-bottom list--flex-spacer">
                    <p class="mdl-typography--body">
                    	<?php echo $user['vorname']?> hat noch keine Freunde hinzugefügt
										</p>
                  </ul>
                <?php endif; ?>
                <p class="mdl-typography--body-1">
                  <?php echo $user['name']?> ist seit dem
                  <?php echo date('d.m.Y', strtotime($user['registered_since'])) ?> registriert.
                </p>
                <p class="mdl-typography--body-1">
                  Seitdem hat
                  <?php echo $user['vorname'] . " " . $user['freundesanzahl'] ?> Personen als Freunde angenommen
                  <?php if($befreundet && $_SESSION['user'] != $user) : ?>, darunter auch du<?php endif; ?>.
                </p>
            </div>
          <?php if($befreundet) : ?>
            <div class="mdl-cell fragen befreundet mdl-color--white mdl-shadow--2dp mdl-cell--7-col mdl-cell--7-col-desktop">
              <p class="mdl-typography--headline">Fragen für die Freunde</p>
              <ol>
                <?php
                  $key = 0;
									foreach($json['freundesfragen'] as $fragenobjekt){
                    $i = $key + 1;
										$fragenobjekt = $json['freundesfragen'][$key];
										$frage = $fragenobjekt['frage'];
										$antwort = "";
										if(array_key_exists($_SESSION['user']['uid'], $fragenobjekt['antworten'])){
											$antwort = $fragenobjekt['antworten'][$_SESSION['user']['uid']];
										} else {
											$antwort = "";
										}
										require(ABS_PATH.INC_PATH.'frage.php');
                    $key++;
                    if($key >= 5){
                      break;
                    }
								} ?>
              </ol>
              <a class="fragen__link" href="/fragen/?user=<?php echo urlencode(base64_encode($user['uid']))?>">Weitere Fragen beantworten...</a>
            </div>
          <?php else : ?>
            <div class="mdl-cell fragen mdl-color--white mdl-shadow--2dp mdl-cell--7-col mdl-cell--7-col-desktop">
              <p class="mdl-typography--text-center mdl-typography--title">Du musst mit <?php echo $user['vorname'] ?> befreundet sein, um seine Fragen beantworten zu können</p>
            </div>
          <?php endif; ?>
        </div>
        <?php else : ?>
        <div class="mdl-grid">
            <div class="mdl-cell personal-data mdl-color--white mdl-shadow--2dp mdl-cell--12-col mdl-cell--12-col-desktop">
                <p class="mdl-typography--headline">Meine persönlichen Daten</p>
                <div class="mdl-grid personal-data__inner">
                    <div class="mdl-cell mdl-cell--8-col mdl-cell--8-col-desktop">
                        <p class="mdl-typography--title">Persönliche Angaben</p>
                        <div class="mdl-grid">
                            <form class="mdl-cell mdl-cell--6-col mdl-cell--6-col-desktop personal-data__form form--update-profile" action="/includes/updateUserProfile.php" method="post">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
                                    <input value="<?= $user['name']?>" name="name" id="textfieldname" class="mdl-textfield__input">
                                    <label class="mdl-textfield__label" for="textfieldname">Mein Name</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
                                    <input value="<?= $user['email']?>" name="email" id="textfieldemail" readonly class="mdl-textfield__input">
                                    <label class="mdl-textfield__label" for="textfieldname">Meine E-Mail-Adresse</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
                                    <input value="<?= $user['uid']?>" name="uid" id="textfielduid" readonly class="mdl-textfield__input">
                                    <label class="mdl-textfield__label" for="textfielduid">Mein Benutzername</label>
                                </div>
                                <div class="mdl-tooltip" for="textfielduid">
                                    Deinen Benutzernamen haben wir festgelegt und ihn zu ändern würde nur Probleme machen.
                                </div>
                                <a href="/users/me/change-password/" class="form__internal-link">
                                    <i class="material-icons">redo</i><span>Mein Passwort ändern</span>
                                </a>
                                <input type="hidden" value="<?php echo $_SESSION['user']['uid']?>" name="uid">
                                <button type="button" id="updateUserData" class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
																	Änderungen speichern
																</button>
                            </form>
                            <form class="mdl-cell mdl-cell--6-col mdl-cell--6-col-desktop flex-container flex-container--vertical" action="/includes/addQuote.php" method="post">
                                <input type="hidden" value="<?php echo $_SESSION['user']['uid']?>" name="uid">
                                <div class="flex-container quote-container">
																	<span class="intro">&#8220;</span>
																	<div class="mdl-textfield quote mdl-js-textfield">
																		<textarea wrap="soft" class="mdl-textfield__input" type="text" rows="1" id="textareaquote" name="quote"><?= $json['spruch'] ?></textarea>
																		<label class="mdl-textfield__label" for="textareaquote">Mein Spruch</label>
																	</div>
																	<span class="outro">&#8221;</span>
                                </div>
                                <button name="submit" type="button" id="addQuote" class="mdl-button mdl-button--raised mdl-js-button mdl-js-ripple-effect">
																	Spruch speichern
																</button>
                            </form>
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-desktop">
                        <p class="mdl-typography--title"><a class="link--no-highlight" href="/freunde/">Meine Freunde</a></p>
                        <ul class="mdl-list list--border-bottom list--flex-spacer">
                          <?php	if(count($user['freunde']) > 0) : ?>
                            <ul class="mdl-list list--border-bottom list--flex-spacer">
                              <?php
                              $key = 0;
                              foreach($user['freunde'] as $freund){
                                $friend = getUser($user['freunde'][$key], $mysqli);	?>
                                  <li class="mdl-list__item">
                                    <a class="mdl-list__item-primary-content" href="/users/index.php/<?= $friend['uid']?>">
                                      <img src="/users/<?= $friend['uid']?>/avatar.jpg" class="mdl-list__item-avatar">
                                        <?php echo $friend['name'] ?>
                                    </a>
                                  </li>
                              <?php
                              $key++;
                              if($key >= 5){
                                break;
                              }
                            } ?>
                            <li class="mdl-list__item">
															<a href="/freunde/meine-freunde/" class="mdl-button mdl-js-button mdl-button--icon" id="more-friends"><i class="material-icons">more_horiz</i></a>
                            </li>
                        </ul>
                        <?php else : ?>
                          <ul class="mdl-list list--no-entries list--border-bottom list--flex-spacer">
                            <p class="mdl-typography--body">
                              Du hast noch keine Freunde hinzugefügt
                            </p>
                          </ul>
                        <?php endif; ?>
                        <p class="mdl-typography--body-1">
                            Du bist seit dem
                            <?php echo date('d.m.Y', strtotime($user['registered_since'])) ?> registriert.<br> Seitdem hast Du
                            <?php echo $user['freundesanzahl'] ?> Personen als Freunde angenommen.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mdl-cell fragen befreundet mdl-color--white mdl-shadow--2dp mdl-cell--12-col mdl-cell--12-col-desktop">
                <p class="mdl-typography--title">Meine Fragen</p>
                <p class="mdl-typography--body-1">
                  Du kannst hier deine eigenen Fragen beantworten. Die findest du auch unter <a href="/fragen/#meine-fragen">Fragen</a>. <br />
                  Freundesfragen kannst du ebenfalls von dort bearbeiten.
                </p>

                    <?php
											require(ABS_PATH . '/fragen/_ajax/frage.php');
											$j = 1;
                      if(count($json['eigeneFragen']) > 0) :
                        echo "<ol>";
                        foreach($json['eigeneFragen'] as $frage){
  												if(!isset($frage['antwort'])){
  													$frage['antwort'] = null;
  												}
  												echo frage($frage, $user, null, $j, 0);
  												$j++;
  											}
                        echo "</ol>";
                      else :
                        ?>
                        <ul class="mdl-list list--no-entries list--border-bottom list--flex-spacer">
                          <p class="mdl-typography--body">
                            Du hast noch keine Fragen ausgewählt. Wähle welche aus! <a href="/fragen/edit/eigene-fragen">Eigene Fragen bearbeiten</a>
                          </p>
                        </ul>
                        <?php
                      endif;
										?>
                </ol>
              </div>
          	</div>
          </div>
        </div>
        <?php endif; ?>
      </main>
      </div>
      <div class="mdl-js-snackbar mdl-snackbar" data-success="">
          <div class="mdl-snackbar__text"></div>
          <button class="mdl-snackbar__action" type="button"></button>
      </div>
      <script src="/js/autogrow.js"></script>
      <script src="/js/users.js"></script>
      <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
  <?php else :
		header("Location: /index.php");
	endif; ?>
