#
# HLStats Database Upgrade file
# -----------------------------
#
# REPLACE #DB_PREFIX# WITH YOUR CURRENT HLSTATS PREFIX eg. hlstats
#
# To upgrade an existing HLStats 1.61 database to version 1.62, type:
#
#   mysql hlstats_db_name < upgrade_from_1.61.sql
#
#

ALTER TABLE `#DB_PREFIX#_Servers` CHANGE `name` `name` varchar(128) NOT NULL DEFAULT '';
ALTER TABLE `#DB_PREFIX#_Servers` CHANGE `publicaddress` `publicaddress` varchar(128) NOT NULL DEFAULT '';
ALTER TABLE `#DB_PREFIX#_Servers` CHANGE `rcon_password` `rcon_password` varchar(64) NOT NULL DEFAULT '';


