<?php
/**
 * reset the stats for a game
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

$gc = false;
$check = false;
$return = false;
$stop = false;

// get the game, without it we can not do anyting
if(isset($_GET['gc'])) {
	$gc = trim($_GET['gc']);
	$check = validateInput($gc,'nospace');
	if($check === true) {
		// load the game info
		$query = $DB->query("SELECT name
							FROM `".DB_PREFIX."_Games`
							WHERE code = '".$DB->real_escape_string($gc)."'");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		if($query->num_rows > 0) {
			$result = $query->fetch_assoc();
			$gName = $result['name'];
		}
		$query->free();
	}
}

// do we have a valid gc code?
if(empty($gc) || empty($check)) {
	exit('No game code given');
}

// get the servers for this game
$serversArr = array();
$serversArrCustom = array();
$query = $DB->query("SELECT serverId,name FROM `".DB_PREFIX."_Servers`
					WHERE game = '".$DB->real_escape_string($gc)."'");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
while($result = $query->fetch_assoc()) {
	$serversArr[] = $result['serverId'];
	$serversArrCustom[$result['serverId']] = $result['name'];
}

// process the reset for this game
if (isset($_POST['sub']['reset'])) {

	if(isset($_POST['select']['server']) && !empty($_POST['select']['server'])) {
		$serversArr = array();
		$serversArr[] = (int)$_POST['select']['server'];
	}

	if(empty($serversArr)) {
		$return = l("Error: No servers found for this game. Nothing to reset.");
		$stop = true;
	}
	$serversArrString = implode(",",$serversArr);

	$queryStr = '';
	if($stop === false ) {
		# get the event tables
		$dbtables = array();

		$query = $DB->query("SHOW TABLES LIKE '".DB_PREFIX."_Events_%'");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		if ($query->num_rows < 1) {
			die("Fatal error: No events tables found with query:<p><pre>$query</pre><p>
				There may be something wrong with your HLStats database or your version of MySQL.");
		}

		while (list($table) = $query->fetch_array()) {
			$dbtables[] = $table;
		}

		foreach ($dbtables as $dbt) {
			# first get all the player IDs
			if($dbt == DB_PREFIX.'_Events_Admin' || $dbt == DB_PREFIX.'_Events_Rcon') {
			}
			elseif($dbt == DB_PREFIX.'_Events_Frags' || $dbt == DB_PREFIX.'_Events_Teamkills') {
			}
			else {
				if(empty($queryStr)) {
					$queryStr .= "SELECT `playerId` FROM `".$dbt."`
						WHERE `serverId` IN (".$serversArrString.")";
				}
				else {
					$queryStr .= " UNION
						SELECT `playerId` FROM `".$dbt."`
						WHERE `serverId` IN (".$serversArrString.")";
				}
			}
		}
	}

	if(!empty($queryStr)) {
		$query = $DB->query($queryStr);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$players[] = $result['playerId'];
			}
			$playerIdString = implode(",",$players);
		}
		else {
			$stop = true;
			$return = l("Error: No players found for this game. Nothing to reset.");
		}
	}

	# reset only if we have players and servers
	if($stop === false) {

		array_push($dbtables,
			DB_PREFIX."_PlayerNames",
			DB_PREFIX."_PlayerUniqueIds",
			DB_PREFIX."_Players"
		);

		foreach ($dbtables as $dbt) {
			if($dbt == DB_PREFIX."_PlayerNames" || $dbt == DB_PREFIX."_PlayerUniqueIds" || $dbt == DB_PREFIX."_Players") {
				if ($DB->query("DELETE FROM `".$dbt."`
									WHERE playerId IN (".$playerIdString.")")) {
					if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
					$return .= $dbt." OK<br />";
				}
				else {
					$return .= "Error for Table:".$dbt."<br />";
				}
			}
			else {
				if ($DB->query("DELETE FROM `".$dbt."`
									WHERE serverId IN (".$serversArrString.")")) {
					if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
					$return .= $dbt." OK<br />";
				}
				else {
					$return .= "Error for Table:".$dbt."<br />";
				}
			}
		}

		// now the tables which we can delete by gamecode
		$dbtablesGamecode [] = DB_PREFIX."_Clans";

		foreach ($dbtablesGamecode as $dbtGame) {
			if ($DB->query("DELETE FROM `".$dbtGame."`
								WHERE game = '".$DB->real_escape_string($gc)."'")) {
				if(SHOW_DEBUG && $DB->error) var_dump($DB->error);

				$return .= $dbtGame." OK<br />";
			}
			else {
				$return .= "Error for Table:".$dbtGame."<br />";
			}
		}

		$return .= "Clearing awards ... <br />";
		if ($DB->query("UPDATE `".DB_PREFIX."_Awards` SET d_winner_id=NULL, d_winner_count=NULL
					WHERE game = '".$DB->real_escape_string($gc)."'")) {
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			$DB->query("DELETE FROM `".DB_PREFIX."_Awards_History`
					WHERE game = '".$DB->real_escape_string($gc)."'");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			$return .= "Awards OK<br />";
		}
		else {
			$return .= "Error for Table: Awards<br />";
		}
	}
}

pageHeader(array(l("Admin"),l('Servers')), array(l("Admin")=>"index.php?mode=admin",l('Reset Statistics')=>''));
?>
<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="index.php?mode=admin&amp;task=gameoverview&amp;code=<?php echo $gc; ?>"><?php echo l('Back to game overview'); ?></a>
			</li>
			<li>
				<a href="index.php?mode=admin"><?php echo l('Back to admin overview'); ?></a>
			</li>
		</ul>
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo l('Reset Statistics for'); ?>: <?php echo $gName; ?></h1>
	<?php echo l('Are you sure you want to reset all statistics for game'); ?> <b><?php echo $gName; ?></b> ? <br />
	<br />
	<?php echo l('All players, clans and events will be deleted from the database'); ?>.<br />
	<?php echo l('(All other admin settings will be retained)'); ?><br />
	<br />
	<b><?php echo l('Note'); ?></b> <?php echo l('You should kill'); ?> <b>hlstats.pl</b>
	<?php echo l('before resetting the stats. You can restart it after they are reset'); ?>.<br />
	<br />
	<?php
	if(!empty($return)) {
		echo '<p>',$return,'</p>';
	}
	?>
	<form method="post" action="">
		<p><?php echo l('Reset for all servers or only specified one ?'); ?></p>
		<p>
			<select name="select[server]">
				<option value=""><?php echo l('All'); ?></option>
			<?php
			foreach($serversArrCustom as $key=>$value) {
				echo '<option value="'.$key.'">'.$value.'</option>';
			}
			?>
			</select>
		</p>
		<p align="center">
		<button type="submit" name="sub[reset]" title="<?php echo l('Reset'); ?>">
			<?php echo l('Reset'); ?>
		</button>
		</p>
	</form>
	</div>
</div>
