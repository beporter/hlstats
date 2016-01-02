<?php
/**
 * Original development:
 * +
 * + HLStats - Real-time player and clan rankings and statistics for Half-Life
 * + http://sourceforge.net/projects/hlstats/
 * +
 * + Copyright (C) 2001  Simon Garner
 * +
 *
 * Additional development:
 * +
 * + UA HLStats Team
 * + http://www.unitedadmins.com
 * + 2004 - 2007
 * +
 *
 *
 * Current development:
 * +
 * + Johannes 'Banana' Keßler
 * + http://hlstats.sourceforge.net
 * + 2007 - 2012
 * +
 *
 * This program is free software is licensed under the
 * COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
 *
 * You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
 * along with this program; if not, visit http://hlstats-community.org/License.html
 *
 */

// Check PHP configuration
if (version_compare(phpversion(), "5.2.0", "<")) {
	die("HLStats requires PHP version 5.2.0 or newer (you are running PHP version " . phpversion() . ").");
}

date_default_timezone_set('Europe/Berlin');

// if you have problems with your installation
// activate this paramter by setting it to true
define('SHOW_DEBUG',true);

// do not display errors in live version
if(SHOW_DEBUG === true) {
	error_reporting(-1);
	ini_set('display_errors',true);
}
else {
	ini_set('display_errors',false);
}

// load config
require('./hlstatsinc/hlstats.conf.php');

/**
 * load required stuff
 *
 * functions functions.inc.php
 * db class
 * general classes like table class
 */
require("hlstatsinc/functions.inc.php");

// db class and options
$DB = new mysqli(DB_ADDR,DB_USER,DB_PASS,DB_NAME);
if($DB->connect_errno) {
	var_dump($DB->connect_error);
	die('Could not connect to the MySQL Server. Check your configuration.');
}
$DB->query("SET NAMES utf8");
$DB->query("SET collation_connection = 'utf8_unicode_ci'");
$DB->set_charset("utf8");

// get the hlstats options
$g_options = getOptions();

// hlstats url
$hlsUrl = "http://".$_SERVER['SERVER_NAME'].str_replace("xml.php","",$_SERVER['SCRIPT_NAME']);

if(!isset($_GET['mode'])) {
	$_GET['mode'] = false;
}
if(!isset($_GET['serverId'])) {
	$_GET['serverId'] = false;
}
if(!isset($_GET['gameCode'])) {
	$_GET['gameCode'] = false;
}

// this will also return if nothing works or the parameter are wrong
$xmlBody = "<message>Service not available.</message>";

