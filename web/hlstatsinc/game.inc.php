<?php
/**
 * game overview file
 * display an overview for one game and display the awards
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

// check if we have roles for this game
$hasRoles = false;
$query = $DB->query("SELECT `roleId`
						FROM `".DB_PREFIX."_Roles`
						WHERE `game` = '".$DB->real_escape_string($game)."'");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if($query->num_rows > 0) {
	$hasRoles = true;
}
$query->free();

$awarddata_arr = false;
$awards_d_date = "None";
// check if we have awards
if (!$g_options['hideAwards'] && (isset($g_options['awards_d_date']) && $g_options['awards_d_date'] != "") ) {
	$queryAwards = $DB->query("SELECT a.name,
									a.verb,
									ah.d_winner_id,
									ah.d_winner_count,
									p.lastName AS d_winner_name,
									p.active AS active,
									p.isBot AS isBot
								FROM `".DB_PREFIX."_Awards_History` AS ah
								LEFT JOIN `".DB_PREFIX."_Players` AS p
									ON p.playerId = ah.d_winner_id
								LEFT JOIN `".DB_PREFIX."_Awards` AS a
									ON a.awardId = ah.fk_award_id
								WHERE ah.game = '".$DB->real_escape_string($game)."'
									AND ah.date = '".$DB->real_escape_string($g_options['awards_d_date'])."'
								ORDER BY a.awardType DESC,
									a.name ASC");
	if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
	if ($queryAwards->num_rows > 0) {
		$tmptime = strtotime($g_options['awards_d_date']);
		$awards_d_date = l(date('l',$tmptime)).' '.date('d.m.',$tmptime);
		while($result = $queryAwards->fetch_assoc()) {
			$awarddata_arr[] = $result;
		}
	}
}

pageHeader(array($gamename), array($gamename=>""));
?>
<div id="sidebar" >
	<h1><?php echo l('Sections'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="<?php echo "index.php?mode=players&amp;game=$game"; ?>"><?php echo l('Player Rankings'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=clans&amp;game=$game"; ?>"><?php echo l('Clan Rankings'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=teams&amp;game=$game"; ?>"><?php echo l('Team Rankings'); ?></a>
			</li>
			<?php if($hasRoles === true) { ?>
			<li>
				<a href="<?php echo "index.php?mode=roles&amp;game=$game"; ?>"><?php echo l('Role Rankings'); ?></a>
			</li>
			<?php } ?>
			<li>
				<a href="<?php echo "index.php?mode=weapons&amp;game=$game"; ?>"><?php echo l('Weapon Statistics'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=actions&amp;game=$game"; ?>"><?php echo l('Action Statistics'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=maps&amp;game=$game"; ?>"><?php echo l('Map Statistics'); ?></a>
			</li>
			<?php if ($g_options['USEGEOIP'] === "1") { ?>
			<li>
				<a href="<?php echo "index.php?mode=country&amp;game=$game"; ?>"><?php echo l('Country Statistics'); ?></a>
			</li>
			<?php } ?>
			<?php if (!$g_options['hideAwards'] && !empty($awarddata_arr)) { ?>
			<li>
				<a href="<?php echo "index.php?mode=awards&amp;game=$game"; ?>"><?php echo l('Awards History'); ?></a>
			</li>
			<?php } ?>
		</ul>
	</div>
	<h1><?php echo l('Game'); ?></h1>
	<div class="left-box">
		<img src="hlstatsimg/game-<?php echo $game; ?>-big.png" alt="<?php echo $game; ?>" title="<?php echo $game; ?>" width="100px" />
	</div>
</div>
<div id="main">
	<div class="content">
<?php
// should we hide the news ?
if(!$g_options['hideNews'] && $num_games === 1) {
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
	if (!$g_options['hideAwards'] && !empty($awarddata_arr)) {
?>
	<h1><?php echo l("Daily Awards")," ",l("for")," ",$awards_d_date,""; ?></h1>

	<table width="100%" border="1" cellspacing="0" cellpadding="4">
<?php foreach($awarddata_arr as $awarddata) { ?>
		<tr>
			<th width="30%"><?php echo htmlspecialchars($awarddata["name"]);?></th>
			<td width="70%">
			<?php
				if ($awarddata["d_winner_id"]) {
					if($awarddata['isBot'] === "1") {
						echo '<img src="hlstatsimg/bot.png" alt="'.l('BOT').'" title="'.l('BOT').'" width="16" height="16" />';
					}
					elseif($awarddata['active'] === "1") {
						echo '<img src="hlstatsimg/player.gif" alt="'.l('active Player').'" title="'.l('active Player').'" width="16" height="16" />';
					}
					else {
						echo '<img src="hlstatsimg/player_inactive.gif" alt="'.l('inactive Player').'" title="'.l('inactive Player').'" width="16" height="16" />';
					}
					echo "&nbsp;<a href=\"index.php?mode=playerinfo&amp;player=".$awarddata["d_winner_id"]."\"><b>";
					echo makeSavePlayerName($awarddata["d_winner_name"]) . "</b></a> (".$awarddata["d_winner_count"]." ".htmlspecialchars($awarddata["verb"]).")";
				}
				else {
					echo "(",l('Nobody').')';
				}
				?>
			</td>
		</tr>
<?php } ?>
	</table>

<?php
	}
?>
	<h1><?php echo l('Participating Servers'); ?></h1>

	<table width="100%" border="1" cellspacing="1" cellpadding="4">
		<tr>
			<th>&nbsp;<?php echo l('Name'); ?></th>
			<th>&nbsp;<?php echo l('Address'); ?></th>
			<th>&nbsp;<?php echo l('Current Server Statistics'); ?></th>
		</tr>
<?php
	$query = $DB->query("SELECT
							serverId, name,
							IF(publicaddress != '',
								publicaddress,
								concat(address, ':', port)
							) AS addr,
							statusurl
						FROM
							".DB_PREFIX."_Servers
						WHERE
							game = '".$DB->real_escape_string($game)."'
						ORDER BY
							name ASC,
							addr ASC");
	$i=0;
	while ($rowdata = $query->fetch_array()) {
		$c = ($i % 2) + 1;

		if ($rowdata["statusurl"]) {
			$addr = "<a href=\"" . $rowdata["statusurl"] . "\">"
				. $rowdata["addr"] . "</a>";
		}
		else {
			$addr = $rowdata["addr"];
		}
?>
		<tr>
			<td align="left">
				<img src="hlstatsimg/server.gif" width="16" height="16" hspace="3" border="0" align="middle" alt="server.gif">
				<?php echo $rowdata["name"]; ?>
			</td>
			<td align="left"><?php echo $addr; ?></td>
			<td align="center"><?php echo "<a href=\"index.php?mode=livestats&amp;server=$rowdata[serverId]\">",l('View'),"</a>"; ?></td>
		</tr>
<?php
		$i++;
	}
?>
	</table>

	<h1><?php echo $gamename; ?> <?php echo l('Statistics'); ?></h1>
<?php
	$query = $DB->query("SELECT COUNT(*) AS plc FROM `".DB_PREFIX."_Players` WHERE game = '".$DB->real_escape_string($game)."'");
	$result = $query->fetch_assoc();
	$num_players = $result['plc'];

	$query = $DB->query("SELECT COUNT(*) AS sc FROM `".DB_PREFIX."_Servers` WHERE game = '".$DB->real_escape_string($game)."'");
	$result = $query->fetch_assoc();
	$num_servers = $result['sc'];

	$lastevent = false;
	$query = $DB->query("SELECT MAX(eventTime) as lastEvent
		FROM `".DB_PREFIX."_Events_Frags` AS ef
		LEFT JOIN `".DB_PREFIX."_Servers` AS s
			ON s.serverId = ef.serverId
		WHERE s.game = '".$DB->real_escape_string($game)."'");
	if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
	$result = $query->fetch_assoc();
	if(!empty($result['lastEvent'])) {
		$timstamp = strtotime($result['lastEvent']);
		$lastevent = getInterval($timstamp);
	}
	$query->free();
?>
<p>
	<ul>
		<li>
			<?php echo "<b>$num_players</b> ",l('players')," ",l('ranked on')," <b>$num_servers</b> ",l('servers'),"."; ?>
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
			<?php echo l("All statistics are generated in real-time. Event history data expires after"), " <b>" . $g_options['DELETEDAYS'] . "</b> ",l("days"),"."; ?>
		</li>
	</ul>
</p>
	</div>
</div>
