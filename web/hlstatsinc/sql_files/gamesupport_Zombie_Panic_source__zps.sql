#
# HLstats Game Support file for Zombi Panic: Source
# ----------------------------------------------------
#
# If you want to insert this manually and not via the admin interface
# replace ++DB_PREFIX++ with the current table prefix !
# and import this into your hlstats database

#
# Game Definition
#
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES('zps','Zombie Panic Source','1','0');


#
# Awards
#
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'O','zps','kill_streak_12','kill,kill,kill','12 kills in a row',NULL,NULL);
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'arms', 'Armed and Dangerous', 'kills with Zombie Arms');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'carrierarms', 'Carry on...', 'kills with Carrier Arms');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'ak47', 'AK-47', 'kills with AK-47');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'mp5', 'MP5', 'kills with MP5');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'revolver', 'Revolver', 'kills with Revolver');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'glock', 'Glock 17', 'kills with Glock 17');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'glock18c', 'Glock 18c', 'kills with Glock 18c');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'ppk', 'PPK', 'kills with PPK');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'usp', 'H & K USP', 'kills with H & K USP');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', '870', 'Rem. 870', 'kills with Rem. 870');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'supershorty', 'Super Shorty', 'kills with Super Shorty');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'grenade_frag', 'Grenade', 'kills with Grenades');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'sledgehammer', 'Sledgehammer', 'kills with Sledgehammer');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'crowbar', 'Hello, I''m Gordon Freeman', 'kills with Crowbar');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'chair', 'Sorry, I thought this was pro wrestling', 'kills with Chair');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'keyboard', 'Keyboard', 'kills with Keyboard');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'plank', 'Plank', 'kills with Plank');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'shovel', 'Grave Digger', 'kills with Shovel');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'golf', 'Fore!', 'kills with Golf Club');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'machete', 'Cuttin'' em Down', 'kills with Machete');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'fryingpan', 'Frying Pan', 'kills with Frying Pan');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'spanner', 'Wrench', 'kills with Wrench');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'axe', 'Axe', 'kills with Axe');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'tireiron', 'Tire Iron', 'kills with Tire Iron');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'hammer', 'Hammer', 'kills with Hammer');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'broom', 'Broom', 'kills with Broom');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'pot', 'Pot', 'kills with Pot');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'racket', 'Tennis Racket', 'kills with Tennis Racket');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'latency','Best Latency','ms average connection');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'bat_aluminum','Out of the park!','kills with Bat (Aluminum)');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'bat_wood','Corked','kills with Bat (Wood)');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'm4','M4','kills with M4');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'pipe','Piping hot','kills with Pipe');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES (NULL,'W', 'zps', 'slam','IEDs','kills with IED');

#
# teams
#
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'zps','Undead','Undead','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'zps','Survivor','Survivors','0');
INSERT IGNORE INTO `++DB_PREFIX++_Teams` VALUES (NULL,'zps','Spectator','Spectator','0');

#
# Player Actions
#
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_2',1,0,'','Double Kill','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_3',2,0,'','Triple Kill','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_4',3,0,'','Domination','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_5',4,0,'','Rampage','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_6',5,0,'','Mega Kill','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_7',6,0,'','Ownage','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_8',7,0,'','Ultra Kill','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_9',8,0,'','Killing Spree','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_10',9,0,'','Monster Kill','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_11',10,0,'','Unstoppable','1','0','0','0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES (NULL,'zps','kill_streak_12',11,0,'','God Like','1','0','0','0');

#
# Weapons
#
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','arms','ARMS',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','world','World',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','tireiron','Tire Iron',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','spanner','Wrench',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','sledgehammer','Sledgehammer',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','shovel','Shovel',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','racket','Tennis Racket',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','pot','Pot',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','plank','Wooden Plank',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','physics','Physics',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','machete','Machete',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','keyboard','Keyboard',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','hammer','Hammer',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','golf','Golf Club',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','fryingpan','Frying Pan',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','crowbar','Crowbar',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','chair','Chair',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','broom','Broomstrick',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','axe','Axe',1.50);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','usp','Heckler & Koch USP',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','supershorty','Mossberg Super Shorty',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','ppk','Walther PPK',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','mp5','Heckler & Koch MP5',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','grenade_frag','Grenade',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','glock18c','Glock 18c',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','glock','Glock 17',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','carrierarms','Carrier',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','ak47','AK-47',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','870','Remington 870',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','revolver','Revolver',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','torque','Torque',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','winchester','Winchester Double Barreled Shotgun',1.00);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES (NULL,'zps','m4','M4A1',1.00);

# end of file
