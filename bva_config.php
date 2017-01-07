<?php
/**
 * Grundeinstellungen für BvAsozial
 *
 *  * MySQL-Zugangsdaten
 *  * ABSPATH-Konstante
 *  * Tabellenpräfix
 *
 * @package BvAsozial 1.5
 */

/**
 * Name der Datenbank
 */
define('DB_NAME', 'bvasozial');

/*
 * Datenbank-Benutzername
 */
define('DB_USER', '');
/**
 * Datenbank-Passwort
 */
define('DB_PASSWORD', '');
/**
 * MySQL-Serveradresse
 */
define('DB_HOST', '');
/**
 * Zeichensatz der Datenbank
 */
define('DB_CHARSET', 'utf8mb4');
/**
 * Collate-Typ soll gleich bleiben
 */
define('DB_COLLATE', '');


/**
 * Debugging-Modus für Entwickler
 *
 * Wenn "true", soll jeder Fehler oder jede Wahnung angezeigt werden, die auftritt, um Fehler in Skripten
 * möglichst frühzeitig zu finden und zu eliminieren
 */
define('BVA_DEBUG', true);


/**
 * Präfix für die Datenbank-Tabellen
 */
$table_prefix = 'bva_';

/**
 * ABSPATH-Konstante
 * Hilft bei der Berechnung von Pfaden vom Root-Verzeichnis aus
 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

require_once(ABSPATH . 'bva_settings.php');
 ?>
