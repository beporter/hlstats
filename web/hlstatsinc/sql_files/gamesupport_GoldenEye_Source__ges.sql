#
# HLstats Game Support file for GoldenEye: Source
# ----------------------------------------------------
#
# If you want to insert this manually and not via the admin interface
# replace ++DB_PREFIX++ with the current table prefix !
# and import this into your hlstats database

#
# Game Definition
#
INSERT IGNORE INTO `++DB_PREFIX++_Games` VALUES ('ges','GoldenEye: Source','1','0');

#
# Awards
#
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_ProximityMine', 'Proximity Pulvarizer', 'kills with Proximity Mines');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_AutoShotgun', 'Automatic Shotgun', 'kills with Automatic Shotgun');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_Phantom', 'Phantastic Phantom', 'kills with Phantom');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_Knife', 'Silent Assassin', 'kills with Hunting Knife');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_D5K', 'D5K', 'kills with D5K Deutsche');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_SilverPP7', 'Silver PP7', 'kills with Silver PP7');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_DD44', 'DD44', 'kills with DD44');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_Grenade', 'Grenade', 'kills with Grenades');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_CougarMagnum', 'Cougar Magnum', 'kills with Cougar Magnum');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_D5K_SILENCED', 'Silenced D5K', 'kills with D5K (Silenced)');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_Shotgun', 'Shotgun', 'kills with Shotgun');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_Klobb', 'Klobbering Time', 'kills with Klobb');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_RCP90', 'RCP 4 Death', 'kills with RC-P90');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_RemoteMine', 'Remotely Remove', 'kills with Remote Mines');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_KF7Soviet', 'KF7 Soviet', 'kills with KF7 Soviet');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_ZMG', 'ZMG', 'kills with ZMG');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_SniperRifle', 'A View To A Kill', 'kills with Sniper Rifle');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_GoldPP7', 'Golden PP7', 'kills with Golden PP7');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_AR33', 'ARYOU33?', 'kills with US AR33 Assault');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_GoldenGun', 'Unknown Pain', 'kills with Golden Gun');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_ThrowingKnife', 'Thorwing Knives', 'kills with Throwing Knives');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_PP7', 'PKing Soup', 'kills with PP7');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_PP7_SILENCED', 'Silenced PP7', 'kills with PP7 (Silenced)');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_TimedMine', 'Timed Termination', 'kills with Timed Mines');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_MilitaryLaser', 'Military Laser', 'kills with Military Laser');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_GrenadeLauncher', 'Grenade Launcher', 'kills with Grenade Launcher');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_Rocket', 'Rocket Launcher', 'kills with Rocket Launcher');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_Taser', 'Taser', 'kills with Taser');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_SniperButt', 'Sniper Butt', 'kills with Sniper Butt');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_Slapper', 'Bitch Fighter', 'kills with Slappers');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', '#GE_RocketLauncher', ', Rocket Launcher', 'kills with Rocket Launcher');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', 'latency', 'Lowest Ping','ms average connection');
INSERT IGNORE INTO `++DB_PREFIX++_Awards` VALUES ('W', 'ges', 'mostkills', 'Bond, James Bond', 'kills');


