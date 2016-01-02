<?php
/**
 * the main file. Handles all the requests for the interface
 * @package HLStats
 * @author Johannes 'Banana' Keßler
 */

/**
 * Original development:
 *
 * + HLStats - Real-time player and clan rankings and statistics for Half-Life
 * + http://sourceforge.net/projects/hlstats/
 * + Copyright (C) 2001  Simon Garner
 *
 *
 * Additional development:
 *
 * + UA HLStats Team
 * + http://www.unitedadmins.com
 * + 2004 - 2007
 *
 *
 *
 * Current development:
 *
 * + Johannes 'Banana' Keßler
 * + http://hlstats.sourceforge.net
 * + 2007 - 2012
 *
 * This program is free software is licensed under the
 * COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
 *
 * You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
 * along with this program; if not, visit http://hlstats-community.org/License.html
 *
 */

/**
 * Check PHP configuration
 */
if (version_compare(phpversion(), "5.2.0", "<")) {
	die("HLStats requires PHP version 5.2.0 or newer (you are running PHP version " . phpversion() . ").");
}

date_default_timezone_set('Europe/Berlin');

/**
 * if you have problems with your installation
 * activate this parameter by setting it to true
 */
define('SHOW_DEBUG',true);

// do not display errors in live version
if(SHOW_DEBUG === true) {
	error_reporting(-1);
	ini_set('display_errors',true);
}
else {
	error_reporting(-1);
	ini_set('display_errors',false);
}

/**
 * load the config
 */
require('hlstatsinc/hlstats.conf.php');

/**
 * load the global functions
 */
require("hlstatsinc/functions.inc.php");


////
//// Initialisation
////

/**
 * the release version
 */
define("VERSION", "1.65");

$DB = new mysqli(DB_ADDR,DB_USER,DB_PASS,DB_NAME);
if($DB->connect_errno) {
	var_dump($DB->connect_error);
	die('Could not connect to the MySQL Server. Check your configuration.');
}
$DB->query("SET NAMES utf8");
$DB->query("SET collation_connection = 'utf8_unicode_ci'");
$DB->set_charset("utf8");
/*
$db_con = mysql_connect(DB_ADDR,DB_USER,DB_PASS) OR die('Could not connect to the MySQL Server. Check your configuration.');
$db_sel = mysql_select_db(DB_NAME,$db_con) OR die('Could not select database. Check your configuration.');
mysql_query("SET NAMES utf8");
mysql_query("SET collation_connection = 'utf8_unicode_ci'");
*/

/**
 * load the options
 */
$g_options = array();
$g_options = getOptions();

if(empty($g_options)) {
	error('Failed to load options.');
}

/**
 * lang change via cookies
 */
$cl = $g_options['LANGUAGE'];
if(isset($_POST['submit-change-lang'])) {
	$check = validateInput($_POST['hls_lang_selection'],'nospace');
	if($check === true && !isset($_POST['hls_lang_selection'][2])) {
		// ok we can assume that we have a valid post value
		// we have a lang change
		// set the cookie and reload the page
		setcookie("hls_language",$_POST['hls_lang_selection'],time()+600,dirname($_SERVER["SCRIPT_NAME"]).'/','',false,true);
		header('Location: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
	}
}
elseif(isset($_COOKIE['hls_language']) && !empty($_COOKIE['hls_language'])) {
	$check = validateInput($_COOKIE['hls_language'],'nospace');
	if($check === true && !isset($_COOKIE['hls_language'][2])) {
		// ok we can assume that we have a valid cookie
		$cl = $_COOKIE['hls_language'];
	}
}
if($cl !== 'en') { // use standard language
	$langFile = getcwd().'/lang/'.$cl.'.ini.php';
	if(!file_exists($langFile)) {
		die('Language file could not be loaded. Please check your LANGUAGE setting.');
	}
	$lData = parse_custom_lang_file($langFile);
	if(empty($lData)) {
		die('Language file could not be parsed. Please check your LANGUAGE setting.');
	}
}

// set utf-8 header
// we have to save all the stuff with utf-8 to make it work !!
header("Content-type: text/html; charset=UTF-8");

////
//// Main
////
$modes = array(
	"players",
	"clans",
	"weapons",
	"maps",
	"actions",
	"claninfo",
	"playerinfo",
	"weaponinfo",
	"mapinfo",
	"actioninfo",
	"playerhistory",
	"search",
	"admin",
	"help",
	"livestats",
	"playerchathistory",
	"teams",
	"roles",
	"awards",
	"country",
	"countryInfo",
	"playerstimeline"
);

$mode = '';
if(!empty($_GET["mode"])) {
	if(in_array($_GET["mode"], $modes) && validateInput($_GET['mode'],'nospace') === true ) {
		$mode = $_GET['mode'];
	}
}

// decide if we show the games or the game file
$queryAllGames = $DB->query("SELECT code, name FROM `".DB_PREFIX."_Games`
								WHERE hidden='0' ORDER BY name");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
$num_games = $queryAllGames->num_rows;

$game = '';
if(isset($_GET['game'])) {
	$check = validateInput($_GET['game'],'nospace');
	if($check === true) {
		$game = $_GET['game'];

		$query = $DB->query("SELECT name FROM `".DB_PREFIX."_Games`
								WHERE code = '".$DB->real_escape_string($game)."'
								AND `hidden` = '0'");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		if($query->num_rows < 1) {
			error("No such game '$game'.");
		}
		else {
			$result = $query->fetch_assoc();
			$gamename = $result['name'];
			if(empty($mode)) $mode = 'game';
		}
	}
}
else {
	if ($num_games == 1) {
		$result = $queryAllGames->fetch_assoc();
		if(!empty($num_games)) {
			$game = $result['code'];
			$gamename = $result['name'];
			if(empty($mode)) $mode = 'game';
		}
		else {
			error("No such game.");
		}
	}
	else {
		if(empty($mode)) $mode = 'games';
	}
}

/**
 * include the requested page
 * the $mode is checked above
 */
if(!empty($mode)) {
	include("hlstatsinc/".$mode.".inc.php");
}

/**
 * include the global footer
 */
include("hlstatsinc/footer.inc.php");
$DB->close();
?>
