#
# HLstats Game Support file for Counter-Strike: Global Offensive
# --------------------------------------------------------------
#
# If you want to insert this manually and not via the admin interface
# replace ++DB_PREFIX++ with the current table prefix !
# and import this into your hlstats database

#
# Game Definition
#
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES ('csgo','Counter-Strike: Global Offensive','1','0');

#
# Awards
#
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'O','csgo','Defused_The_Bomb','Top Defuser','bomb defusions',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'ak47', 'AK47', 'kills with ak47',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'AUG', 'Aug', 'kills with aug',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'awp', 'AWP', 'kills with awp',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'deagle', 'Desert Eagle', 'kills with deagle',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'elite', 'Dual Berretta Elites', 'kills with elite',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'famas', 'Fusil Automatique', 'kills with famas',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'fiveseven', 'Five Seven', 'kills with fiveseven',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'g3sg1', 'G3 SG1', 'kills with g3sg1',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'galilar', 'Galil', 'kills with galil',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'glock', 'Glock', 'kills with glock',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'hegrenade', 'High Explosive Grenade', 'kills with grenade',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'firebomb', 'Incendiary Grenade', 'kills with inferno',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'knife', 'Knife Maniac', 'knifings',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'm249', 'M249', 'kills with m249',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'm4a1', 'M4A4', 'kills with m4a4',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'mac10', 'MAC-10', 'kills with mac10',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'mag7', 'MAG-7', 'kills with mag7',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'O', 'csgo', 'headshot', 'Headshot King', 'shots in the head',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'latency', 'Best Latency', 'ms average connection',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'O', 'csgo', 'round_mvp', 'Most Valuable Player', 'times earning Round MVP',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'mostkills', 'Most Kills', 'kills',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'suicide', 'Suicides', 'suicides',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'teamkills', 'Team Killer', 'team kills',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'mp7', 'MP7', 'kills with mp7',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'mp9', 'MP9', 'kills with mp9',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'negev', 'Negev', 'kills with negev',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'nova', 'Nova', 'kills with nova',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'hkp2000', 'P2000', 'kills with p2000',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'p250', 'P250', 'kills with p250',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'p90', 'P90', 'kills with p90',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'bizon', 'PP-Bizon', 'kills with pp-bizon',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'sawedoff', 'Sawed-Off', 'kills with sawed-off',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'scar20', 'SCAR-20', 'kills with scar-20',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'sg553', 'SG 553', 'kills with sg553',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'ssg08', 'SSG 08', 'kills with ssg08',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'tec9', 'Tec-9', 'kills with Tec-9',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'O', 'csgo', 'Defused_The_Bomb', 'Top Defuser', 'bomb defusions',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'O', 'csgo', 'Planted_The_Bomb', 'Top Demolitionist', 'bomb plantings',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'O', 'csgo', 'Killed_A_Hostage', 'Top Hostage Killer', 'hostages killed',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'O', 'csgo', 'Rescued_A_Hostage', 'Top Hostage Rescuer', 'hostages rescued',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'ump45', 'UMP-45', 'kills with ump45',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'xm1014', 'XM automatic Shotgun', 'kills with xm1014',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'csgo', 'taser', 'Zeus x27', 'kills with taser',NULL,NULL);

