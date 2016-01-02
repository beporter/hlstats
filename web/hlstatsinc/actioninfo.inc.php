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

// the initial row color
$rcol = "row-dark";

// the players array which holds the data to display and the page count
$players['data'] = array();
$players['pages'] = array();


// the action identifier which is needed to load the data
$action = false;
if(!empty($_GET["action"])) {
	if(validateInput($_GET["action"],'nospace') === true) {
		$action = $_GET["action"];
	}
	else {
		die("No action specified.");
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

// query to get the full action name
$queryActionName = $DB->query("SELECT description FROM `".DB_PREFIX."_Actions`
					WHERE code = '".$DB->real_escape_string($action)."'
						AND game = '".$DB->real_escape_string($game)."'");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if ($query->num_rows != 1) {
	$act_name = ucfirst($action);
}
else {
	$result = $query->fetch_assoc();
	//the full action name
	$act_name = $result["description"];
}
$query->free();

// query to get the total total action count
$queryCount = $DB->query("SELECT
		COUNT(epa.Id) AS tc
	FROM `".DB_PREFIX."_Events_PlayerActions` AS epa,
		`".DB_PREFIX."_Players` AS p,
		`".DB_PREFIX."_Actions` AS a
	WHERE a.code = '".$DB->real_escape_string($action)."'
		AND p.game = '".$DB->real_escape_string($game)."'
		AND p.playerId = epa.playerId
		AND epa.actionId = a.id");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
$result = $queryCount->fetch_assoc();
// the toral action count for this specific action
$totalact = $result['tc'];

if(!empty($totalact)) {
	// query to get the data from the db with the given options
	$queryStr = "SELECT SQL_CALC_FOUND_ROWS
			epa.playerId,
			p.lastName AS playerName,
			p.active AS active,
			p.isBot AS isBot,
			COUNT(epa.id) AS obj_count,
			a.reward_player AS obj_bonus
		FROM `".DB_PREFIX."_Events_PlayerActions` AS epa,
			`".DB_PREFIX."_Players` AS p,
			`".DB_PREFIX."_Actions` AS a
		WHERE a.code = '".$DB->real_escape_string($action)."'
			AND p.game = '".$DB->real_escape_string($game)."'
			AND p.playerId = epa.playerId
			AND epa.actionId = a.id
			AND p.hideranking <> '1'
		GROUP BY epa.playerId
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
			$players['data'][] = $result;
		}
	}

	// query to get the total rows which would be fetched without the LIMIT
	$query = $DB->query("SELECT FOUND_ROWS() AS 'rows'");
	$result = $query->fetch_assoc();
	$players['pages'] = (int)ceil($result['rows']/50);
	$query->free();

}

pageHeader(
	array($gamename, l("Action Details"), $act_name),
	array(
		$gamename => "index.php?game=$game",
		l("Action Statistics") => "index.php?mode=actions&amp;game=$game",
		l("Action Details")=>""
	),
	$act_name
);

?>
<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="<?php echo "index.php?mode=actions&amp;game=$game"; ?>"><?php echo l('Back to Action Statistics'); ?></a>
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
		<?php echo l('From a total of'); ?> <b><?php echo intval($totalact); ?></b> <?php echo l('achievements'); ?> (<?php echo l('Last'); ?> <?php echo $g_options['DELETEDAYS']; ?> <?php echo l('days'); ?>)
	</h1>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th class="<?php echo $rcol; ?>"><?php echo l('Rank'); ?></th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'playerId','sortorder'=>$newSort)); ?>">
					<?php echo l('Player'); ?>
				</a>
				<?php if($sort == "playerId") { ?>
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
					<?php echo l('Skill Bonus Total'); ?>
				</a>
				<?php if($sort == "obj_bonus") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
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
				echo '&nbsp;<a href="index.php?mode=playerinfo&amp;player=',$entry['playerId'],'">';
				echo makeSavePlayerName($entry['playerName']);
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
				if($players['pages'] > 1) {
					for($i=1;$i<=$players['pages'];$i++) {
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
		}
		else {
			echo '<tr><td colspan="4">',l('No data recorded'),'</td></tr>';
		}
	?>
	</table>
	</div>
</div>