#
# Player Actions
#
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'headshot', 1, 0, '', 'Headshot Kill', '1', '0', '0', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_2', 1, 0, '', 'Double Kill (2 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_3', 2, 0, '', 'Triple Kill (3 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_4', 3, 0, '', 'Domination (4 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_5', 4, 0, '', 'Rampage (5 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_6', 5, 0, '', 'Mega Kill (6 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_7', 6, 0, '', 'Ownage (7 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_8', 7, 0, '', 'Ultra Kill (8 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_9', 8, 0, '', 'Killing Spree (9 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_10', 9, 0, '', 'Monster Kill (10 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_11', 10, 0, '', 'Unstoppable (11 kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'kill_streak_12', 11, 0, '', 'God Like (12+ kills)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'Round_Win', 5, 0, '', 'Round Win', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'Round_Win_Team', 0, 3, '', 'Team Round Win', '', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_DEADLY', 10, 0, '', 'Most Deadly', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_HONORABLE', 5, 0, '', 'Most Honorable', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_PROFESSIONAL', 10, 0, '', 'Most Professional', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_MARKSMANSHIP', 1, 0, '', 'Marksmanship Award', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_AC10', 2, 0, '', 'AC-10 Award', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_FRANTIC', 2, 0, '', 'Most Frantic', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_WTA', 1, 0, '', 'Where''s the Ammo?', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_LEMMING', -1, 0, '', 'Lemming (suicide)', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_LONGIN', 1, 0, '', 'Longest Innings', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_SHORTIN', -1, 0, '', 'Shortest Innings', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_DISHONORABLE', -10, 0, '', 'Most Dishonorable', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_NOTAC10', 4, 0, '', 'Where''s the Armor?', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'GE_AWARD_MOSTLYHARMLESS', -1, 0, '', 'Mostly Harmless', '1', '', '', '');

INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'Match_Win', 15, 0, '', 'Match Won', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'achievement_unlocked', 10, 0, '', 'Achievment earned', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'alldatfound', 150, 0, '', 'Found all Facility Cartridges', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'camper_eliminated', 10, 0, '', 'Killed a Camper', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'cr_team_lose', 0, -1, '', '(CR) Team Lose', '0', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'cr_team_tie', 0, 0, '', '(CR) Tie in Teamplay', '0', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'cr_team_tie', 0, 0, '', '(CR) Tie in Teamplay', '0', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'cr_team_win', 0, 1, '', '(CR) Team Win', '0', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ctk_tokencapture', 6, 0, '', '(CTK) Captured the Key', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ctk_tokencaptured', 0, 1, '', '(CTK) Captured the Key in Teamplay', '0', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ctk_tokendefended', 3, 0, '', '(CTK) Killed a Key bearer', '', '1', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ctk_tokendefense', 0, 1, '', '(CTK) Defended the Key in Teamplay', '', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ctk_tokendropped', -3, 0, '', '(CTK) Lost the Key', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ctk_tokenlost', 0, -1, '', '(CTK) Lost the Key in Teamplay', '', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ctk_tokenpicked', 6, 0, '', '(CTK) Picked up the Key', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ctk_tokenpicked', 6, 0, '', '(CTK) Picked up the Key', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ctk_tokenpickup', 0, 1, '', '(CTK) Picked up the Key in Teamplay', '', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'firstblood_kill', 5, 0, '', 'First Blood Kill', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'fullcamping', -30, 0, '', 'Deep Camping', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'fyeo_case_picked', 10, 0, '', '(FYEO) Picked up the Case', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'fyeo_caseholder_killed', 10, 0, '', '(FYEO) Killed the Case Holder', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'fyeo_eliminated', 3, 0, '', '(FYEO) Eliminated a Player', '', '1', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'griefing', -10, 0, '', 'Griefing', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'lald_baronwinner', 20, 0, '', '(LaLD) Round Win as Baron Samedi', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'lald_eliminated', 2, 0, '', '(LaLD) Eliminated a Player', '', '1', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'lald_flawlessvictory', 10, 0, '', '(LaLD) Flawless Victory', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'lald_ggpickup', 10, 0, '', '(LaLD) Golden Gun Pickup', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'lald_killedbaron', 20, 0, '', '(LaLD) Killed Baron Samedi', '', '1', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'lald_survived', 5, 0, '', '(LaLD) Survived Through the Entire Round', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ld_flagescape', 2, 0, '', '(LD) Escaped Combat while holding a Flag', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ld_flagpickup', 15, 0, '', '(LD) Picked Up a Flag', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ld_flagslapped', 20, 0, '', '(LD) Killed a Flag Holder with Slappers', '', '1', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ld_flagsuicide', -5, 0, '', '(LD) Suicide while holding a Flag', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ldflaghits', 1, 0, '', '(LD) Flag Hit', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ldflagpoints', 1, 0, '', '(LD) Flag Point', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'longdist_kill', 5, 0, '', 'Long Distance (170+ ft away) Shot Kill', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'massbounces_kill', 5, 0, '', 'Multiple Explosive Bounces Kill', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'mwgg_ggpickup', 10, 0, '', '(MWGG) Picked up the Golden Gun', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'mwgg_kill', 2, 0, '', '(MWGG) Kill with a Golden Gun', '', '1', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'mwgg_killed', 10, 0, '', '(MWGG) Killed the Golden Gun Holder', '', '1', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'mwgg_suicide', -5, 0, '', '(MWGG) Suicide while holding the Golden Gun', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'penetrated_shot', 2, 0, '', 'Penetrated Shot Kill', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'powerused', 2, 0, '', '(CR) Used Power', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'restoredpower', 5, 0, '', '(CR) Restored Power', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'rocket', 3, 0, '', 'Silo Rocket Launch', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'roundend_kill', 10, 0, '', 'After-round-end Kill', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'shuttle', 2, 0, '', 'Aztec Launchpad Opened', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'ufo_shot', 2, 0, '', 'UFO Kill', '1', '', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'yolt_eliminated', 10, 0, '', '(YOLT) Eliminated a Player', '', '1', '', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'yolt_elimination', 1, 0, '', '(YOLT) Eliminated a Player in Teamplay', '', '', '1', '');
INSERT IGNORE INTO `++DB_PREFIX++_Actions` VALUES ('ges', 'zoomed_shot', 1, 0, '', 'Zoomed-in Shot Kill', '1', '', '', '');

#
# Teams
#
INSERT IGNORE INTO `++DB_PREFIX++_Team` VALUES ('ges', 'MI6', 'MI6', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Team` VALUES ('ges', 'Janus', 'Janus', '0');


#
# Roles
#
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'jaws', 'Jaws', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'bond', 'Bond', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'boris', 'Boris', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'Mayday', 'May Day', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'Mishkin', 'Mishkin', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'oddjob', 'Oddjob', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'ourumov', 'Ourumov', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'samedi', 'Samedi', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'valentin', 'Valentin', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', 'female_scientist', 'Scientist', '0');
INSERT IGNORE INTO `++DB_PREFIX++_Roles` VALUES ('ges', '006_mi6', '006', '0');



#
# Weapons
#
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_ProximityMine', 'Proximity Mines', 0.5);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_AutoShotgun', 'Automatic Shotgun', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_Phantom', 'Phantom', 0.8);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_Knife', 'Hunting Knife', 1.8);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_D5K', 'D5K Deutsche', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_SilverPP7', 'Silver PP7', 0.7);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_DD44', 'DD44', 1.2);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_Grenade', 'Grenade', 1.4);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_CougarMagnum', 'Cougar Magnum', 1.2);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_D5K_SILENCED', 'D5K (Silenced)', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_Shotgun', 'Shotgun', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_Klobb', 'Klobb', 1.2);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_RCP90', 'RC-P90', 0.8);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_RemoteMine', 'Remote Mines', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_KF7Soviet', 'KF7 Soviet', 0.8);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_ZMG', 'ZMG', 0.8);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_SniperRifle', 'Sniper Rifle', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_GoldPP7', 'Golden PP7', 0.2);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_AR33', 'US AR33 Assault', 0.8);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_GoldenGun', 'Golden Gun', 0.5);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_ThrowingKnife', 'Throwing Knives', 1.6);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_PP7', 'PP7', 1.2);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_PP7_SILENCED', 'PP7 (Silenced)', 1.20);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_TimedMine', 'Timed Mines', 1.8);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_MilitaryLaser', 'Military Laser', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_GrenadeLauncher', 'Grenade Launcher', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_Rocket', 'Rocket Launcher', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_Taser', 'Taser', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_SniperButt', 'Sniper Butt', 1);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_Slapper', 'Slappers', 1.8);
INSERT IGNORE INTO `++DB_PREFIX++_Weapons` VALUES ('ges', '#GE_RocketLauncher', 'Rocket Launcher', 1);


# end of file