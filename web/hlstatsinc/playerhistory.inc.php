<?php
/**
 * single action overview file
 * display the action listing sorted by action count for each player
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

$player = '';
$pl_name = '';

if(!empty($_GET["player"])) {
	if(validateInput($_GET["player"],'digit') === true) {
		$player = $_GET["player"];
	}
	else {
		die("No player ID specified.");
	}
}

// load the player
require('class/player.class.php');
$playerObj = new Player($player,false);
if($playerObj === false) {
	die('No such player');
}

if (isset($_GET["page"])) {
	$check = validateInput($_GET['page'],'digit');
	if($check === true) {
		$playerObj->setOption("page",$_GET['page']);
	}
}
if (isset($_GET["sort"])) {
	$check = validateInput($_GET['sort'],'nospace');
	if($check === true) {
		$playerObj->setOption("sort",$_GET['sort']);
	}
}
else {
	$playerObj->setOption("sort",'eventTime');
}
$newSort = "ASC";
if (isset($_GET["sortorder"])) {
	$check = validateInput($_GET['sortorder'],'nospace');
	if($check === true) {
		$playerObj->setOption("sortorder",$_GET['sortorder']);
	}

	if($_GET["sortorder"] == "ASC") {
		$newSort = "DESC";
	}
}
else {
	$playerObj->setOption("sortorder",'DESC');
}



$gamename = getGameName($playerObj->getParam("game"));
$pl_name = makeSavePlayerName($playerObj->getParam('name'));
pageHeader(
	array($gamename, l("Event History"), $pl_name),
	array(
		$gamename => "index.php?game=".$playerObj->getParam("game"),
		l("Player Rankings") => "index.php?mode=players&amp;game=".$playerObj->getParam("game"),
		l("Player Details") => "index.php?mode=playerinfo&amp;player=$player",
		l("Event History")=>""
	),
	$pl_name
);
?>

<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="index.php?mode=playerhistory&amp;player=<?php echo $player; ?>"><img src="hlstatsimg/history.gif" width='16' height='16' border='0' hspace="3" align="middle" alt="history.gif"><?php echo l('Event History'); ?></a>
			</li>
			<li>
				<a href="index.php?mode=playerchathistory&amp;player=<?php echo $player; ?>"><img src="hlstatsimg/history.gif" width='16' height='16' border='0' hspace="3" align="middle" alt="history.gif"><?php echo l('Chat History'); ?></a>
			</li>
			<li>
				<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>"><img src="hlstatsimg/player.gif" width='16' height='16' border='0' hspace="3" align="middle" alt="player.gif"><?php echo l('Back to Player page'); ?></a>
			</li>
		</ul>
	</div>
	<h1><?php echo l('Game'); ?></h1>
	<div class="left-box">
		<img src="hlstatsimg/game-<?php echo $playerObj->getParam('game'); ?>-big.png" alt="<?php echo $playerObj->getParam('game'); ?>" title="<?php echo $playerObj->getParam('game'); ?>" width="100px" />
	</div>
</div>
<div id="main">
	<div class="content">
	<h1>
		<?php echo l('Player Event History'); ?>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h1>
<?php
	$history = $playerObj->getEventHistory();
	$rcol = "row-dark";
	if(!empty($history['data'])) {
?>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th class="<?php echo toggleRowClass($rcol); ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'eventTime','sortorder'=>$newSort)); ?>">
					<?php echo l('Date'); ?>
				</a>
				<?php if($playerObj->getOption('sort') == "eventTime") { ?>
				<img src="hlstatsimg/<?php echo $playerObj->getOption('sortorder'); ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo toggleRowClass($rcol); ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'eventType','sortorder'=>$newSort)); ?>">
					<?php echo l('Type'); ?>
				</a>
				<?php if($playerObj->getOption('sort') == "eventType") { ?>
				<img src="hlstatsimg/<?php echo $playerObj->getOption('sortorder'); ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo toggleRowClass($rcol); ?>"><?php echo l('Description'); ?></th>
			<th class="<?php echo toggleRowClass($rcol); ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'serverName','sortorder'=>$newSort)); ?>">
					<?php echo l('Server'); ?>
				</a>
				<?php if($playerObj->getOption('sort') == "serverName") { ?>
				<img src="hlstatsimg/<?php echo $playerObj->getOption('sortorder'); ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo toggleRowClass($rcol); ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'map','sortorder'=>$newSort)); ?>">
					<?php echo l('Map'); ?>
				</a>
				<?php if($playerObj->getOption('sort') == "map") { ?>
				<img src="hlstatsimg/<?php echo $playerObj->getOption('sortorder'); ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
		</tr>
<?php
		foreach($history['data'] as $entry) {
			$rcol = "row-dark";
			echo '<tr>';
			echo '<td class="',toggleRowClass($rcol),'">',$entry['eventTime'],'</td>';
			echo '<td class="',toggleRowClass($rcol),'">',$entry['eventType'],'</td>';
			echo '<td class="',toggleRowClass($rcol),'">',$entry['eventDesc'],'</td>';
			echo '<td class="',toggleRowClass($rcol),'">',$entry['serverName'],'</td>';
			echo '<td class="',toggleRowClass($rcol),'">',$entry['map'],'</td>';
			echo '</tr>';
		}
		echo '<tr><td colspan="5" align="right">';
		if($history['pages'] > 1) {
			for($i=1;$i<=$history['pages'];$i++) {
				if($playerObj->getOption('page') == ($i)) {
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
		echo '</td></tr></table>',"\n";
	}
	else {
		echo l('No Data');
	}
?>
	</div>
</div>
