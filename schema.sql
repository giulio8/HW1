DROP TABLE IF EXISTS `aeroporti`;
CREATE TABLE `aeroporti` (
  `iata_code` char(3) NOT NULL,
  `country` varchar(256) NOT NULL,
  `city` varchar(256) NOT NULL,
  PRIMARY KEY (`iata_code`)
)

DROP TABLE IF EXISTS `destinazioni`;
CREATE TABLE `destinazioni` (
  `titolo` varchar(255) NOT NULL,
  `utente` varchar(255) NOT NULL,
  `descrizione` varchar(1023) DEFAULT NULL,
  `immagine` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`titolo`,`utente`),
  KEY `utente` (`utente`),
  CONSTRAINT `destinazioni_ibfk_1` FOREIGN KEY (`utente`) REFERENCES `utenti` (`username`) ON DELETE CASCADE
)

DROP TABLE IF EXISTS `prenotazioni`;
CREATE TABLE `prenotazioni` (
  `id` varchar(255) NOT NULL,
  `prezzo` float DEFAULT NULL,
  `valuta` varchar(15) DEFAULT NULL,
  `origine` char(3) DEFAULT NULL,
  `destinazione` char(3) DEFAULT NULL,
  `compagnia` varchar(255) DEFAULT NULL,
  `data_partenza` datetime DEFAULT NULL,
  `data_arrivo` datetime DEFAULT NULL,
  `titolo_destinazione` varchar(255) DEFAULT NULL,
  `utente` varchar(255) DEFAULT NULL,
  `bagaglio` int(11) DEFAULT NULL,
  `data_prenotazione` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `titolo_destinazione` (`titolo_destinazione`,`utente`),
  CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`titolo_destinazione`, `utente`) REFERENCES `destinazioni` (`titolo`, `utente`) ON DELETE CASCADE
)

DROP TABLE IF EXISTS `tratte`;
CREATE TABLE `tratte` (
  `id` varchar(255) NOT NULL,
  `origine` char(3) DEFAULT NULL,
  `destinazione` char(3) DEFAULT NULL,
  `volo` varchar(255) DEFAULT NULL,
  `data_partenza` datetime DEFAULT NULL,
  `data_arrivo` datetime DEFAULT NULL,
  `progressivo` int(11) DEFAULT NULL,
  `direzione` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `volo` (`volo`),
  CONSTRAINT `tratte_ibfk_1` FOREIGN KEY (`volo`) REFERENCES `prenotazioni` (`id`) ON DELETE CASCADE
)

DROP TABLE IF EXISTS `utenti`;
CREATE TABLE `utenti` (
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(72) NOT NULL,
  `birthdate` date NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  PRIMARY KEY (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
)
