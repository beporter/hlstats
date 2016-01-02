<?php
/**
 * awards history file
 * display the daily awards history
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

$rcol = "row-dark";
$awardsHistory['data'] = array();

$date = $g_options['awards_d_date'];

if (isset($_GET["date"])) {
	$check = validateInput($_GET['date'],'nospace');
	if($check === true) {
		$date = $_GET['date'];
	}
}
$tmptime = strtotime($date);
$awards_d_date = l(date('l',$tmptime)).' '.date('d.m.',$tmptime);

$query = $DB->query("SELECT a.name,
								a.verb,
								ah.d_winner_id,
								ah.d_winner_count,
								p.lastName AS d_winner_name,
								p.active AS active,
								p.isBot AS isBot,
								ah.date
							FROM `".DB_PREFIX."_Awards_History` AS ah
							LEFT JOIN `".DB_PREFIX."_Players` AS p
								ON p.playerId = ah.d_winner_id
							LEFT JOIN `".DB_PREFIX."_Awards` AS a
								ON a.awardId = ah.fk_award_id
							WHERE ah.game = '".$DB->real_escape_string($game)."'
								AND ah.date = '".$DB->real_escape_string($date)."'
							ORDER BY a.awardType DESC,
								a.name ASC");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if ($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$awardsHistory['data'][] = $result;
	}
	unset($result);
}
$query->free();

// get the dates for the date selection
$dateSelect = array();
$query = $DB->query("SELECT `date` FROM `".DB_PREFIX."_Awards_History`
						WHERE game = '".$DB->real_escape_string($game)."'
						GROUP BY `date`");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$dateSelect[$result['date']] = $result['date'];
	}
	unset($result);
}
$query->free();


pageHeader(
	array($gamename, l("Awards History")),
	array($gamename=>"index.php?game=$game", l("Awards History")=>"")
);
?>

<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="<?php echo "index.php?game=$game"; ?>"><?php echo l('Back to game overview'); ?></a>
			</li>
		</ul>
	</div>
	<h1><?php echo l('Game'); ?></h1>
	<div class="left-box">
		<img src="hlstatsimg/game-<?php echo $game; ?>-big.png" alt="<?php echo $game; ?>" title="<?php echo $game; ?>" width="100px" />
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo l("Awards History"),' ',l('for'),' ',$awards_d_date; ?></h1>
	<form method="get" action="index.php">
	<input type="hidden" name="mode" value="awards" />
	<input type="hidden" name="game" value="<?php echo $game; ?>" />
	<?php
if(!empty($dateSelect)) {
echo l('Date selection');
?>

	: <select name="date">
	<?php
		foreach($dateSelect as $ds) {
			$selected = '';
			if($date == $ds) $selected ='selected="1"';
			echo '<option value="',$ds,'" ',$selected,'>',$ds,'</option>';
		}
	?>
	</select>
	<button type="submit" title="<?php echo l('Show'); ?>">
		<?php echo l('Show'); ?>
	</button>
	</form>
<?php }	?>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th width="200" class="<?php echo $rcol; ?>">
				<?php echo l('Name'); ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<?php echo l('Player'); ?>
			</th>
		</tr>
		<?php
		if(!empty($awardsHistory['data'])) {
			foreach($awardsHistory['data'] as $k=>$entry) {
				toggleRowClass($rcol);

				echo '<tr>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['name'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				if($entry['d_winner_id']) {
					if($entry['isBot'] === "1") {
						echo '<img src="hlstatsimg/bot.png" alt="'.l('BOT').'" title="'.l('BOT').'" width="16" height="16" />';
					}
					elseif($entry['active'] === "1") {
						echo '<img src="hlstatsimg/player.gif" alt="'.l('active Player').'" title="'.l('active Player').'" width="16" height="16" />';
					}
					else {
						echo '<img src="hlstatsimg/player_inactive.gif" alt="'.l('inactive Player').'" title="'.l('inactive Player').'" width="16" height="16" />';
					}
					echo '&nbsp;<a href="index.php?mode=playerinfo&amp;player=',$entry["d_winner_id"],'"><b>',makeSavePlayerName($entry["d_winner_name"]),'</b></a>';
					echo '&nbsp;(',$entry["d_winner_count"],' ',htmlspecialchars($entry["verb"]),')';
				}
				else {
					echo '(',l('Nobody').')';
				}
				echo '</td>',"\n";

				echo '</tr>';
			}
		}
		else {
			echo '<tr><td colspan="3">',l('No data recorded'),'</td></tr>';
		}
		?>
	</table>
	<p><b><?php echo l('Note'); ?>:</b><br />
	<?php echo l('Award history cover only the last'),'&nbsp;',$g_options['DELETEDAYS'],' ',l('days')?>
	</p>
	</div>
</div>
