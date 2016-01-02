<?php

/**
 * admin worldstats manage file
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
 * + 2007 - 2013
 * +
 *
 * This program is free software is licensed under the
 * COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
 *
 * You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
 * along with this program; if not, visit http://hlstats-community.org/License.html
 *
 */

$_wsRegURL = "http://localhost/code/HLStats/worldstats/regapi.php";


// get the games from the db
$query = $DB->query("SELECT code,name
						FROM `".DB_PREFIX."_Games`
						ORDER BY `name`");
$gamesArr = array();
while ($result = $query->fetch_assoc()) {
	$gamesArr[$result['code']] = $result['name'];
}
$gamesArrToReg = $gamesArr;

$error = false;
$success = false;
$requestingSite = "http://".str_replace('index.php', 'xml.php', $_SERVER["SERVER_NAME"].'/'.str_replace($_SERVER["DOCUMENT_ROOT"], '', $_SERVER["SCRIPT_FILENAME"]));
$requestingSiteHash = md5($requestingSite);
$alreadyRegGames = false;

if(isset($_POST['sub']['doRegister'])) {
	if(isset($_POST['reg']['register']) && $_POST['reg']['register'] === "1") {

		# build the query sting

		$queryStr = 'id='.$requestingSiteHash;

		if(!empty($_POST['reg']['game']) && is_array($_POST['reg']['game'])) {

			$payload['games'] = $_POST['reg']['game'];
			$payload['requestURL'] = $requestingSite;
			$payload['siteURL'] = str_replace('index.php', 'xml.php',$requestingSite);
			$payload['siteName'] = $g_options['sitename'];

			$pParams['payload'] = json_encode($payload);

			# we want to register.
			$ch = curl_init();

			#curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_URL,$_wsRegURL.'?'.$queryStr);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $pParams);

			$do = curl_exec($ch);
			$returnData = json_decode($do,true);

			if($returnData !== false && $returnData['status'] === true) {
				$success[] = 'Registration was successfull';
				header('Location: index.php?'.$_SERVER['QUERY_STRING']);
			}
			else {
				$error = 'Your request has failed. Please try again.';
				if(SHOW_DEBUG) {
					var_dump($do);
					var_dump(curl_error($ch));
				}
			}
		}
		else {
			$error = 'Your request has failed. Please try again.';
			if(SHOW_DEBUG) {
				var_dump($do);
				var_dump(curl_error($ch));
			}

		}

		curl_close($ch);
	}
}
else {
	# check the status of the available games

	if(!empty($gamesArr)) {
		$queryStr = 'id='.$requestingSiteHash;
		# add the games
		$queryGamesAdd = implode('__',array_keys($gamesArr));

		$queryStr .= "&games=".$queryGamesAdd;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$_wsRegURL.'?'.$queryStr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		$do = curl_exec($ch);
		$answerStr = $do;

		$answer = json_decode($answerStr,true);

		if($answer !== false && $answer['status'] === true) {
			$alreadyRegGames[] = array();


			if(isset($answer['payload'][$requestingSiteHash])) {
				$success[] = 'Connection to master server possible.';
				$_gameList = $answer['payload'][$requestingSiteHash];
				foreach($_gameList as $code => $status) {
					if($status === "yes") {
						unset($gamesArrToReg[$code]);
						$alreadyRegGames[] = $code;
					}
				}
			}
			else {
				$error = 'Something has gone wrong.';
				if(SHOW_DEBUG) {
					var_dump($answer);
					var_dump(curl_error($ch));
				}
			}
		}
		else {
			$error = 'Your request has failed. Please try again.';
			if(SHOW_DEBUG) {
				var_dump($do);
				var_dump(curl_error($ch));
			}
		}
		curl_close($ch);
	}
}

pageHeader(
	array(
		l("Admin"),
		l('WorldStats')
	),
	array(
		l("Admin")=>"index.php?mode=admin",
		l('WorldStats')=>'')
	);
?>

<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="<?php echo "index.php?mode=admin"; ?>"><?php echo l('Back to admin overview'); ?></a>
			</li>
		</ul>
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo l('Worldstats'); ?></h1>
	<div class="error">
		This is a beta version. Please make sure you know what you are doing.<br />
		Any bugs, questions or feedback is welcome. <a href="http://forum.hlstats-community.org" target="_blank">Use the forum to report.</a>
	</div>
	<p>
		To be a part of the <a href="http://www.hlstats-community.org/worldstats" target="_blank">HLStats WorldStats</a>
		you need to "register" your HLStats installation and activate the xml interface.
	</p>
	<?php
		if(!empty($error)) {
			echo '<div class="error"><p>',$error,'</p></div>';
		}
		elseif(!empty($success)) {
			echo '<div class="success"><p>',implode("<br />",$success),'</p></div>';
		}
	?>
	<p>
		This URL will be used to register your installation:<br />
		<b><?php echo $requestingSite; ?></b>
	</p>
	<p>
		<form method="post" action="">
			<p>Games which are <b>NOT</b> registered yet:</p>
			<?php
			if(!empty($gamesArrToReg)) {
				echo '<select name="reg[game][]" multiple="true" size="5">';
				foreach($gamesArrToReg as $k=>$v) {
					echo '<option value="',$k,'">',$v,'</option>';
				}
				echo '</select><br /><br />';
			}
			?>
			<br />
			Check the game(s) <b>above</b> and set the tick <b>below</b> to register the selected games to the WorldStats<br />
			<br />
			<input type="checkbox" name="reg[register]" value="1" />&nbsp;Register your installation and selected games<br />
			<br />
			<button type="submit" name="sub[doRegister]" title="Do it">Do it</button>
		</form>
	</p>
	</div>
</div>
