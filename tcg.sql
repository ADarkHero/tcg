-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 25. Jan 2019 um 10:33
-- Server-Version: 10.1.28-MariaDB
-- PHP-Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `tcg`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cards`
--

CREATE TABLE `cards` (
  `CardID` int(11) NOT NULL,
  `MasterID` int(11) NOT NULL,
  `CardMasterSubID` int(11) NOT NULL,
  `RarityID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `cards`
--

INSERT INTO `cards` (`CardID`, `MasterID`, `CardMasterSubID`, `RarityID`) VALUES
(6, 5, 1, 1),
(7, 5, 2, 1),
(8, 5, 3, 1),
(9, 5, 4, 1),
(10, 5, 5, 1),
(11, 5, 6, 1),
(12, 5, 7, 2),
(13, 5, 8, 2),
(14, 5, 9, 2),
(15, 5, 10, 3),
(16, 5, 11, 3),
(17, 5, 12, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`) VALUES
(1, 'Food'),
(2, 'Animu');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `masters`
--

CREATE TABLE `masters` (
  `MasterID` int(11) NOT NULL,
  `MasterName` varchar(255) NOT NULL,
  `MasterShortName` varchar(8) NOT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `masters`
--

INSERT INTO `masters` (`MasterID`, `MasterName`, `MasterShortName`, `CategoryID`) VALUES
(5, 'Basic Foodstuff', 'bsfood', 1);

--
-- Trigger `masters`
--
DELIMITER $$
CREATE TRIGGER `onMasterInsertCreateCards` AFTER INSERT ON `masters` FOR EACH ROW INSERT INTO cards VALUES(null,NEW.MasterID,1,1), 
(null,NEW.MasterID,2,1), 
(null,NEW.MasterID,3,1), 
(null,NEW.MasterID,4,1),
(null,NEW.MasterID,5,1),
(null,NEW.MasterID,6,1),
(null,NEW.MasterID,7,2),
(null,NEW.MasterID,8,2),
(null,NEW.MasterID,9,2),
(null,NEW.MasterID,10,3),
(null,NEW.MasterID,11,3),
(null,NEW.MasterID,12,4)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rarities`
--

CREATE TABLE `rarities` (
  `RarityID` int(11) NOT NULL,
  `RarityName` varchar(255) NOT NULL,
  `RarityValue` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `rarities`
--

INSERT INTO `rarities` (`RarityID`, `RarityName`, `RarityValue`) VALUES
(1, 'Common', 55),
(2, 'Rare', 30),
(3, 'Epic', 10),
(4, 'Legendary', 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `securitytokens`
--

CREATE TABLE `securitytokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) NOT NULL,
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `securitytoken` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `securitytokens`
--

INSERT INTO `securitytokens` (`id`, `user_id`, `identifier`, `securitytoken`, `created_at`) VALUES
(1, 1, '05797a5cb02e80387a9bbc91032a44e2', '21f255bcca1d8db186a5321abbf34329d1aa863d', '2019-01-23 11:34:45'),
(2, 1, '282a95e2cabd8218c7c4527de2ac54d2', '623c050feb38b50ee397003fcdb2cbc1ae990467', '2019-01-23 13:03:43');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `storages`
--

CREATE TABLE `storages` (
  `StorageID` int(11) NOT NULL,
  `StorageName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `storages`
--

INSERT INTO `storages` (`StorageID`, `StorageName`) VALUES
(1, 'Will'),
(2, 'Might'),
(3, 'Reserved'),
(4, 'Keep'),
(5, 'Collect'),
(6, 'New');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `trades`
--

CREATE TABLE `trades` (
  `TradeID` int(11) NOT NULL,
  `TradeUserSelf` int(11) NOT NULL,
  `TradeUserOther` int(11) NOT NULL,
  `TradeCardSelf` int(11) NOT NULL,
  `TradeCardOther` int(11) NOT NULL,
  `TradeOpen` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `trades`
--

INSERT INTO `trades` (`TradeID`, `TradeUserSelf`, `TradeUserOther`, `TradeCardSelf`, `TradeCardOther`, `TradeOpen`) VALUES
(1, 1, 2, 6, 7, 0),
(2, 2, 1, 8, 9, 0),
(5, 1, 2, 13, 15, 0),
(6, 1, 2, 14, 12, 0),
(7, 1, 2, 6, 15, 0),
(8, 1, 2, 6, 9, 0),
(9, 1, 2, 6, 7, 0),
(10, 1, 2, 6, 7, 0),
(11, 1, 2, 6, 7, 0),
(12, 1, 2, 6, 9, 0),
(13, 1, 2, 6, 8, 0),
(14, 1, 2, 6, 9, 0),
(15, 1, 2, 6, 13, 0),
(16, 1, 2, 6, 13, 0),
(17, 1, 2, 6, 8, 0),
(18, 1, 2, 6, 13, 0),
(19, 1, 2, 6, 12, 0),
(20, 1, 2, 6, 12, 0),
(21, 1, 2, 6, 15, 0),
(22, 2, 1, 7, 9, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `passwordcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwordcode_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `created_at`, `updated_at`, `passwordcode`, `passwordcode_time`) VALUES
(1, 'maik.riedlsperger@gmail.com', '$2y$10$HJ2V5fQyPhgmXf5foGunIesvi2TKKA7zxv6FOf8/lcrK/O2Fk3j9K', 'ADarkHero', '2019-01-23 11:34:43', NULL, NULL, NULL),
(2, 'maik_riedlsperger@yahoo.de', '$2y$10$HKca3BBWDUGrDgC6sT7PWuD1AhlE4RjMQa/iIh4/k9uFBLXyD5Ytu', 'test', '2019-01-24 08:51:59', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `usersxcards`
--

CREATE TABLE `usersxcards` (
  `UserID` int(11) NOT NULL,
  `CardID` int(11) NOT NULL,
  `StorageID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `usersxcards`
--

INSERT INTO `usersxcards` (`UserID`, `CardID`, `StorageID`) VALUES
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(1, 1, 1),
(2, 1, 1),
(2, 1, 1),
(1, 1, 1),
(1, 15, 1),
(2, 9, 1),
(1, 7, 5),
(1, 17, 1),
(1, 16, 2),
(1, 16, 2),
(1, 10, 1),
(2, 9, 1),
(1, 14, 2),
(2, 6, 6),
(2, 6, 6),
(2, 9, 1),
(1, 13, 1),
(1, 16, 2),
(1, 13, 5),
(1, 14, 2),
(1, 17, 5),
(1, 8, 5),
(1, 6, 1),
(1, 8, 1),
(2, 9, 1),
(1, 17, 1),
(1, 11, 5),
(1, 8, 1),
(1, 13, 1),
(1, 10, 5),
(1, 7, 1),
(1, 17, 1),
(1, 15, 5),
(1, 17, 1),
(1, 10, 1),
(1, 13, 1),
(1, 16, 2),
(1, 16, 2),
(1, 8, 1),
(2, 9, 1),
(1, 7, 1),
(1, 16, 2),
(1, 14, 2),
(1, 12, 5),
(1, 10, 1),
(1, 14, 2),
(1, 13, 1),
(1, 15, 1),
(1, 9, 1),
(1, 7, 1),
(1, 16, 2),
(1, 17, 1),
(1, 14, 2),
(1, 16, 2),
(1, 8, 1),
(1, 11, 1),
(1, 7, 1),
(1, 15, 1),
(1, 9, 1),
(1, 12, 1),
(1, 15, 1),
(1, 7, 1),
(1, 11, 1),
(1, 9, 1),
(1, 14, 2),
(1, 8, 1),
(1, 17, 1),
(1, 7, 1),
(1, 12, 1),
(1, 6, 1),
(1, 15, 1),
(1, 8, 1),
(1, 13, 1),
(1, 7, 1),
(1, 11, 1),
(1, 12, 6),
(1, 13, 6),
(1, 6, 1),
(1, 9, 1),
(1, 10, 1),
(1, 15, 1),
(2, 14, 1),
(2, 13, 1),
(1, 8, 1),
(2, 9, 1),
(2, 7, 1),
(1, 12, 6),
(1, 16, 2),
(1, 6, 1),
(1, 10, 1),
(1, 11, 1),
(1, 14, 2),
(1, 8, 1),
(1, 7, 1),
(1, 17, 6),
(1, 12, 6),
(1, 14, 2),
(1, 9, 1),
(1, 10, 1),
(1, 8, 1),
(1, 13, 6),
(1, 17, 6),
(1, 11, 1),
(1, 16, 2),
(1, 13, 6),
(1, 6, 1),
(1, 14, 2),
(1, 15, 1),
(1, 9, 1),
(1, 14, 2),
(1, 12, 6),
(1, 16, 2),
(1, 13, 6),
(1, 11, 6),
(1, 7, 1),
(1, 8, 1),
(1, 16, 2),
(1, 13, 6),
(1, 6, 1),
(1, 9, 1),
(1, 17, 6),
(1, 14, 2),
(1, 14, 2),
(1, 9, 1),
(1, 11, 6),
(1, 6, 1),
(1, 12, 6),
(1, 13, 6),
(1, 8, 1),
(1, 12, 6),
(1, 14, 2),
(1, 15, 1),
(1, 16, 2),
(1, 10, 1),
(1, 13, 6),
(1, 8, 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`CardID`);

--
-- Indizes für die Tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indizes für die Tabelle `masters`
--
ALTER TABLE `masters`
  ADD PRIMARY KEY (`MasterID`);

--
-- Indizes für die Tabelle `rarities`
--
ALTER TABLE `rarities`
  ADD PRIMARY KEY (`RarityID`);

--
-- Indizes für die Tabelle `securitytokens`
--
ALTER TABLE `securitytokens`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `storages`
--
ALTER TABLE `storages`
  ADD PRIMARY KEY (`StorageID`);

--
-- Indizes für die Tabelle `trades`
--
ALTER TABLE `trades`
  ADD PRIMARY KEY (`TradeID`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `cards`
--
ALTER TABLE `cards`
  MODIFY `CardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `masters`
--
ALTER TABLE `masters`
  MODIFY `MasterID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `rarities`
--
ALTER TABLE `rarities`
  MODIFY `RarityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `securitytokens`
--
ALTER TABLE `securitytokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `storages`
--
ALTER TABLE `storages`
  MODIFY `StorageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `trades`
--
ALTER TABLE `trades`
  MODIFY `TradeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
