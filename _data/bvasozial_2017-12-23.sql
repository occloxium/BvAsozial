# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.20)
# Datenbank: bvasozial
# Erstellt am: 2017-12-23 11:45:03 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle admins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `boundTo` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;

INSERT INTO `admins` (`id`, `boundTo`)
VALUES
	(1,'admin');

/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle anfragen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `anfragen`;

CREATE TABLE `anfragen` (
  `von` varchar(100) NOT NULL,
  `an` varchar(100) NOT NULL,
  `sent` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Export von Tabelle ausstehende_einladungen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ausstehende_einladungen`;

CREATE TABLE `ausstehende_einladungen` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL COMMENT 'sha1',
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `directory` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Export von Tabelle entfernte_einladungen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `entfernte_einladungen`;

CREATE TABLE `entfernte_einladungen` (
  `id` int(11) NOT NULL,
  `uid` varchar(128) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Export von Tabelle entfernte_profile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `entfernte_profile`;

CREATE TABLE `entfernte_profile` (
  `id` int(11) NOT NULL,
  `uid` varchar(128) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recovery_file` varchar(128) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Export von Tabelle freunde
# ------------------------------------------------------------

DROP TABLE IF EXISTS `freunde`;

CREATE TABLE `freunde` (
  `uid` varchar(100) NOT NULL,
  `friend` varchar(100) NOT NULL,
  `friendsSince` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Export von Tabelle login
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login`;

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL COMMENT 'sha1',
  `email` varchar(100) NOT NULL COMMENT 'Für "Passwort vergessen"'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `login` WRITE;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;

INSERT INTO `login` (`id`, `uid`, `password`, `email`)
VALUES
	(1,'admin','9ca694a90285c034432c9550421b7b9dbd5c0f4b6673f05f6dbce58052ba20e4248041956ee8c9a2ec9f10290cdc0782','mrwff@gmx.de');

/*!40000 ALTER TABLE `login` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle moderatoren
# ------------------------------------------------------------

DROP TABLE IF EXISTS `moderatoren`;

CREATE TABLE `moderatoren` (
  `id` int(12) NOT NULL,
  `boundTo` varchar(128) NOT NULL,
  `strikes` int(32) NOT NULL,
  `suspended` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Export von Tabelle person
# ------------------------------------------------------------

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `received_requests` int(11) NOT NULL,
  `sent_requests` int(11) NOT NULL,
  `directory` varchar(255) NOT NULL,
  `registered_since` date NOT NULL,
  `finalisiert` tinyint(1) NOT NULL DEFAULT '0',
  `allowedEmails` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Export von Tabelle statistiken
# ------------------------------------------------------------

DROP TABLE IF EXISTS `statistiken`;

CREATE TABLE `statistiken` (
  `date` datetime NOT NULL COMMENT 'Datum des Tages der Erhebung',
  `registrierte_benutzer` int(32) NOT NULL,
  `ausstehende_einladungen` int(32) NOT NULL,
  `versendete_freundschaftsanfragen` int(32) NOT NULL,
  `finalisierte_profile` int(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabelle zur täglichen Erhebung der Benutzerstatistik';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
