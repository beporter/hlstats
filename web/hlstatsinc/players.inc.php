<?php
/**
 * list all players for a game
 * overall overview from all players
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

require('class/players.class.php');
$playersObj = new Players($game);

/**
 * check the get values
 */
if (isset($_GET["minkills"])) {
	$check = validateInput($_GET['minkills'],'digit');
	if($check === true) {
		$playersObj->setOption("minkills",$_GET['minkills']);
	}
}
if (isset($_GET["showall"])) {

	$check = validateInput($_GET['showall'],'digit');
	if($check === true) {
		$playersObj->setOption("showall",$_GET['showall']);
	}
}
if (isset($_GET["showToday"])) {
	$check = validateInput($_GET['showToday'],'digit');
	if($check === true) {
		$playersObj->setOption("showToday",$_GET['showToday']);
	}
}
if (isset($_GET["showBots"])) {
	$check = validateInput($_GET['showBots'],'digit');
	if($check === true) {
		$playersObj->setOption("showBots",$_GET['showBots']);
	}
}
else {
	$playersObj->setOption("showBots",'0');
}

if (isset($_GET["page"])) {
	$check = validateInput($_GET['page'],'digit');
	if($check === true) {
		$playersObj->setOption("page",$_GET['page']);
	}
}
if (isset($_GET["sort"])) {
	$check = validateInput($_GET['sort'],'nospace');
	if($check === true) {
		$playersObj->setOption("sort",$_GET['sort']);
	}
}
else {
	$playersObj->setOption("sort",'skill');
}

$newSort = "ASC";
if (isset($_GET["sortorder"])) {
	$check = validateInput($_GET['sortorder'],'nospace');
	if($check === true) {
		$playersObj->setOption("sortorder",$_GET['sortorder']);
	}

	if($_GET["sortorder"] == "ASC") {
		$newSort = "DESC";
	}
}
else {
	$playersObj->setOption("sortorder",'DESC');
}

pageHeader(
	array($gamename, l('Player Rankings')),
	array($gamename => "index.php?game=$game", l('Player Rankings')=>"")
);

# get the players overview
$pData = $playersObj->getPlayersOveriew();
?>

