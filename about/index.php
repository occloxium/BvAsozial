<?php
	require_once('constants.php');
	require_once(ABS_PATH.INC_PATH.'functions.php');
?>
<!DOCTYPE html>
<html lang="de">
	<head>
    <?php _getHead('about'); ?>
	</head>
	<body>
	  <div class="layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
	    <div class="drawer mdl-layout__drawer mdl-color--white mdl-color-text--black">
	      <?php _getNav('über'); ?>
	    </div>
      <main class="mdl-layout__content mdl-color--white">
        <h1 class="mdl-typography--display-1">Über BvAsozial</h1>
      </main>
    </div>
    <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
	</body>
</html>
