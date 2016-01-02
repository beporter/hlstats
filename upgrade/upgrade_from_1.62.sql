#
# HLStats Database Upgrade file
# -----------------------------
#
# REPLACE #DB_PREFIX# WITH YOUR CURRENT HLSTATS PREFIX eg. hlstats
#
# To upgrade an existing HLStats 1.62 database to version 1.63, type:
#
#   mysql hlstats_db_name < upgrade_from_1.62.sql
#
#

ALTER TABLE `#DB_PREFIX#_Players` ADD `sykpe` varchar(128) NULL DEFAULT NULL  AFTER `steamprofile`;
ALTER TABLE `#DB_PREFIX#_Players` DROP `lastUpdate`;

INSERT INTO `#DB_PREFIX#_Server_Addons` (`rule`,`addon`,`url`) VALUES ('zp_version','Zombie Plague Mod %','http://forums.alliedmods.net/showthread.php?t=72505');
INSERT INTO `#DB_PREFIX#_Server_Addons` (`rule`,`addon`,`url`) VALUES ('zb_version','zBlock %','http://www.chti-team.fr/htdocs/documents/zblock.htm');
INSERT INTO `#DB_PREFIX#_Server_Addons` (`rule`,`addon`,`url`) VALUES ('smm_version','Mani Admin Plugin %','http://code.google.com/p/maniadminplugin/');
INSERT INTO `#DB_PREFIX#_Server_Addons` (`rule`,`addon`,`url`) VALUES ('spatialstats_version','Spatial statistics logging %','http://addons.eventscripts.com/addons/view/spatialstats');
INSERT INTO `#DB_PREFIX#_Server_Addons` (`rule`,`addon`,`url`) VALUES ('eventscripts_ver','EventScripts','http://www.eventscripts.com/');
INSERT INTO `#DB_PREFIX#_Server_Addons` (`rule`,`addon`,`url`) VALUES ('cssmatch_version','CSSMatch Plugin %','http://www.cssmatch.com/');

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
