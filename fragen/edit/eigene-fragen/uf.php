<?php
  require_once('constants.php');
  require_once(ABS_PATH . INC_PATH . 'functions.php');

  secure_session_start();

  if(login_check($mysqli)){
    $user = $_SESSION['user'];
    if(isset($_POST)){
      $fragenkatalog = json_decode(file_get_contents(ABS_PATH . '/registrieren/fragenkatalog.json'), true);
      $userfile = json_decode(file_get_contents(ABS_PATH."/users/{$user['uid']}/{$user['uid']}.json"), true);
      $new_userfile = [];
      foreach($_POST as $key=>$frage){
        if(preg_match("/^e-frage-[1-9][0-9]*/", $key)){
          $found = false;
          $pos = 0;
          $num = intVal(substr($key, 8));
          foreach($userfile['eigeneFragen'] as $key2=>$user_frage){
            if(stripos($user_frage['frage'], $fragenkatalog['eigeneFragen'][$num - 1]) !== false){
              $found = true;
              $pos = $key2;
            }
          }
          if($found){
            $new_userfile[] = $userfile['eigeneFragen'][$pos];
          } else {
            $new_obj = [
              "frage" => $fragenkatalog['eigeneFragen'][$num - 1],
              "antwort" => ""
            ];
            $new_userfile[] = $new_obj;
          }
        }
      }
      $userfile['eigeneFragen'] = $new_userfile;
      if(file_put_contents(ABS_PATH."/users/{$user['uid']}/{$user['uid']}.json", json_encode($userfile, JSON_PRETTY_PRINT)) > 0)
        echo success(["message" => "Fragen angepasst"]);
      else
        echo error('internalError', 500, 'Unable to write to file');
    } else
      echo error('clientError', 400, 'Bad Request');
  } else
    echo error('clientError', 403, 'Forbidden');
