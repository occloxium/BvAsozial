<?php
	// Domain
	define('DOMAIN', 'http://bvasozial.localhost/bva-install/');

	// Sicherheitskonstante
	define('SECURE', false);

	// Development constants
	define('DEVELOPMENT', false);

	// Absolut-Pfade
	define('ABS_PATH', realpath('/Users/mark/Projects/BvAsozial/includes' . "/../"));
	define('INC_PATH', '/includes/');
	define('ADMIN_PATH', '/admin/');

	// Datenbank-Daten
  define('HOST', 'localhost');
	define('USER', 'root');
	define('PASSWORD', '');
	define('DATABASE', 'bvasozial');

	// SMTP-Daten
	define('INVITE_HOST', '	smtp.mailtrap.io');
	define('INVITE_NAME', 'd25821052d92ad');
	define('INVITE_MAIL', 'd25821052d92ad');
	define('INVITE_PASSWORD', '8214e1e7c30541');
	define('INVITE_PORT', '2525');

  // load mysqli connection
  require_once('db_connect.php');

  // Load Composer dependencies
  require(ABS_PATH.'/vendor/autoload.php');
?>
