#
# HLstats Game Support file for Age of Chivalry
# ----------------------------------------------------
#
# If you want to insert this manually and not via the admin interface
# replace ++DB_PREFIX++ with the current table prefix !
# and import this into your hlstats database


#
# Game Definition
#
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES ('aoc','Age of Chivalry','1','0');


#
# Awards
#
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Flamberge', 'Flamberge', 'kills with Flamberge');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Longsword', 'Longsword', 'kills with Longsword');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Glaive', 'Glaive', 'kills with Glaive');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Dual Daggers', 'Dual Daggers', 'kills with Dual Daggers');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Flamberge & Kite Shield', 'Flamberge & Kite Shield', 'kills with Flamberge & Kite Shield');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Shortsword', 'Shortsword', 'kills with Shortsword');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Warhammer', 'Warhammer', 'kills with Warhammer');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Mace', 'Mace', 'kills with Mace');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Mace & Buckler', 'Mace & Buckler', 'kills with Mace & Buckler');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Broadsword & Evil Shield', 'Mason Broadsword & Shield', 'kills with Mason Broadsword & Shield');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Crossbow', 'Crossbow', 'kills with Crossbow');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Longbow', 'Longbow', 'kills with Longbow');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Longsword & Kite Shield', 'Longsword & Kite Shield', 'kills with Longsword & Kite Shield');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Broadsword & Good Shield', 'Knights Broadsword & Shield', 'kills with Knights Broadsword & Shield');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Hatchet', 'Hatchet', 'kills with Hatchet');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Double Axe', 'Battle Axe', 'kills with Battle Axe');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Flail & Evil Shield', 'Mason Flail & Shield', 'kills with Mason Flail & Shield');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Flail & Good Shield', 'Knights Flail & Shield', 'kills with Knights Flail & Shield');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Javelin', 'Javelin', 'kills with Javelin');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Spiked Mace', 'Spiked Mace', 'kills with Spiked Mace');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Spear & Buckler', 'Spear & Buckler', 'kills with Spear & Buckler');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Spiked Mace & Buckler', 'Spiked Mace & Buckler', 'kills with Spiked Mace & Buckler');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Dagger', 'Dagger', 'kills with Dagger');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Broadsword', 'Broadsword', 'kills with Broadsword');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Throwing Knife', 'Throwing Knife', 'kills with Throwing Knives');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Halberd', 'Halberd', 'kills with Halberd');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'chivalry', 'Fire', 'kills with Fire');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Oil Pot', 'Oil Pot', 'kills with Oil Pot');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('O', 'aoc', 'headshot', 'Headshot/Decapitation', 'Headshots and Decapitations');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc','latency','Best Latency','ms average connection');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Fists','Fists','kills with fists');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'aoc', 'Throwing Axe','Throwing Axe','kills with throwing axes');


#
# Player Actions
#
INSERT INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'aoc', 'headshot', 1, 0, '', 'Headshot/Decapitate Kill', '1', '0', '0', '0');


#
# Teams
#
INSERT IGNORE INTO `++DB_PREFIX++_Team` VALUES (NULL,'aoc','The Mason Order','The Mason Order','0');
INSERT IGNORE INTO `++DB_PREFIX++_Team` VALUES (NULL,'aoc','Agathia Knights','Agathia Knights','0');
INSERT IGNORE INTO `++DB_PREFIX++_Team` VALUES (NULL,'aoc','Spectator','Spectator','0');


#
# Roles
#



#
# Weapons
#
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'flamberge','Flamberge',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'longsword','Longsword',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'halberd','Knights Halberd',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'dagger','Dual Daggers',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'flamberge_kiteshield', 'Flamberge & Kite Shield', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'world','World',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'chivalry','Chivalry',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'sword2','Shortsword',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'warhammer','Warhammer',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'mace','Mace',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'mace_buckler','Mace & Buckler',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'sword01_evil_shield', 'Mason Broadsword & Shield', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'crossbow','Crossbow',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'longbow','Longbow',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'longsword_kiteshield', 'Longsword & Kite Shield', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'sword01_good_shield', 'Knights Broadsword & Shield', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'onehandaxe', 'Hatchet', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'doubleaxe','Battle Axe',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'flail_evil_shield','Mason Flail & Shield',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'flail_good_shield','Knights Flail & Shield',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'thrown_spear', 'Javelin', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'spear_buckler', 'Knights Spear & Buckler', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'dagger2', 'Dagger', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'mtest', 'Footman Longsword', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'thrown_dagger2', 'Thrown Dagger', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'spear_buckler2', 'Mason Spear & Buckler', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'shortsword', 'Spiked Mace', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'spikedmace_buckler','Spiked Mace & Buckler',1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'evil_halberd', 'Mason Halberd', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'env_explosion', 'Fire', 1, 4, 0);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'aoc', 'oilpot', 'Oil Pot', 1, 1, 0);


# end of file
