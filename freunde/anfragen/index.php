<?php
    require_once('constants.php');
		require_once(ABS_PATH.INC_PATH.'functions.php');
    secure_session_start();
    if (login_check($mysqli) == true) :
      $user = $_SESSION['user'];
?>
<!doctype html>
<html>
  <head>
    <?php _getHead('anfragen.freunde'); ?>
  </head>
  <body>
    <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
      <div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <?php require_once(ABS_PATH . '/_sections/drawer.php'); ?>
        <?php _getNav('anfragen'); ?>
      </div>
      <main class="mdl-layout__content mdl-color--grey-100" data-username="<?php echo $user['uid'] ?>" data-directory="<?php echo $user['uid'] ?>">
        <div class="mdl-card container container--margin-top mdl-color--white mdl-shadow--2dp" id="meine-anfragen">
          <p class="mdl-typography--headline element--margin-bottom element--border-bottom">Freundschaftsanfragen</p>
          <p class="mdl-typography--title">Erhaltene Anfragen</p>
          <ul class="mdl-list" id="received">
            <script>
              $.ajax({
                method: 'get',
                url: './_ajax/received-requests.php?uid=<?php echo $user['uid']?>',
                success: function(data) {
                  $('.mdl-list#received').append(data);
                },
                cache: false
              });
            </script>
          </ul>
        </div>
        <div class="mdl-card container mdl-color--white mdl-shadow--2dp" id="gesendete-anfragen">
          <p class="mdl-typography--title">Gesendete Anfragen</p>
          <ul class="mdl-list" id="sent">
            <script>
              $.ajax({
                method: 'get',
                url: './_ajax/sent-requests.php?uid=<?php echo $user['uid']?>',
                success: function(data) {
                  $('.mdl-list#sent').append(data);
                },
                cache: false
              });
            </script>
          </ul>
        </div>
      </main>
    </div>
    <script src="/js/anfragen.freunde.js"></script>
    <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
<?php else : header('Location: ../../'); exit; endif; ?>
