<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');
  secure_session_start();
  if (isset($_POST['frage'])) {
      if (login_check($mysqli)) {
          $fragenkatalog = json_decode(file_get_contents(ABS_PATH . '/registrieren/fragenkatalog.json'), true);
          if (isset($_POST['freundesfragen'], $_POST['eigeneFragen'])) {
              $fragenkatalog['freundesfragen'][] = $_POST['frage'];
              $fragenkatalog['eigeneFragen'][] = $_POST['frage'];
          } elseif (isset($_POST['freundesfragen'])) {
              $fragenkatalog['freundesfragen'][] = $_POST['frage'];
          } elseif (isset($_POST['eigeneFragen'])) {
              $fragenkatalog['eigeneFragen'][] = $_POST['frage'];
          } else {
              echo error('clientError', 500, 'Bad Request');
              exit;
          }
          if (file_put_contents(ABS_PATH . '/registrieren/fragenkatalog.json', json_encode($fragenkatalog, JSON_PRETTY_PRINT)) > 0) {
              header('Location: ../');
              exit;
          } else {
              echo error('internalError', 500, 'Could not save to file');
          }
      } else {
          echo error('clientError', 403, 'Forbidden');
      }
  } else {
      echo error('clientError', 400, 'Bad Request');
  }
?>
