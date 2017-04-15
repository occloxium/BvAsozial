<?php
  if(CONSTANTS_LOADED && login_check($mysqli)) :
 ?>
  <header class="drawer-header">
    <img class="avatar" src="<?php echo '/users/data/' . $_SESSION['user']['uid'] . '/avatar.jpg'?>">
    <div>
    <div class="flex-container">
      <span><?php echo $_SESSION['user']['email']; ?></span>
      <div class="mdl-layout-spacer"></div>
    </div>
  </div>
</header>
<?php
  else : echo "";
  endif;
 ?>
