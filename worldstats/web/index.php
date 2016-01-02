<?php

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
 * + 2007 - 2013
 *
 * This program is free software is licensed under the
 * COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
 *
 * You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
 * along with this program; if not, visit http://hlstats-community.org/License.html
 *
 */

if (version_compare(phpversion(), "5.2.0", "<")) {
	die("HLStats requires PHP version 5.2.0 or newer (you are running PHP version " . phpversion() . ").");
}

date_default_timezone_set('Europe/Berlin');

define('SHOW_DEBUG',true);
if(SHOW_DEBUG === true) {
	error_reporting(-1);
	ini_set('display_errors',true);
}
else {
	error_reporting(-1);
	ini_set('display_errors',false);
}

require('includes/conf.inc.php');
require("includes/functions.inc.php");


$DB = new mysqli(DB_ADDR,DB_USER,DB_PASS,DB_NAME);
if($DB->connect_errno) {
	var_dump($DB->connect_error);
	die('Could not connect to the MySQL Server. Check your configuration.');
}
$DB->query("SET NAMES utf8");
$DB->query("SET collation_connection = 'utf8_unicode_ci'");
$DB->set_charset("utf8");

$_pages = array(
	"players",
	"sites",
	"clans",
	"weapons",
	"games"
);

$_page = 'home';
if(!empty($_GET["p"])) {
	if(in_array($_GET["p"], $_pages) && validateInput($_GET['p'],'nospace') === true ) {
		$_page = $_GET['p'];
	}
}
if(empty($_page)) $_page = 'home';

header("Content-type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html>
<head>
	<title>HLStats WorldStats</title>
	<meta charset="UTF-8">
	<meta name="description" content="HLStats WorldStats" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
	<script type="text/javascript" src="js/prettify.js"></script>
	<script type="text/javascript" src="js/kickstart.js"></script>
	<link rel="stylesheet" type="text/css" href="css/kickstart.css" media="all" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" title="Stylesheet" charset="utf-8" />
	<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
</head>
<body>
	<a id="top-of-page"></a>

	<div id="wrap" class="clearfix">
		<h1>HLStats - WorldStats</h1>
		<ul class="menu">
			<li <?php if($_page == "home") echo 'class="current"' ?>><a href="index.php">Home</a></li>
			<li <?php if($_page == "sites") echo 'class="current"' ?>><a href="index.php?p=sites">Sites</a></li>
			<li <?php if($_page == "players") echo 'class="current"' ?>><a href="index.php?p=players">Players</a>
			<li <?php if($_page == "clans") echo 'class="current"' ?>><a href="index.php?p=clans">Clans</a></li>
			<li <?php if($_page == "weapons") echo 'class="current"' ?>><a href="index.php?p=weapons">Weapons</a></li>
			<li <?php if($_page == "games") echo 'class="current"' ?>><a href="index.php?p=games">Games</a></li>
		</ul>
		<div class="col_12">
			<?php
			include("includes/".$_page.".inc.php");
			?>
		</div>
	</div>
</body>
</html>

<?php
$DB->close();
?>
