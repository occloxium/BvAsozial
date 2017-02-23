<?php
  /**
   * Echos a set of questions of a specific user
   * Alexander Bartolomey | 2017
   * @package BvAsozial 1.2
   */

  require('constants.php');
  require(ABS_PATH.INC_PATH.'functions.php');
  require(ABS_PATH.INC_PATH.'frage.php');

  secure_session_start();
  if(isset($_POST['uid'])){
    if(login_check($mysqli) && (is_privileged($_SESSION['user']['uid'], $_POST['uid'], $mysqli) || isFriendsWith($_POST['uid'], $_SESSION['user']['uid'], $mysqli))){
      $jsonstr = file_get_contents(ABS_PATH.'/users/'.$_POST['uid'].'/'.$_POST['uid'].'.json');
      $json = json_decode($jsonstr, true);
      $i = 1;
      $fragen = "";
      $requestor = $_SESSION['user']['uid'];
      foreach($json['freundesfragen'] as $frage){
        $fragen .= frage($frage, getUser($requestor, $mysqli), getUser($_POST['uid'], $mysqli), $i, 2);
        $i++;
      }
      $saveAll = '<button class="mdl-button mdl-js-button mdl-color--primary mdl-color-text--white mdl-js-ripple-effect save-all">Alles abspeichern</button>';
    $html = "<ul><p class=\"container--freundesfragen__auswahl\">Fragen von <a class=\"highlight\"href=\"/users/index.php/e_mustermann\"><b>". getUser($_POST['uid'], $mysqli)['vorname'] ."</b></a></p></h6>$fragen</ul>$saveAll";
      success(["html" => $html]);
    } else {
      error('clientError', 403, 'Forbidden');
    }
  } else {
    error('clientError', 400, 'Bad Request');
  }

 ?>
