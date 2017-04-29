<?php
  if(CONSTANTS_LOADED && login_check($mysqli)):
 ?>
<div class="mdl-grid margin--top">
  <div class="profile mdl-cell mdl-color--white mdl-shadow--2dp mdl-cell--12-col mdl-cell--12-col-desktop mdl-grid">
    <div class="image-column mdl-cell mdl-cell--6-col mdl-cell--6-col-desktop">
      <div class="avatar">
        <img class="avatar__image" src="<?php echo "/users/data/{$user['uid']}/avatar.jpg"?>">
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
            <form class="form--rufnamen">
              <ul>
                <?php
                 echo rufnamenliste($user['uid'], $_SESSION['user']['uid'], $json['rufnamen'], $mysqli);
                ?>
                <button class="rufnamen__add mdl-color-text--white mdl-color--primary mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" type="button">
                  <i class="material-icons">add</i>
                  <span class="visuallyhidden">Rufnamen hinzufügen</span>
                </button>
              </ul>
              <div class="mdl-tooltip" for="btnaddname">
                Rufnamen hinzufügen
              </div>
              <button type="button" class="mdl-button save-all mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white">
                speichern
              </button>
            </form>
          </div>
          <?php
            $befreundet = isFriendsWith($_SESSION['user']['uid'], $user['uid'], $mysqli);
            if(requestSent($_SESSION['user']['uid'], $user['uid'], $mysqli) || ($befreundet && !($_SESSION['user']['uid'] == $user['uid']))) :?>
            <div class="data-section data-section--border data-section--no-flex">
              <button class="mdl-button mdl-js-button" id="btnadddisabled" disabled>
                Als Freund hinzufügen
              </button>
              <div class="mdl-tooltip" for="btnadddisabled">
                Du bist mit der Person schon befreundet
              </div>
            </div>
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
<?php
 else: echo "";
 endif;
?>
