<?php
/**
 * games overview file
 * display an overview about all games
 * @package HLStats
 * @author Johannes 'Banana' Keßler
 * @copyright Johannes 'Banana' Keßler
 */

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

require('class/players.class.php');

pageHeader(array(l("Contents")), array(l("Contents")=>""));
?>
<div id="main-full">
<div class="content">
<?php
// should we hide the news ?
if(!$g_options['hideNews']) {
	$queryNews = $DB->query("SELECT id,`date`,`user`,`email`,`subject`,`message`
								FROM `".DB_PREFIX."_News`
								ORDER BY `date` DESC");
	if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
	if($queryNews->num_rows > 0) {
?>
	<script type="text/javascript">
	<!--
	function showNews(id) {
		if(document.getElementById("newsBox_" + id).style.display == "none") {
			document.getElementById("newsBox_" + id).style.display = "block";
		}
		else {
			document.getElementById("newsBox_" + id).style.display = "none";
		}

	}
	//-->
</script>
	<h1><?php echo l('News'); ?></h1>
	<?php
		$i = 0;
		while ($rowdata = $queryNews->fetch_assoc()) {
			if($i == 0) {
	?>
	<div class="newsBox" id="newsBox_<?php echo $i; ?>">
	<?php
			}
			else {
	?>
	<a href="javascript:showNews('<?php echo $i; ?>');"><?php echo htmlentities($rowdata['subject'],ENT_QUOTES, "UTF-8"); ?></a>
	<?php echo l('from'); ?> <?php echo $rowdata['date']; ?>
	<div class="newsBox" id="newsBox_<?php echo $i; ?>" style="display: none;">
	<?php
			}
	?>
		<p>
			<i><?php echo $rowdata['subject']; ?></i><br />
			<br />
			<?php echo nl2br($rowdata['message']); ?>
		</p>
		<p class="comments align-right clear"><?php echo l('written by'),' ',$rowdata['user'],' (',$rowdata['date'],')'; ?></p>
	</div>
	<?php
			$i++;
		}
	?>
	<?php
	}
}
?>

	<h1><?php echo l('Games'); ?></h1>
	<table border="0" cellspacing="0" cellpadding="2" width="100%">
		<tr>
			<th colspan="3"><?php echo l('Game'); ?></th>
			<th align="center"><?php echo l('Top Player'); ?></th>
			<th align="center"><?php echo l('Top Clan'); ?></th>
		</tr>
	<?php
		while ($gamedata = $queryAllGames->fetch_assoc()) {

			# get the top player
			$playersObj = new Players($gamedata['code']);
			$topplayer = $playersObj->topPlayer();

			$queryTopClan = $DB->query("SELECT
					c.clanId,
					c.name,
					AVG(p.skill) AS skill,
					COUNT(p.playerId) AS numplayers
				FROM `".DB_PREFIX."_Clans` AS c
				LEFT JOIN `".DB_PREFIX."_Players` AS p
					ON p.clan = c.clanId
				WHERE c.game = '".$DB->real_escape_string($gamedata['code'])."'
				GROUP BY c.clanId
				HAVING skill IS NOT NULL AND numplayers > 1
				ORDER BY skill DESC
				LIMIT 1
			");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);

			$topclan = false;
			if ($queryTopClan->num_rows === 1) {
				$topclan = $queryTopClan->fetch_assoc();
			}
	?>
		<tr>
			<td>
				<a href="<?php echo "index.php?game=".$gamedata['code']; ?>"><img src="hlstatsimg/game-<?php echo $gamedata['code']; ?>.gif" width="16" height="16" hspace="3" border="0" align="middle" alt="<?php echo $gamedata['code']; ?>">&nbsp;<?php echo $gamedata['name']; ?></a>
			</td>
			<td>
				<a href="<?php echo "index.php?mode=players&amp;game=".$gamedata['code']; ?>"><img src="hlstatsimg/player.gif" width="16" height="16" hspace="3" alt="<?php echo l('Player Rankings'); ?>" border="0" align="middle">&nbsp;<?php echo l('Players'); ?></a>
			</td>
			<td>
				<a href="<?php echo "index.php?mode=clans&amp;game=".$gamedata['code']; ?>"><img src="hlstatsimg/clan.gif" width="16" height="16" hspace="3" alt="<?php echo l('Clan Rankings'); ?>" border="0" align="middle">&nbsp;<?php echo l('Clans'); ?></a>
			</td>
			<td>
	<?php
		if ($topplayer !== false) {
			if($topplayer['isBot'] === "1") {
				echo '<img src="hlstatsimg/bot.png" width="16" height="16" hspace="3" alt="'.l('BOT').'" border="0" align="middle" />' ;
			}
			echo '<a href="index.php?mode=playerinfo&amp;player='.$topplayer['playerId'].'">'.makeSavePlayerName($topplayer['lastName']).'</a>';
		}
		else {
			echo '-';
		}
	?>
			</td>
			<td>
	<?php
		if ($topclan !== false) {
			echo '<a href="index.php?mode=claninfo&amp;clan='.$topclan['clanId'].'&amp;game='.$gamedata['code'].'">'.makeSavePlayerName($topclan['name']).'</a>';
		}
		else {
			echo '-';
		}
			?></td>
		</tr>
	<?php
		}
	?>
	</table>
</div>
<h1><?php echo l('General Statistics'); ?></h1>
<p>
<?php
	$query = $DB->query("SELECT COUNT(*) AS pc FROM `".DB_PREFIX."_Players`");
	$result = $query->fetch_assoc();
	$num_players = $result['pc'];
	$query->free();

	$query = $DB->query("SELECT COUNT(*) AS cc FROM `".DB_PREFIX."_Clans`");
	$result = $query->fetch_assoc();
	$num_clans = $result['cc'];
	$query->free();

	$query = $DB->query("SELECT COUNT(*) AS sc FROM `".DB_PREFIX."_Servers`");
	$result = $query->fetch_assoc();
	$num_servers = $result['sc'];
	$query->free();

	$lastevent = false;
	$query = $DB->query("SELECT MAX(eventTime) AS lastEvent FROM `".DB_PREFIX."_Events_Frags`");
	$result = $query->fetch_assoc();
	if(!empty($result['lastEvent'])) {
		$timstamp = strtotime($result['lastEvent']);
		$lastevent = getInterval($timstamp);
	}
	$query->free();
?>
	<ul>
		<li>
			<b><?php echo $num_players; ?></b>
			<?php echo l('players and'); ?><b> <?php echo $num_clans; ?></b> <?php echo l('Clans'),' ',l("ranked in"); ?>
			<b><?php echo $num_games; ?></b> <?php echo l('games on'); ?> <b><?php echo $num_servers; ?></b>
			<?php echo l("Servers"); ?>
		</li>

<?php
	if ($lastevent) {
?>
		<li>
			<?php echo l("Last kill"); ?> <b><?php echo $lastevent; ?></b> <?php echo l('ago'); ?>
		</li>
<?php
	}
?>
		<li>
			<?php echo l("All statistics are generated in real-time. Event history data expires after"),"<b> " . $g_options['DELETEDAYS'] . "</b> ",l("days"),'.';?>
		</li>
	</ul>
</p>
