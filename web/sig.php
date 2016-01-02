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
require('hlstatsinc/hlstats.conf.php');

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

/**
 * path settings.
 * and sanitize of the get vars
 */
$picPath = dirname(__FILE__)."/signatures/";
if(isset($_GET['style']) && !empty($_GET['style'])) {
	$style = sanitize($_GET['style']);
}
else {
	$style = "black";
}

// check for playerId
if(isset($_GET['playerId']) != "" && validateInput($_GET['playerId'],'digit') === true) {
	$playerId = sanitize($_GET['playerId']);
	$playerId = (int)$playerId;
}
else {
	// no player given
	echo "No playerId given";
	exit();
}

/**
 * check if we are allowed to create a pic
 * if so check if we have jpeg support
 * otherwise return "not available" text
 */
if($g_options['allowSig'] == "1") {
	// check for gd support
	$gdInfo = gd_info();
	if($gdInfo['PNG Support'] && $gdInfo['GIF Read Support'] && $gdInfo['GIF Create Support']) {
		// we are allowed and have gif/png support

		// check if we have to create a new picture
		if(file_exists($picPath."create.stamp")) {
			$fh = fopen($picPath."create.stamp","r");
			$stamp = fread($fh, filesize($picPath."create.stamp"));
			$stamp = (int)$stamp;
			fclose($fh);
			// check the timestamp
			// // valid for a half/hour
			if($stamp < time()) {
				// unlink the file
				// I know this is a dirty hack, but who cares ;-)
				@unlink($picPath."preRender/".$playerId.".png");
				$fh = fopen($picPath."create.stamp","w+");
				fwrite($fh,time()+1800);
				fclose($fh);
			}
		}
		else {
			// we dont have a stamp file yet
			// // create one and continue
			$fh = fopen($picPath."create.stamp","w+");
			fwrite($fh,time()+1800);
			fclose($fh);
		}

		// check if we have already a picture.
		// // if so use this end exit
		if(file_exists($picPath."preRender/".$playerId.".png") && SHOW_DEBUG === false) {
			header("Pragma: ");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: ");
			header("Content-type: image/png");
			readfile($picPath."preRender/".$playerId.".png");
			exit();
		}

		// get the player data
		$query = $DB->query("SELECT * FROM `".DB_PREFIX."_Players`
							WHERE playerId = '".$DB->real_escape_string($playerId)."'");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		$playerData = $query->fetch_assoc();
		if($playerData === false) {
			// no player data !
			echo "No data found";
			exit();
		}
		// rank
		$query = $DB->query("SELECT skill, playerId
								FROM `".DB_PREFIX."_Players`
								WHERE game = '".$DB->real_escape_string($playerData['game'])."'
								ORDER BY skill DESC");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		$ranKnum = 1;
		while ($row = $query->fetch_assoc()) {
			$statsArr[$row['playerId']] = $ranKnum;
			$ranKnum++;
		}
		$playerRank = $statsArr[$playerId];
		$playerWholeRank = count($statsArr);
		$query->free();

		// server info
		$query = $DB->query("SELECT serverId FROM `".DB_PREFIX."_Events_Connects`
					WHERE playerId = '".$DB->real_escape_string($playerId)."'
					ORDER BY eventTime DESC
					LIMIT 1");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		$result = $query->fetch_assoc();
		$serverId = $result['serverId'];
		$query->free();
		if(empty($serverId)) exit('Incorrect player info. This does not work.');

		// now get the server info
		$query = $DB->query("SELECT address,port,name
					FROM `".DB_PREFIX."_Servers`
					WHERE serverId = ".$DB->real_escape_string($serverId));
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		$serverData = $query->fetch_assoc();
		$query->free();

		$font = $picPath.'svenings.ttf';
		switch ($style) {

			case 'black':
				// 400x100
				$imgH = imagecreatefrompng($picPath."black.png");
				imagealphablending($imgH, true);
				imagesavealpha($imgH, true);

				// colors
				$foreground = imagecolorallocate($imgH, 255, 255, 255);
				$background = imagecolorallocate($imgH, 10, 227, 236);
			break;

			case 'multi':
				// 400x100
				$imgH = imagecreatefrompng($picPath."multi.png");
				imagealphablending($imgH, true);
				imagesavealpha($imgH, true);

				// colors
				$foreground = imagecolorallocate($imgH, 255, 255, 255);
				$background = imagecolorallocate($imgH, 44, 87, 172);
			break;

			case 'red':
				// 400x100
				$imgH = imagecreatefrompng($picPath."red.png");
				imagealphablending($imgH, true);
				imagesavealpha($imgH, true);

				// colors
				$foreground = imagecolorallocate($imgH, 255, 255, 255);
				$background = imagecolorallocate($imgH, 79, 20, 21);
			break;

			case 'blue':
				// 400x100
				$imgH = imagecreatefrompng($picPath."blue.png");
				imagealphablending($imgH, true);
				imagesavealpha($imgH, true);

				// colors
				$foreground = imagecolorallocate($imgH, 255, 255, 255);
				$background = imagecolorallocate($imgH, 0, 0, 0);
			break;

			case 'css_nitro':
				// 400x100
				$imgH = imagecreatefrompng($picPath."css_by_nitrocium.png");
				imagealphablending($imgH, true);
				imagesavealpha($imgH, true);

				// colors
				$foreground = imagecolorallocate($imgH, 165, 42, 42);
				$background = imagecolorallocate($imgH, 255, 255, 255);
			break;

			case 'green':
			default:
				// 400x100
				$imgH = imagecreatefrompng($picPath."green.png");
				imagealphablending($imgH, true);
				imagesavealpha($imgH, true);

				// colors
				$foreground = imagecolorallocate($imgH, 255, 255, 255);
				$background = imagecolorallocate($imgH, 0, 0, 0);
		}

		// Player Name
		$text = $playerData['lastName'];
		imagettftext($imgH, 12, 0, 22, 33, $background, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));
		imagettftext($imgH, 12, 0, 20, 31, $foreground, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));

		// Rank
		$text = "Rank: ".$playerRank." / ".$playerWholeRank;
		imagettftext($imgH, 10, 0, 22, 50, $background, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));
		imagettftext($imgH, 10, 0, 20, 48, $foreground, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));

		// hlstats url
		$text = $g_options['siteurl'];
		// text info
		/*	0  	lower left corner, X position
			1 	lower left corner, Y position
			2 	lower right corner, X position
			3 	lower right corner, Y position
			4 	upper right corner, X position
			5 	upper right corner, Y position
			6 	upper left corner, X position
			7 	upper left corner, Y position
		*/
		// determine the position of the url and add some padding
		$textInfo = imagettfbbox(8,0,$font,$text);
		$textWitdh = $textInfo[2];
		$textPos = (400-$textWitdh)-10;
		imagettftext($imgH, 8, 0, $textPos+1, 13, $background, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));
		imagettftext($imgH, 8, 0, $textPos, 12, $foreground, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));

		// points
		$text = "HLStats points: ".$playerData['skill'];
		imagettftext($imgH, 9, 0, 22, 62, $background, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));
		imagettftext($imgH, 9, 0, 20, 60, $foreground, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));

		// kills
		$text = "Kills: ".$playerData['kills'];
		imagettftext($imgH, 9, 0, 22, 77, $background, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));
		imagettftext($imgH, 9, 0, 20, 75, $foreground, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));

		// server IP and port etc
		if(empty($serverData)) {
			$text = "Unknown Server\nIP: -";
		}
		else {
			$text = $serverData['name']."\nIP: ".$serverData['address']." ".$serverData['port'];
		}
		$textInfo = imagettfbbox(9,0,$font,$text);
		$textWitdh = $textInfo[2];
		$textPos = (400-$textWitdh)-10;
		imagettftext($imgH, 9, 0, $textPos+1, 41, $background, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));
		imagettftext($imgH, 9, 0, $textPos, 40, $foreground, $font, html_entity_decode($text,ENT_COMPAT,"UTF-8"));

		// display the image
		header("Pragma: ");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: ");
		header("Content-type: image/png");
		imagepng($imgH,$picPath."preRender/".$playerId.".png");
		imagepng($imgH);
		exit();
	}
	else {
		// no jpeg support
		// // we exit here.
		echo "No support to create a signature.";
		exit();
	}
}
else {
	// we are not allowed to create signatures
	// // we end here.
	echo "Signature creation disabled.";
	exit();
}
?>
