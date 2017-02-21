<?php

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

	// Datenbank-Daten
	define('HOST', '');
	define('USER', '');
	define('PASSWORD', '');
	define('DATABASE', '');

	// SMTP-Server-Daten
	define('INVITE_HOST', '');
	define('INVITE_NAME', '');
	define('INVITE_MAIL', '');
  	define('INVITE_UID', '');
	define('INVITE_PASSWORD', '');
	define('INVITE_PORT', );

	// load mysqli connection
	require_once('db_connect.php');

	// Load Composer dependencies
	require(ABS_PATH . '/vendor/autoload.php');
?>