#
# Actions
#
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Begin_Bomb_Defuse_Without_Kit', 0, 0, 'CT', 'Start Defusing the Bomb Without a Defuse Kit', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Begin_Bomb_Defuse_With_Kit', 0, 0, 'CT', 'Start Defusing the Bomb With a Defuse Kit', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Planted_The_Bomb', 10, 2, 'TERRORIST', 'Plant the Bomb', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Defused_The_Bomb', 10, 0, 'CT', 'Defuse the Bomb', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Touched_A_Hostage', 0, 0, 'CT', 'Touch a Hostage', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Rescued_A_Hostage', 5, 1, 'CT', 'Rescue a Hostage', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Killed_A_Hostage', -15, 1, 'CT', 'Kill a Hostage', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Spawned_With_The_Bomb', 2, 0, 'TERRORIST', 'Spawn with the Bomb', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Got_The_Bomb', 2, 0, 'TERRORIST', 'Pick up the Bomb', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Dropped_The_Bomb', -2, 0, 'TERRORIST', 'Drop the Bomb', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'CTs_Win', 0, 2, 'CT', 'All Terrorists eliminated', '0', '0', '1', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Terrorists_Win', 0, 2, 'TERRORIST', 'All Counter-Terrorists eliminated', '0', '0', '1', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'All_Hostages_Rescued', 0, 10, 'CT', 'Counter-Terrorists rescued all the hostages', '0', '0', '1', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Target_Bombed', 0, 5, 'TERRORIST', 'Terrorists bombed the target', '0', '0', '1', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Bomb_Defused', 0, 5, 'CT', 'Counter-Terrorists defused the bomb', '0', '0', '1', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Escaped_As_VIP', 0, 10, 'CT', 'VIP escaped', '0', '0', '1', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Assassinated_The_VIP', 0, 6, 'TERRORIST', 'Terrorists assassinated the VIP', '0', '0', '1', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'Became_VIP', 1, 0, 'CT', 'Become the VIP', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'headshot', 1, 0, '', 'Headshot', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'round_mvp', 0, 0, '', 'Round MVP', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_2', 1, 0, '', 'Double Kill (2 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_3', 2, 0, '', 'Triple Kill (3 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_4', 3, 0, '', 'Domination (4 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_5', 4, 0, '', 'Rampage (5 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_6', 5, 0, '', 'Mega Kill (6 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_7', 6, 0, '', 'Ownage (7 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_8', 7, 0, '', 'Ultra Kill (8 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_9', 8, 0, '', 'Killing Spree (9 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_10', 9, 0, '', 'Monster Kill (10 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_11', 10, 0, '', 'Unstoppable (11 kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_streak_12', 11, 0, '', 'God Like (12+ kills)', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'domination', 5, 0, '', 'Domination', '0', '1', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'revenge', 3, 0, '', 'Revenge', '0', '1', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'kill_assist', 2, 0, '', 'Kill Assist', '0', '1', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL,'csgo', 'mvp', 10, 0, '', 'Most valuable player', '1', '0', '0', '0');

#
# Teams
#
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'csgo','TERRORIST','Terrorist','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'csgo','CT','Counter-Terrorist','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'csgo','SPECTATOR','Spectator','0');

#
# Roles
#
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'csgo','st6','SEAL Team 6','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'csgo','phoenix','Phoenix Connexion','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'csgo','leet','Elite Crew','0');

#
# Weapons
#
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','knife','Knife',2.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','inferno','Incendiary Grenade',1.80);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','hegrenade','High Explosive Grenade',1.80);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','p250','P250',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','mac10','MAC-10',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','fiveseven','FN Five-Seven',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','mp9','MP9',1.40);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','hkp2000','P2000',1.40);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','glock','Glock-18',1.40);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','elite','Dual Berretta Elites',1.40);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','sawedoff','Sawed-Off',1.30);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','nova','Nova',1.30);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','mp7','MP7',1.30);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','mag7','MAG-7',1.30);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','bizon','PP-Bizon',1.30);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','ump45','H&K UMP45',1.30);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','tec9','Tec-9',1.20);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','p90','FN P90',1.20);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','deagle','Desert Eagle',1.20);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','xm1014','XM1014',1.10);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','ssg08','SSG 08',1.10);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','galilar','Galil AR',1.10);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','galil','Galil',1.10);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','taser','Zeus x27',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','sg553','SG 553',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','negev','Negev',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','m4a1','M4A4',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','m249','M249 PARA Light Machine Gun',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','awp','AWP',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','aug','Steyr Aug',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','ak47','Kalashnikov AK-47',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','scar20','Scar-20',0.80);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'csgo','g3sg1','H&K G3/SG1 Sniper Rifle',0.80);

