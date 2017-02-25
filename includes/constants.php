<?php

  define('VERSION', '1.2.38');
	/* DEFINE YOUR SETTINGS HERE */

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

	// SMTP-Server-Daten
	define('INVITE_PORT', 587);

  // load private DATABASE
  require_once('constants.inc.php');
	// load mysqli connection
	require_once('db_connect.php');

	// Load Composer dependencies
	require(ABS_PATH . '/vendor/autoload.php');
?>
