<?php
	// Sicherheitskonstante
	define('SECURE', false);

	// Development constants
	define('DEVELOPMENT', true);

	// Absolut-Pfade
	define('ABS_PATH', realpath(__DIR__ . '/../'));
	define('INC_PATH', '/includes/');
	define('ADMIN_PATH', '/admin/');

	define('HOST', 'localhost');
	define('USER', 'bvasozial-2016');
	define('PASSWORD', '2MvfymPYJN7YBWqm');
	define('DATABASE', 'bvasozial-2016');

	// load mysqli connection
	require_once('db_connect.php');
?>
