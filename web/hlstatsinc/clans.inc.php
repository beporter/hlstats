<?php
/**
 * clans overview file
 * display complete clans overview
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
$clans['data'] = array();
$clans['pages'] = array();

// the current page to display
$page = 1;
if (isset($_GET["page"])) {
	$check = validateInput($_GET['page'],'digit');
	if($check === true) {
		$page = $_GET['page'];
	}
}

// the current element to sort by for the query
$sort = 'skill';
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

// minimum mebers count to show
$minmembers = 2;
if (isset($_GET["showAll"])) {
	$check = validateInput($_GET['showAll'],'digit');
	if($check === true)
		$minmembers = false;
}

// query to get the data from the db with the given options
$queryStr = "SELECT SQL_CALC_FOUND_ROWS
		c.clanId,
		c.name,
		c.tag,
		COUNT(p.playerId) AS nummembers,
		SUM(p.kills) AS kills,
		SUM(p.deaths) AS deaths,
		ROUND(AVG(p.skill)) AS skill,
		IFNULL(SUM(p.kills) / SUM(p.deaths), 0) AS kpd
	FROM `".DB_PREFIX."_Clans` AS c
	LEFT JOIN `".DB_PREFIX."_Players` AS p
		ON p.clan = c.clanId
	WHERE c.game = '".$DB->real_escape_string($game)."'
		AND p.hideranking = 0
	GROUP BY c.clanId";
if(!empty($minmembers)) {
	$queryStr .= " HAVING nummembers >= ".$DB->real_escape_string($minmembers);
}
	$queryStr .= " ORDER BY ".$sort." ".$sortorder."";

// calculate the limit
if($page === 1) {
	$queryStr .=" LIMIT 0,50";
}
else {
	$start = 50*($page-1);
	$queryStr .=" LIMIT ".$start.",50";
}
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
$query = $DB->query($queryStr);
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$clans['data'][] = $result;
	}
}
$query->free();

// query to get the total rows which would be fetched without the LIMIT
// works only if the $queryStr has SQL_CALC_FOUND_ROWS
$query = $DB->query("SELECT FOUND_ROWS() AS 'rows'");
$result = $query->fetch_assoc();
$clans['pages'] = (int)ceil($result['rows']/50);
$query->free();

pageHeader(
	array($gamename, l("Clan Rankings")),
	array($gamename=>"index.php?game=$game", l("Clan Rankings")=>"")
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
		<form method="GET" action="index.php">
			<input type="hidden" name="mode" value="search">
			<input type="hidden" name="game" value="<?php echo $game; ?>">
			<input type="hidden" name="st" value="clan">
			<?php echo l('Find a clan'); ?>:
			<input type="text" name="q" size="10"><br />
			<button type="submit" title="<?php echo l('Search'); ?>">
				<?php echo l('Search'); ?>
			</button>
		</form>
	</div>
	<h1><?php echo l('Game'); ?></h1>
	<div class="left-box">
		<img src="hlstatsimg/game-<?php echo $game; ?>-big.png" alt="<?php echo $game; ?>" title="<?php echo $game; ?>" width="100px" />
	</div>
</div>
<div id="main">
	<div class="content">
	<h1>
		<?php echo l("Clan Rankings"); ?>
	</h1>
	<p>
	<?php if(empty($minmembers)) { ?>
		<a href="index.php?mode=clans&amp;game=<?php echo $game; ?>"><?php echo l('Show only clans with 2 or more members') ?></a>
	<?php } else { ?>
		<a href="index.php?mode=clans&amp;game=<?php echo $game; ?>&amp;showAll=1"><?php echo l('Show all clans without a player limit of 2') ?></a>
	<?php } ?>
	</p>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<?php
		echo '<tr><td colspan="8" align="right">';
			if($clans['pages'] > 1) {
				for($i=1;$i<=$clans['pages'];$i++) {
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
		?>
		<tr>
			<th class="<?php echo $rcol; ?>"><?php echo l('Rank'); ?></th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'name','sortorder'=>$newSort)); ?>">
					<?php echo l('Name'); ?>
				</a>
				<?php if($sort == "name") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'tag','sortorder'=>$newSort)); ?>">
					<?php echo l('Tag'); ?>
				</a>
				<?php if($sort == "tag") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'skill','sortorder'=>$newSort)); ?>">
					<?php echo l('Points'); ?>
				</a>
				<?php if($sort == "skill") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'nummembers','sortorder'=>$newSort)); ?>">
					<?php echo l('Members'); ?>
				</a>
				<?php if($sort == "nummembers") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'kills','sortorder'=>$newSort)); ?>">
					<?php echo l('Kills'); ?>
				</a>
				<?php if($sort == "kills") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'deaths','sortorder'=>$newSort)); ?>">
					<?php echo l('Deaths'); ?>
				</a>
				<?php if($sort == "deaths") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'kpd','sortorder'=>$newSort)); ?>">
					<?php echo l('Kills per Death'); ?>
				</a>
				<?php if($sort == "kpd") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
		</tr>
		<?php
		if(!empty($clans['data'])) {
			if($page > 1) {
				$rank = ($page - 1) * (50 + 1);
			}
			else {
				$rank = 1;
			}
			foreach($clans['data'] as $k=>$entry) {
				toggleRowClass($rcol);

				echo '<tr>',"\n";

				echo '<td class="',$rcol,'">';
				echo $rank+$k;
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo '<a href="index.php?mode=claninfo&amp;clan=',$entry['clanId'],'&amp;game=',$game,'">';
				echo $entry['name'];
				echo '</a>';
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['tag'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['skill'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['nummembers'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['kills'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['deaths'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo number_format($entry['kpd'],2);
				echo '</td>',"\n";

				echo '</tr>';
			}
			echo '<tr><td colspan="8" align="right">';
			if($clans['pages'] > 1) {
				for($i=1;$i<=$clans['pages'];$i++) {
					if($page == ($i)) {
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
			echo '</td></tr>';
		}
		else {
			echo '<tr><td colspan="8">',l('No data recorded'),'</td></tr>';
		}
	?>
	</table>
	</div>
</div>
