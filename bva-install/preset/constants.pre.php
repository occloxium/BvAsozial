<?php
	// Domain
	define('DOMAIN', $domain);

	// Sicherheitskonstante
	define('SECURE', false);

	// Development constants
	define('DEVELOPMENT', false);

	// Absolut-Pfade
	define('ABS_PATH', realpath($include_path . "/../"));
	define('INC_PATH', '/includes/');
	define('ADMIN_PATH', '/admin/');

	// Datenbank-Daten
  define('HOST', $db_host);
	define('USER', $db_user);
	define('PASSWORD', $db_password);
	define('DATABASE', $db_name);

	// SMTP-Daten
	define('INVITE_HOST', $smtp_host);
	define('INVITE_NAME', $smtp_name);
	define('INVITE_MAIL', $smtp_mail);
	define('INVITE_PASSWORD', $smtp_password);
	define('INVITE_PORT', $smtp_port);

  // load mysqli connection
  require_once('db_connect.php');

  // Load Composer dependencies
  require(ABS_PATH.'/vendor/autoload.php');
?>
