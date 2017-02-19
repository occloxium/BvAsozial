<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
	secure_session_start();
	if(login_check($mysqli) == true && $_SESSION['user']['is_admin']) :
?>
<!DOCTYPE html>
<html>
	<head>
		<?php _getHead('add-mod.admin') ?>
	</head>
	<body>
		<div class="mdl-layout__container">
			<div class="layout-wrapper">
				<header class="layout__header mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
					<img class="logo prime" src="/img/logo-cropped.png">
					<div class="header__inner">
						<p class="mdl-typography--headline header__title">
							Moderator hinzufügen
						</p>
					</div>
				</header>
				<main class="page-content mdl-color--grey-100">
					<div class="mdl-card container mdl-color--white mdl-shadow--2dp">
            <div class="breadcrumb">
              <li class="breadcrumb__item">
                <a href="../../../">Admin</a>
              </li>
              <li class="breadcrumb__item">
                <a href="../../">Benutzer verwalten</a>
              </li>
              <li class="breadcrumb__item">
                <a href="../">Moderatoren verwalten</a>
              </li>
              <li class="breadcrumb__item">
                Moderatoren ernennen
              </li>
            </div>
            <p class="mdl-typography--headline">Moderator hinzufügen</p>
            <p class="mdl-typography--body-1">
              Wähle die Person aus, die Du zum Moderator machen möchtest.
            </p>
            <form>
              <div class="form--search">
                <div class="mdl-textfield mdl-js-textfield">
                  <input type="text" class="mdl-textfield__input" name="search" id="search">
                  <label for="search" class="mdl-textfield__label">Benutzer suchen...</label>
                </div>
                <button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                  <i class="material-icons">search</i>
                </button>
              </div>
            </form>
						<form class="list" action="./am.php">
              <div class="list--users">
                <ul>
                  <?php
                    // Gets a mysqli_result object of all non-mod users
                    $users = getUsers($mysqli, "SELECT * FROM person INNER JOIN moderatoren ON moderatoren.boundTo != person.uid");
                    if($users->num_rows == 0) :
                    ?>
                      <li class="none">
                        Es existieren keine Benutzer, die Moderatoren werden könnten
                      </li>
                    <?php
                    else :
                      while($user = $users->fetch_assoc()):
                      ?>
                      <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-<?= $user['uid'] ?>">
                        <input type="checkbox" value="" name="<?= $user['uid'] ?>" id="checkbox-<?= $user['uid'] ?>" class="mdl-checkbox__input">
                        <span class="mdl-checkbox__label"><?= $user['name']?></span>
                      </label>
                      <?php
                      endwhile;
                    endif;
                   ?>
                </ul>
              </div>
              <p class="warnung">
                <i class="material-icons">warning</i>
                <span>
                  Ein Moderator hat Zugriff auf alle relevanten Daten der Benutzerprofile und kann diese entsprechend auch verändern. Kurz gesagt: Er / Sie hat Zugriff auf alle Daten dieser Plattform, die von Benutzern eingegeben werden (Auch zu den privatesten Fragen). Seine / Ihre Aufgabe ist es aber lediglich, dafür zu sorgen, dass diese Antworten, von wem auch immer, Rufnamen etc. "lesbar" / "druckbar", d.h. Rechtschreibung, wo Offensichtliches falsch ist, unangemessene Inhalt löschen und so weiter. Diese Benutzerdaten werden, anders als Datenbank-Daten, nicht täglich gesichert und Änderungen sind deswegen sehr schwer bis gar nicht reversibel. Bedenke also immer: <br /><b>Mit großer Macht kommt große Verantwortung!</b>
                </span>
              </p>
							<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect" type="button">
								Moderator ernennen
							</button>
						</form>
					</div>
				</main>
			</div>
		</div>
    <script>
      $('form.list button').click(function(){
        $.ajax({
          type: 'post',
          url: 'am.php',
          data: $('form.list').serialize()
        }).done(function(data){
          try {
            var obj = JSON.parse(data);
            $('form.list button, .warnung').detach();
            if(obj.success){
              $('main').append($('<div></div>').addClass('log mdl-card container mdl-color--white mdl-shadow--2dp').append($('<pre></pre>').append(obj.log)));
            } else {
              $('main').append($('<div></div>').addClass('log mdl-card container mdl-color--white mdl-shadow--2dp').append($('<pre></pre>').append(obj.message)));
            }
          } catch (e){
            console.error(data);
          }
          $('form.list').append($('<a></a>').addClass('mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white').attr('href', '../').text('Zurück').prepend($('<i></i>').addClass('material-icons').text('keyboard_arrow_left')));
        }).fail(function(e){

        });
      });
    </script>
		<script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
<?php else : header('Location: ../'); exit; endif; ?>
