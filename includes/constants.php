<?php

  define('VERSION', '1.2.38');
	// Domain
	define('DOMAIN','localhost');

	// Sicherheitskonstante
	define('SECURE', false);

	// Development constants
	define('DEVELOPMENT', true);

	// Absolut-Pfade
	define('ABS_PATH', realpath(__DIR__ . '/../'));
	define('INC_PATH', '/includes/');
	define('ADMIN_PATH', '/admin/');

	// Datenbank-Daten


	// SMTP-Server-Daten
	define('INVITE_HOST', 'smtp.gmail.com');
	define('INVITE_NAME', 'Abi-Zeitung 2016');

	define('INVITE_PORT', 587);

  // load private DATABASE
  require_once('../../constants.inc.php');
	// load mysqli connection
	require_once('db_connect.php');

	// Load Composer dependencies
	require(ABS_PATH . '/vendor/autoload.php');
?>
