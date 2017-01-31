<?php
	require_once('constants.php');
  require_once(ABS_PATH . INC_PATH . 'functions.php');

  secure_session_start();

	if(login_check($mysqli)) :
		$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
	<head>
		<?php _getHead('registrierung'); ?>
	</head>
	<body>
		<div class="mdl-layout__container">
			<div class="layout-wrapper">
        <header class="layout__header layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
          <img class="logo prime" src="/img/logo-cropped.png">
          <div class="header__inner">
            <p class="mdl-typography--headline header__title">
              Eigene Fragen anpassen
            </p>
          </div>
        </header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
						<form class="form--fill-wrapper" method="post" action="./uf.php">
							<p class="mdl-typography--title">Eigene Fragen</p>
							<p class="mdl-typography--body-1">Hier kannst du deine eigenen Fragen anpassen, solltest du dir vorher unsicher gewesen sein</p>
							<b class="">Wenn du vorher bereits beantwortete Fragen abwählst, verfallen deine Antworten für diese Fragen! Dieser Schritt kann nicht von uns rückgängig gemacht werden!</b>
							<div>&nbsp;</div><div>&nbsp;</div>
							<table class="fragenkatalog-table mdl-shadow--2dp" id="freundesfragen">
								<?php
									$user_fragen = json_decode(file_get_contents(ABS_PATH . "/users/{$user['uid']}/{$user['uid']}.json"), true);
									$obj = json_decode(file_get_contents(ABS_PATH . '/registrieren/fragenkatalog.json'), true);
									echo "<tbody>";
									foreach($obj['eigeneFragen'] as $key=>$frage) {
										$num = $key + 1;
										$found = false;
                    if($user_fragen != false){
                        foreach($user_fragen['eigeneFragen'] as $user_frage){
                    			if(stripos($frage, $user_frage['frage']) !== false){
                    				$found = true;
                    			}
                    		}
                    }
										echo '<tr>
    												<td>
    													<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="e-frage-' . $num .'">
    														<input type="checkbox" data-category="eigeneFragen" id="e-frage-' . $num .'" class="mdl-checkbox__input" name="e-frage-' . $num .'" ' . ($found ? 'checked' : '') .  '>
    													</label>
    												</td>
    												<td class="frage">' . $frage . '</td>
    											</tr>';
									}
									echo "</tbody>";
								?>
							</table>
							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect">
								Änderungen speichern
							</button>
					</form>
					<a style="max-width: 240px; margin: 2em auto; display: block;" class="mdl-button mdl-js-button mdl-color--accent mdl-color-text--white mdl-js-ripple-effect" href="../../">
						Zurück zu meinen Fragen
					</a>
					</div>
				</main>
			</div>
		</div>
		<div class="mdl-snackbar mdl-js-snackbar">
			<div class="mdl-snackbar__text"></div>
			<button class="mdl-snackbar__action" type="button"></button>
		</div>
		<script src="/js/eigeneFragen.edit.fragen.js"></script>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>

<?php else : header('Location: ../'); exit;
			endif; ?>
