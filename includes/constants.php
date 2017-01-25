<?php

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
	define('HOST', 'localhost');
	define('USER', 'bvasozial-2016');
	define('PASSWORD', '2MvfymPYJN7YBWqm');
	define('DATABASE', 'bvasozial-2016');

	// SMTP-Server-Daten
	define('INVITE_HOST', 'host90.checkdomain.de');
	define('INVITE_NAME', 'Abi-Zeitung 2016');
	define('INVITE_MAIL', 'bvasozial.2016@occloxium.com');
	define('INVITE_PASSWORD', 'kralle2209');
	define('INVITE_PORT', 587);

	// load mysqli connection
	require_once('db_connect.php');

	// Load Composer dependencies
	require(ABS_PATH.'/vendor/autoload.php');
?>
