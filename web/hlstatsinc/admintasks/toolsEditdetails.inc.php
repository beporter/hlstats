<?php
/**
 * admin edit player or clan details
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

$return = false;
$editMode = false;
$playerObj = false;
$clanData = false;

require('class/player.class.php');

if(!empty($_GET["playerId"])) {
	if(validateInput($_GET["playerId"],'digit') === true) {
		$playerObj = new Player($_GET["playerId"],false);
		$editMode = 'player';
	}
}

if(!empty($_GET["clanId"])) {
	if(validateInput($_GET["clanId"],'digit') === true) {
		$editMode = 'clan';

		// now get the clan details
		// since we do not have a clan class we make it this way
		$query = $DB->query("SELECT
				c.tag,
				c.name,
				c.homepage,
				c.steamGroup
			FROM ".DB_PREFIX."_Clans AS c
			WHERE c.clanId=".$DB->real_escape_string($_GET["clanId"])."");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);

		if ($query->num_rows > 0) {
			$clanData = $query->fetch_assoc();
		}
		$query->free();
	}
}

// process the edit of a player
if(isset($_POST['submit']['editPlayer']) && !empty($playerObj)) {

	// the checkboxes
	if(!isset($_POST['details']['deletefromclan'])) {
		$_POST['details']['deletefromclan'] = "0";
	}
	if(!isset($_POST['details']['resetstats'])) {
		$_POST['details']['resetstats'] = "0";
	}

	$check = $playerObj->checkFields($_POST['details']);
	if($check === true) {
		$do = $playerObj->updatePlayerProfile();
		if($do === true) {
			header('Location: index.php?mode=admin&task=toolsEditdetails&playerId='.$_GET["playerId"]);
		}
		else {
			$return['msg'] = l('Could not save data');
			$return['status'] = "1";
		}
	}
	else {
		$return['msg'] = l('Invalid Input');
		$return['status'] = "1";
	}
}

// process the edit of a clan
if(isset($_POST['submit']['editClan']) && !empty($clanData)) {
	if(!empty($_POST['details'])) {
		$query = $DB->query("UPDATE `".DB_PREFIX."_Clans`
						SET `name` = '".$DB->real_escape_string($_POST['details']['name'])."',
							`homepage` = '".$DB->real_escape_string($_POST['details']['homepage'])."',
							`steamGroup` = '".$DB->real_escape_string($_POST['details']['steamGroup'])."'
					WHERE `clanId` = '".$DB->real_escape_string($_GET["clanId"])."'");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		if($query !== false) {
			header('Location: index.php?mode=admin&task=toolsEditdetails&clanId='.$_GET["clanId"]);
		}
		else {
			$return['msg'] = l('Could not save data');
			$return['status'] = "1";
		}
	}
}

// process the search
if(isset($_POST['submit']['searchForId'])) {
	$searchFor = trim($_POST['search']['ID']);
	$searchWhere = trim($_POST['search']['what']);
	$check = validateInput($searchFor,'digit');
	$check1 = validateInput($searchWhere,'nospace');
	if($check === true && $check1 === true) {
		// search for given ID
		if($searchWhere === "player") {
			$query = $DB->query("SELECT `playerId`
									FROM `".DB_PREFIX."_Players`
									WHERE `playerId` = '".$DB->real_escape_string($searchFor)."'");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			if($query->num_rows > 0) {
				$result = $query->fetch_assoc();
				header('Location: index.php?mode=admin&task=toolsEditdetails&playerId='.$result['playerId']);
			}
			else {
				$return['msg'] = l('Nothing found');
				$return['status'] = "1";
			}
		}
		elseif($searchWhere === "clan") {
			$query = $DB->query("SELECT `clanId`
									FROM `".DB_PREFIX."_Clans`
									WHERE `clanId` = '".$DB->real_escape_string($searchFor)."'");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			if($query->num_rows > 0) {
				$result = $query->fetch_assoc();
				header('Location: index.php?mode=admin&task=toolsEditdetails&clanId='.$result['clanId']);
			}
			else {
				$return['msg'] = l('Nothing found');
				$return['status'] = "1";
			}
		}
		else {
			$return['msg'] = l('Invalid Input');
			$return['status'] = "1";
		}
	}
	else {
		$return['msg'] = l('Invalid Input');
		$return['status'] = "1";
	}
}

pageHeader(array(l("Admin"),l('Edit Details')), array(l("Admin")=>"index.php?mode=admin",l('Edit Details')=>''));
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
	<h1><?php echo l('Edit Player or Clan Details'); ?></h1>
	<?php
		if(!empty($return)) {
			if($return['status'] === "1") {
				echo '<div class="error">',$return['msg'],'</div>';
			}
			elseif($return['status'] === "2") {
				echo '<div class="success">',$return['msg'],'</div>';
			}
		}
		if($editMode === "player") {
		?>
		<h2><?php echo l('Player'),': ',$playerObj->getParam('name'); ?></h2>
		<form method="post" action="">
			<div style="float: left; margin-right: 20px;">
				<label><?php echo l('Real Name'); ?> :</label>
				<input type="text" name="details[fullName]" value="<?php echo $playerObj->getParam('fullName'); ?>" />
				<label><?php echo l('E-mail Address'); ?> :</label>
				<input type="text" name="details[email]" value="<?php echo $playerObj->getParam('email'); ?>" />
				<label><?php echo l('Homepage'); ?> :</label>
				<input type="text" name="details[homepage]" value="<?php echo $playerObj->getParam('homepage'); ?>" />
				<label><?php echo l('ICQ Number'); ?> :</label>
				<input type="text" name="details[icq]" value="<?php echo $playerObj->getParam('icq'); ?>" />
			</div>
			<div style="float: left">
				<label><?php echo l('MySpace'); ?> :</label>
				<input type="text" name="details[myspace]" value="<?php echo $playerObj->getParam('myspace'); ?>" />
				<label><?php echo l('Facebook'); ?> :</label>
				<input type="text" name="details[facebook]" value="<?php echo $playerObj->getParam('facebook'); ?>" />
				<label><?php echo l('Jabber'); ?> :</label>
				<input type="text" name="details[jabber]" value="<?php echo $playerObj->getParam('jabber'); ?>" />
				<label><?php echo l('Steam Profile'); ?> :</label>
				<input type="text" name="details[steamprofile]" value="<?php echo $playerObj->getParam('steamprofile'); ?>" />
			</div>
			<br style="clear: both" /><br />
			<b><?php echo l('Hide Ranking'); ?> :</b>
			<select name="details[hideranking]">
				<option value="1" <?php if($playerObj->getParam('hideranking') === "1") echo 'selected="1"';?>><?php echo l('Yes'); ?></option>
				<option value="0" <?php if($playerObj->getParam('hideranking') === "0") echo 'selected="1"';?>><?php echo l('No'); ?></option>
			</select><br />
			<br />
			<b><?php echo l('Delete From Clan'); ?> :</b>
			<input type="checkbox" name="details[deletefromclan]" value="1" /><br />
			<br />
			<b><?php echo l('Reset player stats'); ?> :</b>
			<input type="checkbox" name="details[resetstats]" value="1" />
			<p>
				<button type="submit" title=" <?php echo l('Apply'); ?>" name="submit[editPlayer]">
					<?php echo l('Apply'); ?>
				</button>
			</p>
		</form>
		<?php
		} elseif($editMode === "clan") { ?>
		<h2><?php echo l('Clan'),': ',$clanData['name']; ?></h2>
		<form method="post" action="">

			<label><?php echo l('Clan Name'); ?> :</label>
			<input type="text" name="details[name]" value="<?php echo $clanData['name']; ?>" />
			<label><?php echo l('Homepage'); ?> :</label>
			<input type="text" name="details[homepage]" value="<?php echo $clanData['homepage']; ?>" />
			<label><?php echo l('Steam group URL'); ?> :</label>
			<input type="text" name="details[steamGroup]" value="<?php echo $clanData['steamGroup']; ?>" />

			<p>
				<button type="submit" title=" <?php echo l('Apply'); ?>" name="submit[editClan]">
					<?php echo l('Apply'); ?>
				</button>
			</p>
		</form>
		<?php
		}
	?>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<h2><?php echo l('Enter player or clan ID'); ?></h2>
	<form method="post" action="">
		<?php echo l('You can enter a player or clan ID number directly, or you can search for a player or clan'); ?>.<br />
		<br />
		<?php echo l('Player'); ?> <input type="radio" name="search[what]" value="player" checked="1" />
		<?php echo l('Clan'); ?> <input type="radio" name="search[what]" value="clan" /><br />
		<?php echo l('ID'); ?>: <input type="text" name="search[ID]" value="" />
		<button type="submit" title=" <?php echo l('Edit'); ?>" name="submit[searchForId]">
			<?php echo l('Edit'); ?>
		</button>
	</form>
	</div>
</div>
