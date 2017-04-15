<?php
  if(CONSTANTS_LOADED && login_check($mysqli)):
 ?>
<div class="mdl-grid">
  <div class="mdl-cell personal-data mdl-color--white mdl-shadow--2dp mdl-cell--12-col mdl-cell--5-col-desktop">
    <p class="mdl-typography--headline">Persönliche Daten</p>
    <p class="mdl-typography--title">Freunde</p>
    <?php	if(count(filterUsers($_SESSION['user']['uid'], $user['freunde'], $mysqli)) > 0) : ?>
      <ul class="mdl-list list--border-bottom list--flex-spacer">
        <?php
        $key = 0;
        foreach(filterUsers($_SESSION['user']['uid'], $user['freunde'], $mysqli) as $freund){
          $friend = getUser($freund, $mysqli);	?>
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
      </ul>
    <?php else : ?>
      <ul class="mdl-list list--no-entries list--border-bottom list--flex-spacer">
        <p class="mdl-typography--body">
          <?php echo $user['vorname']?> hat noch keine Freunde hinzugefügt
        </p>
      </ul>
    <?php endif; ?>
    <p class="mdl-typography--body-1">
      <?php echo $user['name']?> ist seit dem <?php echo date('d.m.Y', strtotime($user['registered_since'])) ?> registriert.
    </p>
    <p class="mdl-typography--body-1">
      Seitdem hat <?php echo $user['vorname'] . " " . $user['freundesanzahl'] ?> Personen als Freunde angenommen <?php if($befreundet && $_SESSION['user'] != $user) : ?>, darunter auch du<?php endif; ?>.
    </p>
  </div>
</div>
 <?php
  else: echo "";
  endif;
?>
