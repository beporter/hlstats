#
# HLStats Database Upgrade file
# -----------------------------
#
# REPLACE #DB_PREFIX# WITH YOUR CURRENT HLSTATS PREFIX eg. hlstats
#
# To upgrade an existing HLStats 1.63 database to version 1.64, type:
#
#   mysql hlstats_db_name < upgrade_from_1.63.sql
#
#

DROP TABLE IF EXISTS `#DB_PREFIX#_Events_PlayerAttackedPlayer`;
CREATE TABLE `#DB_PREFIX#_Events_PlayerAttackedPlayer` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`eventTime` datetime DEFAULT NULL,
	`serverId` int(10) DEFAULT NULL,
	`map` varchar(64) DEFAULT NULL,
	`playerId` int(10) DEFAULT NULL,
	`weapon` varchar(64) DEFAULT NULL,
	`victimId` int(10) DEFAULT NULL,
	`damage` int(10) DEFAULT NULL,
	`armor` int(10) DEFAULT NULL,
	`health` int(10) DEFAULT NULL,
	`hitgroup` varchar(32) DEFAULT NULL,
	`damage_armor` int(10) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
