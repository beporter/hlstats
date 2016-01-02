#
# HLstats Game Support file for Fortress Forever
# ----------------------------------------------------
#
# If you want to insert this manually and not via the installer
# replace ++DB_PREFIX++ with the current table prefix !


#
# Game Definition
#
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES ('ff','Fortress Forever','1','0');


#
# Awards
#
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_tranq', 'Drug Dealer', 'kills with tranq gun',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_supershotgun', 'Super Shots', 'kills with super shotgun',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_supernailgun', 'Super Nails', 'kills with super nailgun',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_spanner', 'Whack-A-Mole', 'kills with spanner',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_sniperrifle', 'Snipes-A-Holic', 'kills with sniper rifle',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_rpg', 'ROCKET MAN', 'kills with rpg',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_shotgun', 'N00b shotwhore', 'kills with normal shotgun',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_railgun', 'Pew pew laz0r beams', 'kills with railgun',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_pipelauncher', 'Bouncy pipe whore', 'kills with blues',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_medkit', 'Aids Monger', 'kills with aids',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_knife', 'My name is Skanky Butterpuss(e)', 'kills with knife',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_grenadelauncher', 'Click BOOM', 'kills with grellows',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_flamethrower', 'I like fire!', 'kills with flamethrower',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_autorifle', 'Gay Sniper', 'kills with AR',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_dispenser', 'Remote Control GOD', 'kills with dispenser',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'weapon_assaultcannon', 'Best Fatty Ever!', 'kills with ass cannon',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'SentryGun', 'Robocop', 'kills with SG',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'grenade_normal', 'Flying chicken!', 'kills with frag grenade',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'grenade_mirv', 'Look I can kill SGs!', 'kills with mirv grenade',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'grenade_emp', 'Wheres all my ammo!', 'kills with emp grenade',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'BOOM_HEADSHOT', 'Sharp Eye', 'headshots',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'DETPACK', 'Earthquake Machine', 'kills with detpack',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff', 'backstab', 'Backstabber', 'backstab kills',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'ff','latency','Best Latency','ms average connection',NULL,NULL);



#
# Player Actions
#
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_2', 1, 0, '', 'Double Kill (2 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_3', 2, 0, '', 'Triple Kill (3 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_4', 3, 0, '', 'Domination (4 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_5', 4, 0, '', 'Rampage (5 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_6', 5, 0, '', 'Mega Kill (6 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_7', 6, 0, '', 'Ownage (7 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_8', 7, 0, '', 'Ultra Kill (8 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_9', 8, 0, '', 'Killing Spree (9 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_10', 9, 0, '', 'Monster Kill (10 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_11', 10, 0, '', 'Unstoppable (11 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'kill_streak_12', 11, 0, '', 'God Like (12+ kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'headshot', 1, 0, '', 'Headshot kill', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'sentrygun_upgraded', 1, 0, '', 'Upgraded Sentry Gun', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'build_sentrygun', 1, 0, '', 'Built Sentry Gun', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'build_dispenser', 1, 0, '', 'Built Dispenser', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'dispenser_detonated', -1, 0, '', 'Dispenser Detonated', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'sentry_detonated', -1, 0, '', 'Sentry Gun Detonated', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'sentry_dismantled', -1, 0, '', 'Sentry Gun Dismantled', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'dispenser_dismantled', -1, 0, '', 'Dispenser Dismantled', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'build_mancannon', 1, 0, '', 'Built Jump Pad', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'mancannon_detonated', -1, 0, '', 'Detonated Jump Pad', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'build_detpack', 1, 0, '', 'Placed Detpack', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'flag_touch', 3, 0, '', 'Flag Picked Up', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'flag_capture', 3, 0, '', 'Flag Captured', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'flag_dropped', -3, 0, '', 'Flag Dropped', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'flag_thrown', -3, 0, '', 'Flag Thrown', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES(NULL, 'ff', 'disguise_lost', 1, 0, '', 'Uncovered Enemy', '', '1', '', '');


#
# Teams
#
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'ff','#FF_TEAM_RED','Red Team','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'ff','#FF_TEAM_BLUE','Blue Team','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'ff','#FF_TEAM_YELLOW','Yellow Team','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'ff','#FF_TEAM_GREEN','Green Team','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'ff','Red','Red Team','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'ff','Blue','Blue Team','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'ff','Attackers','Attackers','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'ff','Defenders','Defenders','0');



#
# Roles
#
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','Scout','Scout','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','Sniper','Sniper','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','Soldier','Soldier','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','Demoman','Demoman','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','Medic','Medic','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','HWGuy','HWGuy','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','Pyro','Pyro','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','Spy','Spy','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','Engineer','Engineer','0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES (NULL,'ff','Civilian','Civilian','0');



#
# Weapons
#
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_railgun', 'Railgun', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_tranq', 'Tranq Gun', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_medkit', 'Medkit', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_spanner', 'Spanner', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_crowbar', 'Crowbar', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_shotgun', 'Shotgun', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'grenade_napalm', 'Napalm Grenade', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_ic', 'IC', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'grenade_nail', 'Nail Grenade', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_supershotgun', 'Super Shotgun', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_supernailgun', 'Super Nailgun', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_sniperrifle', 'Sniper Rifle', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_rpg', 'Rocket Launcher', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_pipelauncher', 'Pipe Launcher', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_knife', 'Knife', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_grenadelauncher', 'Grenade Launcher', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_flamethrower', 'Flamethrower', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'Dispenser', 'Dispenser', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_autorifle', 'Auto Rifle', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_assaultcannon', 'Assault Cannon', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'SentryGun', 'Sentry Gun', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'grenade_normal', 'Frag Grenade', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'grenade_mirv', 'Mirv Grenade', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'grenade_emp', 'Emp Grenade', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'DETPACK', 'Detpack', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_umbrella','Umbrella', 10.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'grenade_gas','Gas Grenade', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_tommygun', 'Tommygun', 1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL, 'ff', 'weapon_nailgun', 'Nailgun', 1.00);


# end of file
