<?php


/**
 * validate if given string is correct
 *
 * @param string $string
 * @param string $mode
 */
function validateInput($string,$mode) {
	$ret = false;
	if($string != "" && !empty($mode)) {
		switch ($mode) {
			case 'nospace':
				$pattern = '/[^\p{L}\p{N}\p{P}]/u';
				$value = preg_replace($pattern, '', $string);
				if($string === $value) {
					 $ret = true;
				}
			break;
			case 'digit':
				$pattern = '/[^\p{N}]/u';
				$value = preg_replace($pattern, '', $string);
				if($string === $value) {
					 $ret = true;
				}
			break;

			case 'text':
				$pattern = '/[^\p{L}\p{N}\p{P}\s]/u';
				$value = preg_replace($pattern, '', $string);
				if($string === $value) {
					 $ret = true;
				}
			break;
		}
	}
	return $ret;
}

/**
 * make a save player name and keeping the special tags and stuff
 *
 * @param string $name The player name as string
 * @return string The escaped player name
 */
function makeSavePlayerName($name) {
	return htmlentities($name, ENT_QUOTES, "UTF-8");
}

/**
 * create the steam profile url
 * e.g. STEAM_0:0:123456
 * Steam_community_number = (Last_part_of_steam_id * 2) + 76561197960265728 + Second_to_last_part_of_steam_id
 * this needs bcmath support in PHP
 * http://www.php.net/manual/en/book.bc.php
 * @param string $steamId
 * @return string $ret
 */
function getSteamProfileUrl($steamId) {
	$ret = $steamId;

	$s = calculateSteamProfileID($steamId);

	$ret = '<a href="http://steamcommunity.com/profiles/'.$s.'" target="_blank">'.$steamId.'</a>';

	return $ret;
}

function calculateSteamProfileID($steamId) {
	$ret = false;

	if(!empty($steamId) && strstr($steamId,'STEAM_') && function_exists('bcadd')) {
		$t = explode(':',$steamId);
		$s = bcadd('76561197960265728',$t[2]*2);
		$s = bcadd($s,$t[1]);

		if(strstr($s,'.')) {
			$st = explode('.',$s);
			$ret = $st[0];
		}
		else {
			$ret = $s;
		}
	}

	return $ret;
}

/**
 * string makeQueryString
 *
 * @param array $params The array with the params to be added (key=>value)
 * @param array $notkeys The keys to be removed
 *
 * @return string $querystring The final query-string for an url
 */
function makeQueryString($params, $notkeys = array()) {
	$querystring = "";

	if (!is_array($notkeys)) $notkeys = array();

	foreach ($_GET as $k=>$v) {
		if(!key_exists($k,$params) && !in_array($k, $notkeys)) {
		//if ($k && $k != $key && !in_array($k, $notkeys)) {
			$querystring .= urlencode($k) . "=" . urlencode($v) . "&amp;";
		}
	}

	foreach($params as $key => $value) {
		$querystring .= urlencode($key) . "=" . urlencode($value)."&amp;";
	}
	$querystring = trim($querystring,"&");

	return $querystring;
}
