<?php
	include_once '../../includes/functions.php';
	include_once '../../includes/db_connect.php';
	secure_session_start();
	if(login_check($mysqli) == true) :
		$user = getUser($_SESSION['username'], $mysqli);
?>
    <!doctype html>
    <html>

    <head>
        <?php _getHead('meine-freunde.freunde') ?>
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
                <?php _getNav('meine-freunde'); ?>
            </div>
            <main class="mdl-layout__content mdl-color--grey-100" data-username="<?php echo $_SESSION['username'] ?>" data-directory="<?php echo $user['directory'] ?>" meta-friends="">
                <div class="mdl-card container--top-margin mdl-color--white mdl-shadow--2dp" id="meine-anfragen">
                    <p class="mdl-typography--title element--margin-bottom element--border-bottom">Meine Freunde</p>
                    <form class="search-form" method="get">
                        <div class="mdl-textfield mdl-js-textfield">
                            <input type="text" class="mdl-textfield__input" name="search" id="search">
                            <label for="search" class="mdl-textfield__label">Freund finden...</label>
                        </div>
                        <button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
							<i class="material-icons">search</i>
						</button>
                    </form>
                    <script>
                        (function() {
                            $.ajax({
                                url: './_ajax/init.php',
                                method: 'get'
                            }).done(function(data) {
                                $('.freunde-container ul').append(data);
                            });
                        }());

                    </script>
                </div>
                <div class="mdl-card container mdl-color--white mdl-shadow--2dp freunde-container">
                    <ul class="mdl-list">

                    </ul>
                </div>
            </main>
        </div>
        <script src="/js/meine-freunde.freunde.js"></script>
        <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>

    </html>
    <?php else :
				header('Location: ../../');
			endif; ?>
