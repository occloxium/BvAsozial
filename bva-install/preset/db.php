<?php $query = <<<QUERY
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `boundTo` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `anfragen` (
  `von` varchar(100) NOT NULL,
  `an` varchar(100) NOT NULL,
  `sent` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `ausstehende_einladungen` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL COMMENT 'sha1',
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `directory` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `freunde` (
  `uid` varchar(100) NOT NULL,
  `friend` varchar(100) NOT NULL,
  `friendsSince` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL COMMENT 'sha1',
  `email` varchar(100) NOT NULL COMMENT 'Für "Passwort vergessen"'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `moderatoren` (
  `id` int(12) NOT NULL,
  `boundTo` varchar(128) NOT NULL,
  `strikes` int(32) NOT NULL,
  `suspended` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
DELIMITER $$
CREATE TRIGGER `ban_on_3_strikes` BEFORE UPDATE ON `moderatoren` FOR EACH ROW BEGIN
	IF NEW.strikes >= 2 THEN
    	SET NEW.suspended = 1;
    END IF;
END
$$
DELIMITER ;

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `received_requests` int(11) NOT NULL,
  `sent_requests` int(11) NOT NULL,
  `directory` varchar(255) NOT NULL,
  `registered_since` date NOT NULL,
  `finalisiert` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `statistiken` (
  `date` datetime NOT NULL COMMENT 'Datum des Tages der Erhebung',
  `registrierte_benutzer` int(32) NOT NULL,
  `ausstehende_einladungen` int(32) NOT NULL,
  `versendete_freundschaftsanfragen` int(32) NOT NULL,
  `finalisierte_profile` int(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabelle zur täglichen Erhebung der Benutzerstatistik';
DELIMITER $$
CREATE TRIGGER `default_date` BEFORE INSERT ON `statistiken` FOR EACH ROW SET new.date = CURDATE()
$$
DELIMITER ;


ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boundTo` (`boundTo`);

ALTER TABLE `anfragen`
  ADD UNIQUE KEY `von` (`von`,`an`);

ALTER TABLE `ausstehende_einladungen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

ALTER TABLE `freunde`
  ADD UNIQUE KEY `uid` (`uid`,`friend`);

ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

ALTER TABLE `moderatoren`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boundTo` (`boundTo`);

ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

ALTER TABLE `statistiken`
  ADD PRIMARY KEY (`date`),
  ADD UNIQUE KEY `date` (`date`);


ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ausstehende_einladungen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `moderatoren`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `anfragen`
  ADD CONSTRAINT `anfragen_ibfk_1` FOREIGN KEY (`von`) REFERENCES `person` (`uid`) ON DELETE CASCADE;

ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `person` (`uid`) ON DELETE CASCADE;

DELIMITER $$
CREATE DEFINER=`bvasozialadmin`@`localhost` EVENT `update_statistics` ON SCHEDULE EVERY 6 HOUR STARTS '2016-10-20 05:00:00' ON COMPLETION PRESERVE ENABLE DO BEGIN
INSERT
INTO
  bvasozial.statistiken(DATE)
VALUES (CURDATE());
UPDATE
  bvasozial.statistiken
SET
  statistiken.registrierte_benutzer =(
  SELECT COUNT(*)
  FROM
    bvasozial.person
)
WHERE
  statistiken.date = CURDATE();
UPDATE
  bvasozial.statistiken
SET
  statistiken.ausstehende_einladungen =(
  SELECT COUNT(*)
  FROM
    bvasozial.ausstehende_einladungen
)
WHERE
  statistiken.date = CURDATE();
UPDATE
  bvasozial.statistiken
SET
  statistiken.versendete_freundschaftsanfragen =(
    (
    SELECT COUNT(*)
    FROM
      bvasozial.freunde
  ) + (
SELECT COUNT(*)
FROM
  anfragen
)
  )
WHERE
  statistiken.date = CURDATE();
UPDATE
  bvasozial.statistiken
SET
  statistiken.finalisierte_profile =(
  SELECT COUNT(bvasozial.person.finalisiert)
  FROM
    bvasozial.person
  WHERE
    person.finalisiert = 1
)
WHERE
  statistiken.date = CURDATE();
END$$

DELIMITER ;

QUERY;
?>
