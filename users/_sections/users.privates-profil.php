<?php
  if(CONSTANTS_LOADED && login_check($mysqli)) :
 ?>
<div class="private-profile__wrapper">
  <h1 class="mdl-typography--display-1 private-profile__title"><?= $user['name'] ?> hat das Profil vor dir verborgen.</h1>
  <p class="mdl-typography--body-1 private-profile__subtitle">
    Das macht uns traurig <i class="material-icons">sentiment_very_dissatisfied</i>
  </p>
</div>

<?php
  else : echo "";
  endif;
 ?>
