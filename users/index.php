<?php
  require_once('constants.php');
  require_once(ABS_PATH.INC_PATH.'functions.php');
  secure_session_start();
  if(login_check($mysqli) == true) :
?>
  <html>
  <head>
    <?php _getHead('profil'); ?>
  </head>

  <body data-signedInUser="<?= $_SESSION['user']['uid']?>">
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <?php require_once(ABS_PATH . '/_sections/drawer.php'); ?>
        <?php _getNav('profil'); ?>
      </div>
      <?php
        if(isset($_SERVER['PATH_INFO']) && str_replace('/','', $_SERVER['PATH_INFO']) != $_SESSION['user']['uid'] ) :
          $user = getUser(str_replace('/','',$_SERVER['PATH_INFO']), $mysqli);
          if(!isset($user['uid'], $user['name'])) :
            header('Location: ../');
          endif;
        else :
          $_SERVER['PATH_INFO'] = $_SESSION['user']['uid'];
          $user = $_SESSION['user'];
        endif;
        ?>
      <main class="mdl-layout__content mdl-color--grey-100" data-name="<?php echo $user['name'] ?>" data-username="<?php echo $user['uid'] ?>">
        <?php
          $json_str = file_get_contents(ABS_PATH . "/users/data/{$user['uid']}/{$user['uid']}.json");
          $json = json_decode($json_str, true);
          switch($user['privacySettings']){
            case 0 :
              if($_SESSION['user'] == $user){
                require_once('_sections/users.mein-profil-banner.php');
                require_once('_sections/users.mein-profil.php');
                require_once('_sections/users.meine-fragen.php');
              } else {
                require_once('_sections/users.privates-profil.php');
              }
              break;
            case 1 :
              if(is_listed($user['uid'], $_SESSION['user']['uid']) && isFriendsWith($_SESSION['user']['uid'], $user['uid'], $mysqli) && $_SESSION['user'] != $user){
                require_once('_sections/users.profil-banner.php');
                require_once('_sections/users.profil.php');
              } else {
                if($_SESSION['user'] == $user){
                  require_once('_sections/users.mein-profil-banner.php');
                  require_once('_sections/users.mein-profil.php');
                  require_once('_sections/users.meine-fragen.php');
                } else {
                  require_once('_sections/users.privates-profil.php');
                }
              }
              break;
            case 2 :
              if(isFriendsWith($_SESSION['user']['uid'], $user['uid'], $mysqli) && $_SESSION['user'] != $user){
                require_once('_sections/users.profil-banner.php');
                require_once('_sections/users.profil.php');
              } else {
                if($_SESSION['user'] == $user){
                  require_once('_sections/users.mein-profil-banner.php');
                  require_once('_sections/users.mein-profil.php');
                  require_once('_sections/users.meine-fragen.php');
                } else {
                  require_once('_sections/users.privates-profil.php');
                }
              }
              break;
            case 3 :
              if($_SESSION['user'] == $user){
                require_once('_sections/users.mein-profil-banner.php');
                require_once('_sections/users.mein-profil.php');
                require_once('_sections/users.meine-fragen.php');
              } else {
                require_once('_sections/users.profil-banner.php');
                require_once('_sections/users.profil.php');
              }
          }
          ?>
      </main>
      </div>
      <div class="mdl-js-snackbar mdl-snackbar" data-success="">
          <div class="mdl-snackbar__text"></div>
          <button class="mdl-snackbar__action" type="button"></button>
      </div>
      <script src="/js/autogrow.js"></script>
      <script src="/js/users.js"></script>
      <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php else :
  header("Location: /index.php");
endif; ?>
