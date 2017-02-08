<?php
  require_once('constants.php');
  require_once(ABS_PATH . INC_PATH . 'functions.php');

  secure_session_start();
  if(login_check($mysqli) && $_SESSION['user']['is_admin']){
    
  }
 ?>
