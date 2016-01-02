#
# HLstats Game Support file for The Hidden: Source
# ----------------------------------------------------
#
# If you want to insert this manually and not via the installer
# replace ++DB_PREFIX++ with the current table prefix !


#
# Game Definition
#
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES('hidden','The Hidden: Source','1','0');


#
# Awards
#
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden', 'fn2000', 'FN2000 Assault Rifle', 'kills with FN2000 Assault Rifle');
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden', 'p90', 'FN P90 Sub Machine Gun', 'kills with FN P90 Sub Machine Gun');
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden', 'shotgun', 'Remington 870 MCS Shotgun', 'kills with Remington 870 MCS Shotgun');
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden', 'fn303', 'FN303 Less Lethal Launcher', 'kills with FN303 Less Lethal Launcher');
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden', 'pistol', 'FN FiveSeven Pistol', 'kills with FN FiveSeven Pistol');
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden', 'pistol2', 'FNP-9 Pistol', 'kills with FNP-9 Pistol');
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden', 'knife', 'Kabar D2 Knife', 'kills with Kabar D2 Knife');
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden', 'grenade_projectile', 'Pipe Bomb', 'kills with Pipe Bomb');
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden', 'physics', 'Physics', 'kills with Physics');
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES (NULL,'W', 'hidden','latency','Best Latency','ms average connection');

#
# Player Actions
#
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_2', 1, 0, '', 'Double Kill (2 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_3', 2, 0, '', 'Triple Kill (3 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_4', 3, 0, '', 'Domination (4 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_5', 4, 0, '', 'Rampage (5 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_6', 5, 0, '', 'Mega Kill (6 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_7', 6, 0, '', 'Ownage (7 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_8', 7, 0, '', 'Ultra Kill (8 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_9', 8, 0, '', 'Killing Spree (9 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_10', 9, 0, '', 'Monster Kill (10 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_11', 10, 0, '', 'Unstoppable (11 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'hidden', 'kill_streak_12', 11, 0, '', 'God Like (12+ kills)', '1', '', '', '');


#
# Teams
#
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'hidden','Hidden','Subject 617','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'hidden','IRIS','I.R.I.S.','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'hidden','Spectator','Spectator','0');


#
# Roles
#


#
# Weapons
#

INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'hidden', 'fn2000','FN2000 Assault Rifle',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'hidden', 'p90','FN P90 Sub Machine Gun',2.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'hidden', 'shotgun','Remington 870 MCS Shotgun',2.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'hidden', 'fn303','FN303 Less Lethal Launcher',2.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'hidden', 'pistol','FN FiveSeven Pistol',3.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'hidden', 'pistol2','FNP-9 Pistol',3.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'hidden', 'knife','Kabar D2 Knife',2.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'hidden', 'grenade_projectile','Pipe Bomb',2.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'hidden', 'physics','Physics',3.00);


# end of file
