<?php
/**
 * search page
 * search for a player, clans in one or more games
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


pageHeader(
	array(l("Search")),
	array(l("Search")=>"")
);

$sr_query = false;
$sr_type = 'player';
$sr_game = false;

if(!empty($_GET["q"])) {
	$sr_query = sanitize($_GET["q"]);
	$sr_query = urldecode($sr_query);
}

if(!empty($_GET["st"])) {
	if(validateInput($_GET["st"],'nospace') === true) {
		$sr_type = $_GET["st"];
	}
}

if(!empty($_GET["game"])) {
	if(validateInput($_GET["game"],'nospace') === true) {
		$sr_game = $_GET["game"];
	}
}

$remoteSearch = false;
// check if we have asearch request via get
if(!empty($sr_query) && !empty($sr_type) && !empty($sr_game)) {
	$remoteSearch = true;
}

// get the game list
$gamesArr = array();
$query = $DB->query("SELECT code, name FROM `".DB_PREFIX."_Games`
						WHERE hidden='0' ORDER BY name");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
while ($result = $query->fetch_assoc()) {
	$gamesArr[$result['code']] = $result['name'];
}

$searchResults = false;
$queryStr = false;

if(isset($_POST['submit']['search']) || $remoteSearch === true) {

	if($remoteSearch === false) {
		$sr_query = trim($_POST['search']['input']);
		$sr_game = trim($_POST['search']['game']);
		$sr_type = trim($_POST['search']['area']);
	}

	if(!empty($sr_query)) {
		$andgame = "";
		if ($sr_game !== "---") {
			$andgame = "AND g.code = '".$DB->real_escape_string($sr_game)."'";
		}

		switch($sr_type) {
			case 'clan':
				$queryStr = "SELECT
						c.clanId,
						c.tag,
						c.name,
						c.game AS gamename
					FROM `".DB_PREFIX."_Clans` AS c
					LEFT JOIN `".DB_PREFIX."_Games` AS g
						ON g.code = c.game
					WHERE g.hidden = '0'
						AND
						(
							c.tag LIKE '%".$DB->real_escape_string($sr_query)."%'
							OR
							c.name LIKE '%".$DB->real_escape_string($sr_query)."%'
						)
						".$andgame."
					ORDER BY name";
			break;

			case 'ids':
				$queryStr = "SELECT pn.playerId,
						pn.name,
						g.name AS gamename
					FROM `".DB_PREFIX."_PlayerNames` AS pn
					LEFT JOIN `".DB_PREFIX."_Players` AS p
						ON p.playerId = pn.playerId
					LEFT JOIN `".DB_PREFIX."_PlayerUniqueIds` AS pu
						ON pu.playerId = pn.playerId
					LEFT JOIN `".DB_PREFIX."_Games` AS g
						ON g.code = p.game
					WHERE g.hidden = '0'
						AND pu.uniqueId LIKE '%".$DB->real_escape_string($sr_query)."%'
						".$andgame."
					ORDER BY name";
			break;

			case 'player':
			default:
				$queryStr = "SELECT pn.playerId,
						pn.name,
						g.name AS gamename
					FROM `".DB_PREFIX."_PlayerNames` AS pn
					LEFT JOIN `".DB_PREFIX."_Players` AS p
						ON p.playerId = pn.playerId
					LEFT JOIN `".DB_PREFIX."_Games` AS g
						ON g.code = p.game
					WHERE g.hidden = '0'
						AND pn.name LIKE '%".$DB->real_escape_string($sr_query)."%'
						".$andgame."
					ORDER BY name";
			break;
		}

		if(!empty($queryStr)) {
			$searchResults = array();
			$query = $DB->query($queryStr);
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			if($query->num_rows > 0) {
				while($result = $query->fetch_assoc()) {
					$searchResults[$result['gamename']][] = $result;
				}
			}
		}
	}
}


//@todo: search for uniq id
?>

<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="index.php"><?php echo l('Back to start page'); ?></a>
			</li>
		</ul>
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo l('Find a Player or Clan'); ?></h1>
	<form method="post" action="">
		<p>
			<?php echo l('You can search for a exact or a part of a player/clan name. The uniqe IDs are either IP or steam ID'); ?>
		</p>
		<b><?php echo l('Search For'); ?></b>:<br />
		<input type="text" name="search[input]" value="" /><br />
		<br />
		<b><?php echo l('In'); ?></b>:<br />
		<select name="search[area]">
			<option value="player" <?php if($sr_type == "player") echo 'selected="1"'; ?>><?php echo l('Player names'); ?></option>
			<option value="clan" <?php if($sr_type == "clan") echo 'selected="1"'; ?>><?php echo l('Clan names'); ?></option>
			<option value="ids" <?php if($sr_type == "ids") echo 'selected="1"'; ?>><?php echo l('Uniqe IDs'); ?></option>
		</select><br />
		<br />
		<b><?php echo l('Game'); ?></b>:<br />
		<select name="search[game]">
			<option value="---"><?php echo l('All'); ?></option>
			<?php
			foreach($gamesArr as $k=>$v) {
				$selected = '';
				if($sr_game == $k) $selected = 'selected="1"';
				echo '<option value="',$k,'" ',$selected,'>',$v,'</option>';
			}
			?>
		</select><br />
		<br />
		<button type="submit" name="submit[search]" title="<?php echo l('Find Now'); ?>">
			<?php echo l('Find Now'); ?>
		</button><br />
		<br />
	</form>
	<?php
		if(is_array($searchResults) && !empty($searchResults)) {
			echo '<ul>';
			foreach($searchResults as $gn=>$entry) {
				echo '<li><b>',$gn,'</b>';
				if(!empty($entry)) {
					echo '<ul>';
					foreach($entry as $e) {
						echo '<li><a href="index.php?mode=playerinfo&player=',$e['playerId'],'">',makeSavePlayerName($e['name']),'</a></li>';
					}
					echo '</ul>';
				}
				echo '</li>';
			}
			echo '</ul>';
		}
		elseif(is_array($searchResults)) {
			echo '<div style="text-align: center; color: red;"><b>',l('Nothing found'),'</b></div>';
		}
	?>
	</div>
</div>
