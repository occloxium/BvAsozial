<?php
  if(CONSTANTS_LOADED && login_check($mysqli)):
 ?>
<div class="mdl-grid">
  <div class="mdl-cell personal-data mdl-color--white mdl-shadow--2dp mdl-cell--12-col mdl-cell--12-col-desktop">
    <p class="mdl-typography--headline">Meine persönlichen Angaben</p>
    <div class="mdl-grid personal-data__inner">
      <div class="mdl-cell mdl-cell--8-col mdl-cell--8-col-desktop">
        <p class="mdl-typography--title">Dein Spruch</p>
        <p class="mdl-typography--body-1">
          Dieser Spruch landet in deinem Steckbrief. Er kann jederzeit geändert werden.
        </p>
        <div class="mdl-grid">
          <form class="mdl-cell mdl-cell--8-col mdl-cell--8-col-desktop flex-container flex-container--vertical" action="/includes/addQuote.php" method="post">
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
        <p class="mdl-typography--title"><a class="highlight" href="/freunde/">Meine Freunde</a></p>
        <?php	if(count($user['freunde']) > 0) : ?>
          <ul class="mdl-list list--border-bottom list--flex-spacer">
            <?php
            $key = 0;
            foreach($user['freunde'] as $freund){
              $friend = getUser($user['freunde'][$key], $mysqli);	?>
              <li class="mdl-list__item">
                <a class="mdl-list__item-primary-content" href="/users/index.php/<?= $friend['uid']?>">
                  <img src="/users/data/<?= $friend['uid']?>/avatar.jpg" class="mdl-list__item-avatar">
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
          <?php echo date('d.m.Y', strtotime($user['registered_since'])) ?> registriert.<br> Seitdem hast Du <?php echo $user['freundesanzahl'] ?> Personen als Freunde angenommen.
        </p>
      </div>
    </div>
  </div>
</div>
 <?php
  else: echo "";
  endif;
?>
