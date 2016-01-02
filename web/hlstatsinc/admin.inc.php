<?php
/**
 * main admin file
 * @package HLStats
 * @author Johannes 'Banana' Keßler
 * @copyright Johannes 'Banana' Keßler
 */

/**
 *
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


$selTask = false;
$selGame = false;

if(!empty($_GET["task"])) {
	if(validateInput($_GET["task"],'nospace') === true) {
		$selTask = $_GET["task"];
	}
}

session_set_cookie_params(43200); // 8 hours
session_name("hlstats-session");
session_start();
session_regenerate_id(true);

$auth = false;
require('class/admin.class.php');
$adminObj = new Admin();
$auth = $adminObj->getAuthStatus();

// process the logout
if(!empty($_GET['logout'])) {
	if(validateInput($_GET['logout'],'digit') === true && $_GET['logout'] == "1") {
		$adminObj->doLogout();
		header('Location: index.php');
	}
}

if($auth === true) {
	if(!empty($selTask)) {
		if(file_exists(getcwd().'/hlstatsinc/admintasks/'.$selTask.'.inc.php')) {
			require('hlstatsinc/admintasks/'.$selTask.'.inc.php');
		}
		else {
			require('hlstatsinc/admintasks/overview.php');
		}
	}
	else { // show overview
		require('hlstatsinc/admintasks/overview.php');
	}
}
else {
	require('hlstatsinc/admintasks/login.php');
}
?>
