<?php
/**
 * actions overview file
 * display the complete game actions sorted by actions count
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

// the actions array which holds the data to display and the page count
$actions['data'] = array();
$actions['pages'] = array();

// the current page to display
$page = 1;
if (isset($_GET["page"])) {
	$check = validateInput($_GET['page'],'digit');
	if($check === true) {
		$page = $_GET['page'];
	}
}

// the current element to sort by for the query
$sort = 'obj_count';
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

// query to get the total actions count for this game
$queryActionsCount = $DB->query("SELECT COUNT(*) ac
	FROM `".DB_PREFIX."_Actions` AS a,
		`".DB_PREFIX."_Events_PlayerActions` AS epa
	WHERE epa.actionId = a.id
		AND a.game = '".$DB->real_escape_string($game)."'");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
$result = $queryActionsCount->fetch_assoc();
// get the total actions count for this game
$totalactions = $result['ac'];
$queryActionsCount->free();

if(!empty($totalactions)) {
	// query to get the data from the db with the given options
	$queryStr = "SELECT SQL_CALC_FOUND_ROWS
		a.code,
		a.description,
		COUNT(epa.id) AS obj_count,
		a.reward_player AS obj_bonus
	FROM `".DB_PREFIX."_Actions` AS a,
		`".DB_PREFIX."_Events_PlayerActions` AS epa,
		`".DB_PREFIX."_Players` AS p
	WHERE epa.playerId = p.playerId
		AND p.game = '".$DB->real_escape_string($game)."'
		AND epa.actionId = a.id
		AND a.game = '".$DB->real_escape_string($game)."'
		AND p.hideranking = 0
	GROUP BY a.id
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
			$actions['data'][] = $result;
		}
	}
	$query->free();

	// query to get the total rows which would be fetched without the LIMIT
	// works only if the $queryStr has SQL_CALC_FOUND_ROWS
	$query = $DB->query("SELECT FOUND_ROWS() AS 'rows'");
	$result = $query->fetch_assoc();
	$actions['pages'] = (int)ceil($result['rows']/50);
	$query->free();
}

pageHeader(
	array($gamename, l("Action Statistics")),
	array($gamename=>"index.php?game=$game", l("Action Statistics")=>"")
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
	<h1>
		<?php echo l("Action Statistics"); ?> |
		<?php echo l('From a total of'); ?> <b><?php echo $totalactions; ?></b> <?php echo l('actions'); ?> (<?php echo l('Last'); ?> <?php echo $g_options['DELETEDAYS']; ?> <?php echo l('days'); ?>)
	</h1>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th class="<?php echo $rcol; ?>"><?php echo l('Rank'); ?></th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'code','sortorder'=>$newSort)); ?>">
					<?php echo l('Action'); ?>
				</a>
				<?php if($sort == "code") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'obj_count','sortorder'=>$newSort)); ?>">
					<?php echo l('Achieved'); ?>
				</a>
				<?php if($sort == "obj_count") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'obj_bonus','sortorder'=>$newSort)); ?>">
					<?php echo l('Skill Bonus'); ?>
				</a>
				<?php if($sort == "obj_bonus") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
		</tr>
	<?php
		if(!empty($actions['data'])) {
			if($page > 1) {
				$rank = ($page - 1) * (50 + 1);
			}
			else {
				$rank = 1;
			}
			foreach($actions['data'] as $k=>$entry) {
				toggleRowClass($rcol);

				echo '<tr>',"\n";

				echo '<td class="',$rcol,'">';
				echo $rank+$k;
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo '<a href="index.php?mode=actioninfo&amp;action=',$entry['code'],'&amp;game=',$game,'">';
				echo $entry['description'];
				echo '</a>';
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['obj_count'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['obj_bonus'];
				echo '</td>',"\n";

				echo '</tr>';
			}
			echo '<tr><td colspan="4" align="right">';
			if($actions['pages'] > 1) {
				for($i=1;$i<=$actions['pages'];$i++) {
					if($page == ($i)) {
						echo "[",$i,"]";
					}
					else {
						echo "<a href='index.php?",makeQueryString(array('page'=>$i)),"'>[",$i,"]</a>";
					}
				}
			}
			else {
				echo "[1]";
			}
			echo '</td></tr>';
		}
		else {
			echo '<tr><td colspan="4">',l('No data recorded'),'</td></tr>';
		}
	?>
	</table>
	</div>
</div>
