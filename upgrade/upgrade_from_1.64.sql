#
# HLStats Database Upgrade file
# -----------------------------
#
# REPLACE #DB_PREFIX# WITH YOUR CURRENT HLSTATS PREFIX eg. hlstats
#
# To upgrade an existing HLStats 1.64 database to version 1.65, type:
#
#   mysql hlstats_db_name < upgrade_from_1.64.sql
#
#

ALTER TABLE `#DB_PREFIX#_Players` CHANGE COLUMN `icq` `icq` varchar(10), CHANGE COLUMN `myspace` `myspace` varchar(128), CHANGE COLUMN `facebook` `facebook` varchar(128), CHANGE COLUMN `jabber` `jabber` varchar(128), CHANGE COLUMN `steamprofile` `steamprofile` varchar(128);
ALTER TABLE `#DB_PREFIX#_Players` CHANGE COLUMN `sykpe` `skype` varchar(128);