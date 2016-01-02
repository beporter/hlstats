CREATE TABLE `hlstats_ws_playerDataTable` (
  `uniqueID` varchar(128) NOT NULL DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `countryCode` varchar(5) DEFAULT NULL,
  `skill` int(10) DEFAULT NULL,
  `oldSkill` int(10) DEFAULT NULL,
  `kills` int(10) DEFAULT NULL,
  `deaths` int(10) DEFAULT NULL,
  `lastConnect` datetime DEFAULT NULL,
  `lastUpdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `game` varchar(64) DEFAULT NULL,
  `day` date DEFAULT NULL,
  `siteName` varchar(64) DEFAULT NULL,
  UNIQUE KEY `uniqueID` (`uniqueID`,`game`,`day`),
  KEY `uniqueID_2` (`uniqueID`),
  KEY `game` (`game`),
  KEY `day` (`day`),
  KEY `sitename` (`sitename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `hlstats_ws_sites` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `siteHash` varchar(32) DEFAULT NULL,
  `siteURL` varchar(255) DEFAULT NULL,
  `requestURL` varchar(255) DEFAULT NULL,
  `game` varchar(64) DEFAULT NULL,
  `regDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;