// check if we are allowed to use this feature
if($g_options['allowXML'] == "1") {
	// we are allowed to return some xml data
	switch ($_GET['mode']) {
		/**
		 * return only the top 10 players for given gameCode
		 * BOTs are ignored
		 */
		case 'playerlist':
			$gameCode = sanitize($_GET['gameCode']);
			if(!empty($gameCode) && validateInput($gameCode,'nospace')) {
				$query = $DB->query("SELECT
			    			t1.playerId,
							t1.lastName,
							t1.skill
			    		FROM `".DB_PREFIX."_Players` as t1
						INNER JOIN `".DB_PREFIX."_PlayerUniqueIds` as t2
			    			ON t1.playerId = t2.playerId
			    		WHERE t1.game = '".$DB->real_escape_string($gameCode)."'
			    			AND t1.hideranking=0
			    			AND t2.uniqueId not like 'BOT:%'
			    		ORDER BY t1.skill DESC
			    		LIMIT 10");
				$xmlBody = "<message>Top 10 player list</message>";
				$xmlBody .= "<players>";
				if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
				while ($playerData = $query->fetch_assoc()) {
					$xmlBody .="<player>";
					$xmlBody .="<name><![CDATA[".htmlentities($playerData['lastName'],ENT_COMPAT,"UTF-8")."]]></name>";
					$xmlBody .="<skill>".$playerData['skill']."</skill>";
					$xmlBody .="<profile><![CDATA[".$hlsUrl."index.php?mode=playerinfo&player=".$playerData['playerId']."]]></profile>";
					$xmlBody .="</player>";
				}
				$xmlBody .= "</players>";
			}
			else {
				$xmlBody = "<message>No game Code given.</message>";
			}
		break;

		/**
		 * get the information about the top 100 players to be included in the worldstats
		 * currently only working if MODE = Normal
		 * which means that only STEAM_IDs are working....
		 */
		case 'worldstats':
			$gameCode = sanitize($_GET['gameCode']);
			if(!empty($gameCode) && validateInput($gameCode,'nospace') && $g_options['MODE'] === "Normal") {
				$query = $DB->query("SELECT p.playerId, p.lastName, p.skill,
									p.oldSkill, p.kills, p.deaths,
									pu.uniqueId, p.game, p.clan,
									ec.country, ec.countryCode, MAX(ec.eventTime) AS lastConnect
			    		FROM `".DB_PREFIX."_Players` AS p
						INNER JOIN `".DB_PREFIX."_PlayerUniqueIds` AS pu
			    			ON p.playerId = pu.playerId
						INNER JOIN `".DB_PREFIX."_Events_Connects` AS ec
							ON ec.playerId = p.playerId
			    		WHERE p.game = '".$DB->real_escape_string($gameCode)."'
			    			AND p.hideranking = 0
			    			AND pu.uniqueId not like 'BOT:%'
						GROUP BY ec.playerId
			    		ORDER BY p.skill DESC
			    		LIMIT 100");

				$xmlBody = "<message>Top 100 player worldstats list</message>";
				$xmlBody .= "<players>";
				while ($playerData = $query->fetch_assoc()) {
					$xmlBody .= "<player>";
					$xmlBody .= "<name><![CDATA[".htmlentities($playerData['lastName'],ENT_COMPAT,"UTF-8")."]]></name>";
					$xmlBody .= "<skill>".$playerData['skill']."</skill>";
					$xmlBody .= "<oldSkill>".$playerData['oldSkill']."</oldSkill>";
					$xmlBody .= "<profile><![CDATA[".$hlsUrl."index.php?mode=playerinfo&player=".$playerData['playerId']."]]></profile>";
					$xmlBody .= "<uniqueId>".$playerData['uniqueId']."</uniqueId>";
					$xmlBody .= "<kills>".$playerData['kills']."</kills>";
					$xmlBody .= "<deaths>".$playerData['deaths']."</deaths>";
					$xmlBody .= "<country>".$playerData['country']."</country>";
					$xmlBody .= "<countryCode>".$playerData['countryCode']."</countryCode>";
					$xmlBody .= "<lastConnect>".$playerData['lastConnect']."</lastConnect>";
					$xmlBody .= "<game>".$playerData['game']."</game>";
					$xmlBody .= "<clan>".$playerData['clan']."</clan>";
					$xmlBody .= "</player>";
				}
				$xmlBody .= "</players>";
			}
			else {
				$xmlBody = "<message>No game code given.</message>";
			}
		break;

		/**
		 * return some information about the given server like livestats view
		 */
		case 'serverinfo':
		default:
			// we want some server info
			$serverId = sanitize($_GET['serverId']);
			if(!empty($serverId) && validateInput($serverId,'digit')) {
				// check if we have such server
				$query = $DB->query("
						SELECT
							s.serverId,
							s.name,
							s.address,
							s.port,
							s.publicaddress,
							s.game,
							s.rcon_password,
							g.name gamename
						FROM `".DB_PREFIX."_Servers` AS s
						LEFT JOIN `".DB_PREFIX."_Games` AS g
							ON s.game = g.code
						WHERE s.serverId = ".$DB->real_escape_string($serverId)."
				");
				if ($query->num_rows === 1) {
					// get the server data
					$serverData = $query->fetch_assoc();

					$xmlBody = "<message>Server Information</message>";
					$xmlBody .= "<server>";
					$xmlBody .= "<name>".$serverData['name']."</name>";
					$xmlBody .= "<ip>".$serverData['address']."</ip>";
					$xmlBody .= "<port>".$serverData['port']."</port>";
					$xmlBody .= "<game>".$serverData['gamename']."</game>";

					// load the required stuff
					include('hlstatsinc/binary_funcs.inc.php');
					include('hlstatsinc/hlquery_funcs.inc.php');

					$xmlBody .= "<additional>";
					// run some query to display some more info
					if ($serverData['publicaddress'] != "") {
						# Port maybe different
						$temp = explode(':', $serverData['publicaddress']);
						$server_ip = $serverData['address'];
						if (isset($temp[1])) {
							$server_port = $temp[1];
						}
						else {
							$server_port = $serverData['port'];
						}
					}
					else {
						$server_ip = $serverData['address'];
						$server_port = $serverData['port'];
					}

					// check if we have a rcon password
					$server_rcon = false;
					if($serverData['rcon_password'] != "") {
						$server_rcon = $serverData['rcon_password'];
					}

					$server_hltv = array();
					$server_players = array();

					# Get info
					if (($server_details = Source_A2S_Info($server_ip, $server_port)) !== false) {
						if ($server_details['gametype'] == 73) {
							$serverData['source'] = 1;
							$server_details['address'] = $server_ip.':'.$server_port;
						}
						else {
							$serverData['source'] = 0;
						}

						$server_details['hltvcount'] = count($server_hltv);

						$server_details['players_real'] = $server_details['numplayers'];
						$server_details['players_real'] -= $server_details['numbots'];
						$server_details['players_real'] -= $server_details['hltvcount'];

						$server_details['players_connecting'] = $server_details['numplayers'];
						$server_details['players_connecting'] -= $server_details['numbots'];
						$server_details['players_connecting'] -= count($server_players);
						$server_details['players_connecting'] -= $server_details['hltvcount'];

						// we have some info from the server (no rcon yet)
						$xmlBody .= "<map>".$server_details['map']."</map>";
						$xmlBody .= "<serverName>".htmlentities($server_details['hostname'],ENT_COMPAT,"UTF-8")."</serverName>";
						$xmlBody .= "<maxplayers>".$server_details['maxplayers']."</maxplayers>";
						$xmlBody .= "<players>".$server_details['players_real']."</players>";
						$xmlBody .= "<secure>".$server_details['secure']."</secure>";

						if ($server_details['botcount'] > 0) {
							$xmlBody .= "<bots>".$server_details['numbots']."</bots>";
						}

						# Get challenge
						$query_challenge = Source_A2S_GetChallenge($server_ip, $server_port);

						# Get packets with challenge number
						$server_rules = Source_A2S_Rules($server_ip, $server_port, $query_challenge);
						$server_players = Source_A2S_Player($server_ip, $server_port, $query_challenge);

						$server_details = Format_Info_Array($server_details);

						// the nextmap
						if (isset($server_rules['cm_nextmap'])) {
							$server_details['nextmap'] = $server_rules['cm_nextmap'];
						}
						elseif (isset($server_rules['amx_nextmap'])) {
							$server_details['nextmap'] = $server_rules['amx_nextmap'];
						}
						elseif (isset($server_rules['mani_nextmap'])) {
							$server_details['nextmap'] = $server_rules['mani_nextmap'];
						}
						if(isset($server_details['nextmap']) && $server_details['nextmap'] != "") {
							$xmlBody .= "<nextmap>".$server_details['nextmap']."</nextmap>";
						}

						# Some unfortunate games like CS don't usually give the map timeleft
						# I wonder if some plugin can yet again provide a use here...
						# Generally the plugin version is more reliable so that is the highest priority to use
						if (isset($server_rules['amx_timeleft'])) {
							$server_details['timeleft'] = $server_rules['amx_timeleft'];
						}
						elseif (isset($server_rules['cm_timeleft'])) {
							$server_details['timeleft'] = $server_rules['cm_timeleft'];
						}
						elseif (isset($server_rules['mp_timeleft'])) {
							$server_details['timeleft'] = sprintf('%02u:%02u', ($server_rules['mp_timeleft'] / 60), ($server_rules['mp_timeleft'] % 60));
						}
						elseif (isset($server_rules['mani_timeleft'])) {
							$server_details['timeleft'] = $server_rules['mani_timeleft'];
						}
						if (isset($server_details['timeleft'])) {
							$xmlBody .= "<timeleft>".$server_details['timeleft']."</timeleft>";
						}

						// frags left
						if ($server_rules['mp_fraglimit'] > 0) {
							$xmlBody .= "<fragsmax>".$server_rules['mp_fraglimit']."</fragsmax>";
							$xmlBody .= "<fragsleft>".$server_rules['mp_fragsleft']."</fragsleft>";
						}
					}
					else {
						$xmlBody .= "<message>No info available</message>";
					}

					$xmlBody .= "</additional>";

					// end of xml body
					$xmlBody .= "</server>";
				}
				else {
					// we have no such server
					$xmlBody = "<message>No such server.</message>";
				}
			}
			else {
				// we have no server id
				$xmlBody = "<message>No server ID given.</message>";
			}
	}
}

// prepare the xml data
$xmlReturn = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$xmlReturn .= '<root>';
$xmlReturn .= $xmlBody;
$xmlReturn .= '</root>'."\n";

// return the xml data
header("Pragma: ");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: ");
header('Content-type: text/xml; charset=UTF-8');
echo $xmlReturn;
?>
