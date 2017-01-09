<?php
	// Sicherheitskonstante
	define('SECURE', false);

	// Development constants
	define('DEVELOPMENT', false);

	// Absolut-Pfade
	define('ABS_PATH', realpath($include_path . "/../"));
	define('INC_PATH', '/includes/');
	define('ADMIN_PATH', '/admin/');

	// load mysqli connection
  define('HOST', $db_host);
	define('USER', $db_user);
	define('PASSWORD', $db_password);
	define('DATABASE', $db_name);

  require_once('db_connect.php');
?>
