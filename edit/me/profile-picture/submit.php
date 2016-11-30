<?php
  include_once '../../../includes/db_connect.php';
  include_once '../../../includes/functions.php';

  secure_session_start();

  if(login_check($mysqli) == true){
    $user = getUser($_SESSION['username'], $mysqli);
    // move temporary file to directory where it has to be cropped before setting it.

    if(move_uploaded_file($_FILES['img']['tmp_name'], "/../../img/" . basename($_FILES['img']['name']))){
      echo success([]);
    } else {
      echo error('internalError', 500, 'Could not move file to edit directory');
    }
  }
 ?>
