<?php
/**
 * clan overview file
 * display complete clan overview and its memebers
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
$members['data'] = array();
$members['pages'] = array();

// the clanId
$clan = '';
if(!empty($_GET["clan"])) {
	if(validateInput($_GET["clan"],'digit') === true) {
		$clan = $_GET["clan"];
	}
	else {
		die("No clan ID specified.");
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

$query = $DB->query("SELECT
		c.tag,
		c.name,
		c.homepage,
		c.steamGroup,
		c.game,
		SUM(p.kills) AS kills,
		SUM(p.deaths) AS deaths,
		COUNT(p.playerId) AS nummembers,
		ROUND(AVG(p.skill)) AS avgskill
	FROM `".DB_PREFIX."_Clans` AS c
	LEFT JOIN `".DB_PREFIX."_Players` AS p
		ON p.clan = c.clanId
	WHERE c.clanId=".$DB->real_escape_string($clan)."
		AND p.hideranking = 0
	GROUP BY c.clanId
");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if ($query->num_rows != 1) {
	die("No such clan '$clan'.");
}

$clandata = $query->fetch_assoc();
$query->free();

$game = $clandata['game'];


$cl_name = str_replace(" ", "&nbsp;", htmlspecialchars($clandata["name"]));
$cl_tag  = str_replace(" ", "&nbsp;", htmlspecialchars($clandata["tag"]));
$cl_full = $cl_tag . " " . $cl_name;

// now get the clan members
$queryStr = "SELECT SQL_CALC_FOUND_ROWS
			playerId, lastName, skill, oldSkill, kills, deaths, active, isBot,
			IFNULL(kills/deaths, 0) AS kpd,
			(kills/" . $DB->real_escape_string($clandata["kills"]) . ") * 100 AS percent
		FROM `".DB_PREFIX."_Players`
		WHERE clan = ".$DB->real_escape_string($clan)."
			AND hideranking = 0
		ORDER BY ".$sort." ".$sortorder;

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
		$members['data'][] = $result;
	}
}
$query->free();

// query to get the total rows which would be fetched without the LIMIT
// works only if the $queryStr has SQL_CALC_FOUND_ROWS
$query = $DB->query("SELECT FOUND_ROWS() AS 'rows'");
$result = $query->fetch_assoc();
$members['pages'] = (int)ceil($result['rows']/50);
$query->free();

pageHeader(
	array($gamename, l("Clan Details"), $cl_full),
	array(
		$gamename => "index.php?game=$game",
		l("Clan Rankings") => "index.php?mode=clans&amp;game=$game",
		l("Clan Details")=>""
	),
	$clandata["name"]
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
	<h1><?php echo l('Clan Profile and Statistics Summary'); ?></h1>
	<table width="100%" border="1" cellspacing="0" cellpadding="2">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Home Page");?></th>
			<td><?php
				if ($url = getLink($clandata["homepage"])) {
					echo $url;
				}
				else {
					echo '(',l("Not specified"),")";
				}
				?>
			</td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Steam Group page");?></th>
			<td><?php
				if ($url = getLink($clandata["steamGroup"])) {
					echo $url;
				}
				else {
					echo '(',l("Not specified"),")";
				}
				?>
			</td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Number of Members"); ?></th>
			<td><?php echo $clandata["nummembers"]; ?></td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Avg. Member Points"); ?></th>
			<td><?php echo $clandata["avgskill"]; ?></td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Total Kills"); ?></th>
			<td><?php echo $clandata["kills"];?></td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Total Deaths"); ?></th>
			<td><?php echo $clandata["deaths"]; ?></td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Kills per Death"); ?></th>
			<td><?php
				if ($clandata["deaths"] != 0) {
					printf("%0.2f", $clandata["kills"] / $clandata["deaths"]);
				}
				else {
					echo "-";
				}
			?></td>
		</tr>
	</table>
	<h1><?php echo l('Members'); ?></h1>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<?php
		echo '<tr><td colspan="7" align="right">';
		if($members['pages'] > 1) {
			for($i=1;$i<=$members['pages'];$i++) {
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
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'lastName','sortorder'=>$newSort)); ?>">
					<?php echo l('Name'); ?>
				</a>
				<?php if($sort == "lastName") { ?>
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
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'kills','sortorder'=>$newSort)); ?>">
					<?php echo l('Kills'); ?>
				</a>
				<?php if($sort == "kills") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'percent','sortorder'=>$newSort)); ?>">
					<?php echo l('Contribution to Clan Kills'); ?>
				</a>
				<?php if($sort == "percent") { ?>
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
		if(!empty($members['data'])) {
			if($page > 1) {
				$rank = ($page - 1) * (50 + 1);
			}
			else {
				$rank = 1;
			}
			foreach($members['data'] as $k=>$entry) {
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
				echo makeSavePlayerName($entry['lastName']);
				echo '</a>';
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
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
				echo $entry['skill'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['kills'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo '<div class="percentBar" title="',number_format((int)$entry['percent'],0),'%"><div class="barContent" style="width:',number_format((int)$entry['percent'],0),'px"></div></div>',"\n";
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['deaths'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo number_format((int)$entry['kpd'],1);
				echo '</td>',"\n";

				echo '</tr>';
			}
			echo '<tr><td colspan="7" align="right">';
			if($members['pages'] > 1) {
				for($i=1;$i<=$members['pages'];$i++) {
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
			echo '<tr><td colspan="7">',l('Clan has no active members to display.'),'</td></tr>';
		}
		?>
	</table>
	<p style="text-align: right">
	    <b><?php echo l('Admin Options'); ?>:</b>
	    <a href="<?php echo "index.php?mode=admin&amp;task=toolsEditdetails&amp;clanId=$clan"; ?>"><?php echo l('Edit Clan Details'); ?></a>
	</p>
	</div>
</div>
