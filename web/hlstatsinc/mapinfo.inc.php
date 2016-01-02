<?php
/**
 * map info page
 * display the kills for each player on this map compared to all map kills
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

// the initial row color
$rcol = "row-dark";

// the players array which holds the data to display and the page count
$players['data'] = array();
$players['pages'] = array();

// the current map to display
$map = false;
if(!empty($_GET["map"])) {
	if(validateInput($_GET["map"],'nospace') === true) {
		$map = $_GET["map"];
	}
	else {
		die("No map specified.");
	}
}

// the current page to display
$page = 1;
if (isset($_GET["page"])) {
	$check = validateInput($_GET['page'],'digit');
	if($check === true) {
		$page = $_GET['page'];
	}
}

// the current element to sort by for the query
$sort = 'frags';
if (isset($_GET["sort"])) {
	$check = validateInput($_GET['sort'],'nospace');
	if($check === true) {
		$sort = $_GET['sort'];
	}
}

// the default next sort order
$newSort = "ASC";
// the default sort order for the query
$sortorder = 'DESC';
if (isset($_GET["sortorder"])) {
	$check = validateInput($_GET['sortorder'],'nospace');
	if($check === true) {
		$sortorder = $_GET['sortorder'];
	}

	if($_GET["sortorder"] == "ASC") {
		$newSort = "DESC";
	}
}

// query to get the total kills count for this map
$queryCount = $DB->query("SELECT COUNT(DISTINCT ef.killerId) AS cc,
		SUM(ef.map = '".$DB->real_escape_string($map)."') AS tc
	FROM `".DB_PREFIX."_Events_Frags` AS ef
	LEFT JOIN `".DB_PREFIX."_Players` AS p
		ON p.playerId = ef.killerId
	WHERE ef.map = '".$DB->real_escape_string($map)."'
		AND p.game = '".$DB->real_escape_string($game)."'
		AND p.hideranking = 0
");
$result = $queryCount->fetch_assoc();
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
// the total kills for this map
$totalkills = $result['tc'];
$queryCount->free();

if(!empty($totalkills)) {
	$queryStr = "SELECT SQL_CALC_FOUND_ROWS
		ef.killerId,
		p.lastName AS killerName,
		COUNT(ef.map) AS frags,
		p.active,
		p.isBot
	FROM `".DB_PREFIX."_Events_Frags` AS ef
	LEFT JOIN `".DB_PREFIX."_Players` AS p
		ON p.playerId = ef.killerId
	WHERE ef.map = '".$DB->real_escape_string($map)."'
		AND p.game = '".$DB->real_escape_string($game)."'
		AND p.hideranking = 0
	GROUP BY ef.killerId
	ORDER BY ".$sort." ".$sortorder."";

	// calculate the limit
	if($page === 1) {
		$queryStr .=" LIMIT 0,50";
	}
	else {
		$start = 50*($page-1);
		$queryStr .=" LIMIT ".$start.",50";
	}

	$query = $DB->query($queryStr);
	if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
	if($query->num_rows > 0) {
		while($result = $query->fetch_assoc()) {
			$result['percent'] = $result['frags']/$totalkills*100;
			$players['data'][] = $result;
		}
	}
	$query->free();

	// query to get the total rows which would be fetched without the LIMIT
	// works only if the $queryStr has SQL_CALC_FOUND_ROWS
	$query = $DB->query("SELECT FOUND_ROWS() AS 'rows'");
	$result = $query->fetch_assoc();
	$players['pages'] = (int)ceil($result['rows']/50);
	$query->free();
}


pageHeader(
	array($gamename, l("Map Details"), $map),
	array(
		$gamename => "index.php?game=$game",
		l("Map Statistics") => "index.php?mode=maps&amp;game=$game",
		l("Map Details")=>""
	),
	$map
);

// map image
$mapImage = 'hlstatsimg/maps/'.strtolower($game).'/'.strtolower($map).'.jpg';
if(!file_exists($mapImage)) {
	$mapImage = 'hlstatsimg/noimage.jpg';
}

?>
<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="<?php echo "index.php?game=$game&amp;mode=maps"; ?>"><?php echo l('Back to Map Statistics'); ?></a>
			</li>
		</ul>
		<img src="<?php echo $mapImage; ?>" alt="<?php echo $map; ?>" title='<?php echo $map; ?>'><br />
		<?php
		if ($g_options["map_dlurl"]) {
			$map_dlurl = str_replace("%MAP%", $map, $g_options["map_dlurl"]);
			$map_dlurl = str_replace("%GAME%", $game, $map_dlurl);
			echo "<p><a href=\"$map_dlurl\">Download this map...</a></p>";
		}
		?>
	</div>
	<h1><?php echo l('Game'); ?></h1>
	<div class="left-box">
		<img src="hlstatsimg/game-<?php echo $game; ?>-big.png" alt="<?php echo $game; ?>" title="<?php echo $game; ?>" width="100px"  />
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo l("Map Details"); ?> |
		<?php echo l('From a total of'); ?> <b><?php echo intval($totalkills); ?></b> <?php echo l('kills'); ?> (<?php echo l('Last'); ?> <?php echo $g_options['DELETEDAYS']; ?> <?php echo l('days'); ?>)
	</h1>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th class="<?php echo $rcol; ?>"><?php echo l('Rank'); ?></th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'killerId','sortorder'=>$newSort)); ?>">
					<?php echo l('Player'); ?>
				</a>
				<?php if($sort == "killerId") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'frags','sortorder'=>$newSort)); ?>">
					<?php echo l('Kills on'),' ',$map; ?>
				</a>
				<?php if($sort == "frags") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>"><?php echo l('Percentage of Kills'); ?></th>
		</tr>
	<?php
		if(!empty($players['data'])) {
			if($page > 1) {
				$rank = ($page - 1) * (50 + 1);
			}
			else {
				$rank = 1;
			}
			foreach($players['data'] as $k=>$entry) {
				toggleRowClass($rcol);

				echo '<tr>',"\n";

				echo '<td class="',$rcol,'">';
				echo $rank+$k;
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				if($entry['isBot'] === "1") {
					echo '<img src="hlstatsimg/bot.png" alt="'.l('BOT').'" title="'.l('BOT').'" width="16" height="16" />';
				}
				elseif($entry['active'] === "1") {
					echo '<img src="hlstatsimg/player.gif" alt="'.l('active Player').'" title="'.l('active Player').'" width="16" height="16" />';
				}
				else {
					echo '<img src="hlstatsimg/player_inactive.gif" alt="'.l('inactive Player').'" title="'.l('inactive Player').'" width="16" height="16" />';
				}
				echo '<a href="index.php?mode=playerinfo&amp;player=',$entry['killerId'],'">';
				echo makeSavePlayerName($entry['killerName']);
				echo '</a>';
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['frags'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo '<div class="percentBar" title="',number_format((int)$entry['percent'],0),'%"><div class="barContent" style="width:',number_format((int)$entry['percent'],0),'px"></div></div>',"\n";
				echo '</td>',"\n";

				echo '</tr>';
			}
			echo '<tr><td colspan="4" align="right">';
				if($players['pages'] > 1) {
					for($i=1;$i<=$players['pages'];$i++) {
						if($players == ($i)) {
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
		}
		else {
			echo '<tr><td colspan="4">',l('No data recorded'),'</td></tr>';
		}
	?>
	</table>
	</div>
</div>
