<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/constants.php';
	include_once ROOT . '/includes/functions.php';
	include_once ROOT . '/includes/db_connect.php';
	secure_session_start();
	if(login_check($mysqli) == true) :
		$logged_in_user = getUser($_SESSION['username'], $mysqli);
?>
    <html lang="de">
		<!-- Profile page -->
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
        <title>BvAsozial</title>
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="icon" sizes="192x192" href="images/android-desktop.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="BvAsozial">
        <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">
        <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
        <meta name="msapplication-TileColor" content="#252830">

        <link rel="shortcut icon" href="images/favicon.png">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <link rel="stylesheet" href="/css/bvasozial.mdl.src.css">
        <link rel="stylesheet" href="/css/sidewide.css">
        <link rel="stylesheet" href="/css/profiles.css">
        <script src="/js/jquery-2.1.4.min.js"></script>
        <script src="/js/autogrow.js"></script>
    </head>

    <body data-signedInUser="<?= $logged_in_user['uid']?>">
        <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
            <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
                <header class="drawer-header">
                    <img class="avatar" src="<?php echo '/users/' . $logged_in_user['directory'] . '/avatar.jpg'?>">
                    <div>
                        <div class="flex-container">
                            <span><?php echo $logged_in_user['name']; ?></span>
                            <div class="mdl-layout-spacer"></div>
                        </div>
                    </div>
                </header>
                <?php get_nav('users'); ?>
            </div>
            <?php
							if(isset($_SERVER['PATH_INFO'])) :
								$user = getUser(str_replace('/','',$_SERVER['PATH_INFO']), $mysqli);
								if(!isset($user['uid'], $user['name'])) :
									header('Location: ../'); // Fehler bei der Benutzerabfrage über PATH_INFO aufgetreten. Renavigiere zum Oberverzeichnis
								endif;
							else :
								$_SERVER['PATH_INFO'] = $_SESSION['username'];
								$user = $logged_in_user;
							endif;
						?>
            <main class="mdl-layout__content mdl-color--grey-100" data-name="<?php echo $user['name'] ?>" data-username="<?php echo $user['uid'] ?>" data-directory="<?php echo $user['directory'] ?>" meta-friends="<?php echo 2; ?>">
                <?php
									$json_str = file_get_contents(ROOT . "/users/" . $user['uid'] . "/" . $user['uid'] . ".json");
									$json = json_decode($json_str, true);
								?>
                <div class="mdl-grid margin--top">
                    <div class="profile mdl-cell mdl-color--white mdl-shadow--2dp mdl-cell--12-col mdl-cell--12-col-desktop mdl-grid">
                        <div class="image-column mdl-cell mdl-cell--6-col mdl-cell--6-col-desktop">
													<div class="avatar">
														<img class="avatar__image" src="<?php echo '/users/' . $user['directory'] . '/avatar.jpg'?>">
														<?php
															if($logged_in_user['uid'] === $user['uid']) :
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
                                                  if($logged_in_user['uid'] === $user['uid']){ ?>
                                                      <span class="mdl-chip mdl-chip--deletable" data-name="<?php echo $name ?>">
                                                          <span class="mdl-chip__text"><?php echo $name ?></span>
                                                          <button type="button" class="mdl-chip__action"><i class="material-icons">cancel</i></button>
                                                      </span>
                                                  <?php } else { ?>
                                                      <span class="mdl-chip mdl-chip">
                                                          <span class="mdl-chip__text"><?php echo $name ?></span>
                                                      </span>
                                                  <?php }
																										}
																									endif;
                                                $befreundet = isFriendsWith($logged_in_user['uid'], $user['uid'], $mysqli);
                                                if($befreundet) :	?>
                                                    <button id="btnaddname" class="mdl-color-text--white mdl-color--primary data-section__button mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                                                        <i class="material-icons">add</i>
                                                        <span class="visuallyhidden">Rufnamen hinzufügen</span>
                                                    </button>
                                                    <div class="mdl-tooltip" for="btnaddname">
                                                        Rufnamen hinzufügen
                                                    </div>
                                                <?php endif; ?>
                                        </ul>
                                    </div>
									<?php if(requestSent($logged_in_user['uid'], $user['uid'], $mysqli) || ($befreundet && !($logged_in_user['uid'] == $user['uid']))) :?>
									<div class="data-section data-section--border data-section--no-flex">
										<button class="mdl-button mdl-js-button" id="btnadddisabled" disabled>
											Als Freund hinzufügen
										</button>
										<div class="mdl-tooltip" for="btnadddisabled">
											Du bist mit der Person schon befreundet
										</div>
								  	</div>
								    <?php elseif($logged_in_user['uid'] == $user['uid']) : echo ""; ?>
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
                <?php if($logged_in_user != $user) : ?>
                <div class="mdl-grid">
                    <div class="mdl-cell personal-data mdl-color--white mdl-shadow--2dp mdl-cell--5-col mdl-cell--5-col-desktop">
                        <p class="mdl-typography--headline">Persönliche Daten</p>
                        <p class="mdl-typography--title">Freunde</p>
                        <?php	if($user['freundesanzahl'] > 0) : ?>
                            <ul class="mdl-list list--border-bottom list--flex-spacer">
                                <?php
									for($i = 0; $i < 5; $i++){
										if($user['freunde'][$i] != null){
											$friend = getUser($user['freunde'][$i], $mysqli);	?>
                                    <li class="mdl-list__item">
                                        <a class="mdl-list__item-primary-content" href="/users/index.php/<?= $friend['uid']?>">
													<img src="/users/<?= $friend['directory']?>/avatar.jpg" class="mdl-list__item-avatar">
													<?php echo $friend['name'] ?>
												</a>
                                    </li>
                                    <?php }} ?>
                            </ul>
                            <?php else : ?>
                            <ul class="mdl-list list--no-entries list--border-bottom list--flex-spacer">
                                <p class="mdl-typography--body">
                                    <?php echo $user['vorname']?> hat noch keine Freunde hinzugefügt</p>
                            </ul>
                            <?php endif; ?>
                            <p class="mdl-typography--body-1">
                                <?php echo $user['name']?> ist seit dem
                                <?php echo date('d.m.Y', strtotime($user['registered_since'])) ?> registriert.
                            </p>
                            <p class="mdl-typography--body-1">
                                Seitdem hat
                                <?php echo $user['vorname'] . " " . $user['freundesanzahl'] ?> Personen als Freunde angenommen
                                <?php if($befreundet && $logged_in_user != $user) : ?>, darunter auch du
                                <?php endif; ?>.
                            </p>
                    </div>
                    <?php if($befreundet) : ?>
                    <div class="mdl-cell fragen befreundet mdl-color--white mdl-shadow--2dp mdl-cell--7-col mdl-cell--7-col-desktop">
                        <p class="mdl-typography--headline">Fragen für die Freunde</p>
                        <ol>
                            <?php
										for($i = 1; $i <= 3; $i++) {
											$fragenobjekt = $json['freundesfragen'][$i - 1];
											$frage = $fragenobjekt['frage'];
											$antwort = "";
											if(array_key_exists($logged_in_user['uid'], $fragenobjekt['antworten'])){
												$antwort = $fragenobjekt['antworten'][$logged_in_user['uid']];
											} else {
												$antwort = "";
											}
											echo <<<FRAGE
<li class="frage">
	<b>$frage</b>
	<div class="flex-container">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty is-focused">
			<input type="text" class="mdl-textfield__input" value="$antwort" id="item-$i" data-item="$i">
			<label class="mdl-textfield__label" for="item-$i">Deine Antwort</label>
		</div>
		<button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-for="{$user['uid']}" data-item="$i" data-category="freundesfragen" data-freund="{$user['uid']}">
			<i class="material-icons">save</i>
		</button>
	</div>
</li>
FRAGE;
									} ?>
                        </ol>
                        <a class="fragen__link" href="/fragen/?user=<?php echo urlencode(base64_encode($user['uid']))?>">Weitere Fragen beantworten...</a>
                    </div>
                    <?php else : ?>
                    <div class="mdl-cell fragen mdl-color--white mdl-shadow--2dp mdl-cell--7-col mdl-cell--7-col-desktop">
                        <p class="mdl-typography--text-center mdl-typography--title">Du musst mit
                            <?php echo $user['vorname'] ?> befreundet sein, um seine Fragen beantworten zu können</p>
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
                                        <input type="hidden" value="<?php echo $_SESSION['username']?>" name="uid">
                                        <button type="button" id="updateUserData" class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
																					Änderungen speichern
																				</button>
                                    </form>
                                    <form class="mdl-cell mdl-cell--6-col mdl-cell--6-col-desktop flex-container flex-container--vertical" action="/includes/addQuote.php" method="post">
                                        <input type="hidden" value="<?php echo $_SESSION['username']?>" name="uid">
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
                                    <?php
																			for($i = 0; $i < 5; $i++){
																				if($user['freunde'][$i] != null){
																					$friend = getUser($user['freunde'][$i], $mysqli);	?>
																					<li class="mdl-list__item">
																						<a class="mdl-list__item-primary-content" href="/users/index.php/<?= $friend['uid']?>">
																							<img src="/users/<?= $friend['directory']?>/avatar.jpg" class="mdl-list__item-avatar">
																							<?php echo $friend['name'] ?>
																						</a>
																					</li>
                                        <?php }} ?>
                                        <li class="mdl-list__item">
																					<a href="/freunde/meine-freunde/" class="mdl-button mdl-js-button mdl-button--icon" id="more-friends"><i class="material-icons">more_horiz</i></a>
                                        </li>
                                </ul>
                                <p class="mdl-typography--body-1">
                                    Du bist seit dem
                                    <?php echo date('d.m.Y', strtotime($user['registered_since'])) ?> registriert.<br> Seitdem hast Du
                                    <?php echo $user['freundesanzahl'] ?> Personen als Freunde angenommen.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-cell fragen befreundet mdl-color--white mdl-shadow--2dp mdl-cell--12-col mdl-cell--12-col-desktop">
                        <p class="mdl-typography--title">Meine Freundesfragen</p>
                        <p class="mdl-typography--body-1">
                            Die Fragen, die Du Deinen Freunden über Dich gestellt habe. <br> Dies ist eine verkürzte Darstellung, wundere Dich daher nicht, wenn nicht alle Deine Freunde auftauchen.<br>Unter <a href="/fragen/#antworten-freunde"><b>Fragen</b></a> werden Dir alle Antworten deiner Freunde angezeigt.
                        </p>
                        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                            <div class="mdl-tabs__tab-bar">
					                  <?php
															$freunde = [];
															for($i = 1; $i <= 4; $i++){
																$friend = $user['freunde'][$i-1];
																if($friend != null) :
																	$freund = getUser($friend, $mysqli);
																	$freunde[] = $freund;
																	$friend_url = replaceSpecialCharacters($freund['uid']);
																	if($i == 1){
																		echo "<a href=\"#$friend_url-panel\" class=\"mdl-tabs__tab is-active\">{$freund['name']}</a>";
																	} else {
																		echo "<a href=\"#$friend_url-panel\" class=\"mdl-tabs__tab\">{$freund['name']}</a>";
																	}
																endif;
															}
														?>
                            <a href="/fragen/index.php#antworten-freunde" class="mdl-tabs__tab--external-link"><i class="material-icons">more_horiz</i></a>
                            </div>
                            <?php
															if(count($freunde) == 0) :
														?>
                                <p class="mdl-typography--text-center mdl-typography--title">Du musst erst mit Personen befreundet sein, damit sie Deine Fragen beantworten können</p>
                                <?php
																	else :
																	for($i = 1; $i <= 4; $i++){
																		$freund = $freunde[$i-1];
																		if($freund != null) :
																			if($i == 1){ ?>
                                    <div class="mdl-tabs__panel is-active" id="<?php echo replaceSpecialCharacters($freund['uid'])?>-panel">
                                        <?php
																					} else { ?>
                                        <div class="mdl-tabs__panel" id="<?php echo replaceSpecialCharacters($freund['uid'])?>-panel">
                                            <?php } ?>
                                            <ol>
                                                <?php
																									include_once $_SERVER['DOCUMENT_ROOT'] . '/fragen/_ajax/frage.php';
																									$j = 1;
																									foreach($json['freundesfragen'] as $frage){
																										if(!isset($frage['antworten'][$freund['uid']])){
																											$frage['antworten'][$freund['uid']] = null;
																										}
																										echo frage($frage, $user, $freund, $j, 1);
																										$j++;
																									}
																								?>
                                            </ol>
                                        </div>
                                        <?php
																					endif;
																				}
																			endif;
																		?>
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
        <script src="/js/users.js"></script>
        <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>

    </html>
    <?php else :
			header("Location: /index.php");
		endif; ?>