<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
		<?php if(isset($_GET['showall']) && $_GET['showall'] === "1") {
			echo '<a href="?mode=players&amp;game=',$game,'">',l('Show only active players'),'</a>';
		}
		else {
			echo '<a href="?mode=players&amp;game=',$game,'&amp;showall=1">',l('Show all players'),'</a>';
		}
		?>
			</li>
			<li>
				<a href="?mode=players&amp;game=<?php echo $game; ?>&amp;showToday=1"><?php echo l('Show players from today'); ?></a>
			</li>
			<?php if($g_options['IGNOREBOTS'] === "0") { ?>
			<li>
				<?php if(isset($_GET['showBots']) && $_GET['showBots'] === "1") { ?>
				<a href="?mode=players&amp;game=<?php echo $game; ?>"><img src="hlstatsimg/bot.png" width="16" height="16" hspace="3" border="0" align="middle" alt="BOTs">&nbsp;<?php echo l('HideBots'); ?></a>
				<?php } else { ?>
				<a href="?mode=players&amp;game=<?php echo $game; ?>&amp;showBots=1"><img src="hlstatsimg/bot.png" width="16" height="16" hspace="3" border="0" align="middle" alt="BOTs">&nbsp;<?php echo l('Show BOTs too'); ?></a>
				<?php } ?>
			</li>
			<?php } ?>
			<li>
				<a href="<?php echo "index.php?mode=clans&amp;game=$game"; ?>"><img src="hlstatsimg/clan.gif" width="16" height="16" hspace="3" border="0" align="middle" alt="clan.gif">&nbsp;<?php echo l('Clan Rankings'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=playerstimeline&amp;game=$game"; ?>"><img src="hlstatsimg/chart.png" width="16" height="16" hspace="3" border="0" align="middle" alt="clan.gif">&nbsp;<?php echo l('Players timeline'); ?></a>
			</li>
		</ul>
		<form method="GET" action="index.php">
			<input type="hidden" name="game" value="<?php echo $game; ?>" />
			<input type="hidden" name="mode" value="players" />
			<?php echo l('Only show players with'); ?><br />
			<input type="text" name="minkills" size="4" maxlength="4" value="<?php echo $playersObj->getOption('minkills'); ?>"><br />
			<?php echo l('or more kills'); ?>.<br />
			<button type="submit" title="<?php echo l('Apply'); ?>">
				<?php echo l('Apply'); ?>
			</button>
		</form>
		<small>
		<?php echo l('Default is to show only active players'); ?>
		(<a href="index.php?mode=help#playersoverview">?</a>)<br />
		<?php echo l('All players include inactive players.'); ?><br />
		<?php echo l('BOTs will show only if the are not ignored.'); ?>
		</small>
	</div>
	<h1><?php echo l('Game'); ?></h1>
	<div class="left-box">
		<img src="hlstatsimg/game-<?php echo $game; ?>-big.png" alt="<?php echo $game; ?>" title="<?php echo $game; ?>" width="100px" />
	</div>
</div>
<div id="main">
	<div class="content">
<?php
	$rcol = "row-dark";
?>
	<table cellpadding="0" cellspacing="0" width="100%" border="1">
		<?php
		echo '<tr><td colspan="6" align="right">';
		if($pData['pages'] > 1) {
			$si = 1;
			$ei = $pData['pages'];
			if($playersObj->getOption('page') > 10) {
				echo "<a href='index.php?",makeQueryString(array('page'=>1)),"'>[1]</a>...";
				echo "<a href='index.php?",makeQueryString(array('page'=>$playersObj->getOption('page')-2)),"'>[",$playersObj->getOption('page')-2,"]</a>";
				echo "<a href='index.php?",makeQueryString(array('page'=>$playersObj->getOption('page')-1)),"'>[",$playersObj->getOption('page')-1,"]</a>";
				$si = $playersObj->getOption('page');
			}
			if($playersObj->getOption('page') < $pData['pages']-13) {
				$si = $playersObj->getOption('page');
				$ei = $playersObj->getOption('page')+10;
			}
			for($i=$si;$i<=$ei;$i++) {
				if($playersObj->getOption('page') == ($i)) {
					echo "[",$i,"]";
				}
				else {
					echo "<a href='index.php?",makeQueryString(array('page'=>$i)),"'>[",$i,"]</a> ";
				}
			}
			if($playersObj->getOption('page') < ($pData['pages']-13)) {
				echo '&nbsp;[>>]';
			}
		}
		else {
			echo "[1]";
		}
		echo '</td></tr>',"\n";
		?>
		<tr>
			<th ><?php echo l('Rank'); ?></th>
			<th >
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'lastName','sortorder'=>$newSort)); ?>">
					<?php echo l('Name'); ?>
				</a>
				<?php if($playersObj->getOption('sort') == "lastName") { ?>
				<img src="hlstatsimg/<?php echo $playersObj->getOption('sortorder'); ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th >
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'skill','sortorder'=>$newSort)); ?>">
					<?php echo l('Points'); ?>
				</a>
				<?php if($playersObj->getOption('sort') == "skill") { ?>
				<img src="hlstatsimg/<?php echo $playersObj->getOption('sortorder'); ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th >
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'kills','sortorder'=>$newSort)); ?>">
					<?php echo l('Kills'); ?>
				</a>
				<?php if($playersObj->getOption('sort') == "kills") { ?>
				<img src="hlstatsimg/<?php echo $playersObj->getOption('sortorder'); ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th >
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'deaths','sortorder'=>$newSort)); ?>">
					<?php echo l('Deaths'); ?>
				</a>
				<?php if($playersObj->getOption('sort') == "deaths") { ?>
				<img src="hlstatsimg/<?php echo $playersObj->getOption('sortorder'); ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th >
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'kpd','sortorder'=>$newSort)); ?>">
					<?php echo l('Kills per Death'); ?>
				</a>
				<?php if($playersObj->getOption('sort') == "kpd") { ?>
				<img src="hlstatsimg/<?php echo $playersObj->getOption('sortorder'); ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
		</tr>
		<?php

			if(!empty($pData['data'])) {
				if($playersObj->getOption('page') > 1) {
					$rank = ($playersObj->getOption('page') - 1) * (50 + 1);
				}
				else {
					$rank = 1;
				}

				// needed to display the corrent rank
				$x = 0;
				foreach($pData['data'] as $entry) {
					$rcol = "row-dark";

					echo '<tr>',"\n";

					echo '<td class="',toggleRowClass($rcol),'">';
					echo $rank+$x;
					echo '</td>',"\n";

					echo '<td class="',toggleRowClass($rcol),'">';
					if($entry['isBot'] === "1") {
						echo '<img src="hlstatsimg/bot.png" alt="BOT" title="BOT" width="16" height="16" />&nbsp;';
					}
					elseif($entry['active'] === "1") {
						echo '<img src="hlstatsimg/player.gif" alt="'.l('active Player').'" title="'.l('active Player').'" width="16" height="16" />&nbsp;';
					}
					else {
						echo '<img src="hlstatsimg/player_inactive.gif" alt="'.l('inactive Player').'" title="'.l('inactive Player').'" width="16" height="16" />&nbsp;';
					}

					echo '&nbsp;<a href="index.php?mode=playerinfo&amp;player=',$entry['playerId'],'">',$entry['lastName'],'</a></td>',"\n";

					echo '<td class="',toggleRowClass($rcol),'">';
					echo '<img width="16" height="16" ';
					if($entry['skill'] > $entry['oldSkill']) {
						echo 'src="hlstatsimg/skill_up.gif" alt="Up" title="Up"';
					}
					elseif($entry['skill'] < $entry['oldSkill']) {
						echo 'src="hlstatsimg/skill_down.gif" alt="Down" title="Down"';
					}
					else {
						echo 'src="hlstatsimg/skill_stay.gif" alt="Stay" title="Stay"';
					}
					echo ' />';
					echo $entry['skill'],'</td>',"\n";

					echo '<td class="',toggleRowClass($rcol),'">',$entry['kills'],'</td>',"\n";
					echo '<td class="',toggleRowClass($rcol),'">',$entry['deaths'],'</td>',"\n";
					echo '<td class="',toggleRowClass($rcol),'">',$entry['kpd'],'</td>',"\n";

					echo '</tr>',"\n";
					$x++;
				}

				echo '<tr><td colspan="6" align="right">';
				if($pData['pages'] > 1) {
					for($i=1;$i<=$pData['pages'];$i++) {
						if($playersObj->getOption('page') == ($i)) {
							echo "[",$i,"]";
						}
						else {
							echo "<a href='index.php?",makeQueryString(array('page'=>$i)),"'>[",$i,"]</a> ";
						}
					}
				}
				else {
					echo "[1]";
				}
				echo '</td></tr>',"\n";
			}
			else {
				echo '<tr><td colspan="6">',l('No players recorded'),'</td></tr>',"\n";
			}
		?>
	</table>
	<?php
	// if so show a timeline of player count
    if($g_options['showChart'] == "1") {

		require('class/chart.class.php');
		$chartObj = new Chart($game);

		$chart = $chartObj->getChart('playerActivity',$playersObj);
		if(!empty($chart)) {
			echo '<h2>',l('Players per day'),' - ',l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'),'</h2>';
			echo '<div class="chart"><img src="',$chart,'" /></div>';
		}


		$chartObj2 = new Chart($game);
		$chartObj2->setOption('height',350);
		$chart = $chartObj2->getChart('mostTimeOnline',$playersObj);
		if(!empty($chart)) {
			echo '<h2>',l('Most time online (hours)'),' - ',l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'),'</h2>';
			echo '<div class="chart"><img src="',$chart,'" /></div>';
			$chartData = $chartObj2->getChartData();
			if(!empty($chartData)) {
				echo '<h2></h2>';
				echo "<ol>\n";
				foreach($chartData as $playerId=>$entry) {
					echo '<li><a href="index.php?mode=playerinfo&amp;player=',$playerId,'">',makeSavePlayerName($entry['playerName']),'</a> ',getTimeFromSec($entry['timeSec']),'</li>';
				}
				echo "</ol>\n";
			}
		}
    }
?>
	</div>
</div>
