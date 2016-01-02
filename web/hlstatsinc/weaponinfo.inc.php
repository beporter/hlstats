<?php
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
$players['data'] = array();
$players['pages'] = array();

$weapon = false;
if(!empty($_GET["weapon"])) {
	if(validateInput($_GET["weapon"],'nospace') === true) {
		$weapon = $_GET["weapon"];
	}
	else {
		die("No weapon specified.");
	}
}

$page = 1;
if (isset($_GET["page"])) {
	$check = validateInput($_GET['page'],'digit');
	if($check === true) {
		$page = $_GET['page'];
	}
}
$sort = 'frags';
if (isset($_GET["sort"])) {
	$check = validateInput($_GET['sort'],'nospace');
	if($check === true) {
		$sort = $_GET['sort'];
	}
}

$newSort = "ASC";
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


$query = $DB->query("SELECT name FROM `".DB_PREFIX."_Weapons`
				WHERE code = '".$DB->real_escape_string($weapon)."'
				AND game = '".$DB->real_escape_string($game)."'");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if ($query->num_rows != 1) {
	$wep_name = ucfirst($weapon);
}
else {
	$result = $query->fetch_assoc();
	$wep_name = $result["name"];
}
$query->free();

// get the weapon info
$queryStr = "SELECT SQL_CALC_FOUND_ROWS
	ef.killerId,
	p.lastName AS killerName,
	p.active,
	p.isBot,
	COUNT(ef.weapon) AS frags
FROM `".DB_PREFIX."_Events_Frags` AS ef
LEFT JOIN `".DB_PREFIX."_Players` AS p
	ON p.playerId = ef.killerId
WHERE ef.weapon = '".$DB->real_escape_string($weapon)."'
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
		$players['data'][] = $result;
	}
}

// get the max count for pagination
$query = $DB->query("SELECT FOUND_ROWS() AS 'rows'");
$result = $query->fetch_assoc();
$players['pages'] = (int)ceil($result['rows']/50);
$query->free();

$query = $DB->query($queryStr);

// get the total kills
$queryCount = $DB->query(" SELECT
		COUNT(DISTINCT ef.killerId) AS wc,
		SUM(ef.weapon = '".$DB->real_escape_string($weapon)."') AS tc
	FROM `".DB_PREFIX."_Events_Frags` AS ef
	LEFT JOIN `".DB_PREFIX."_Players` AS p
		ON p.playerId = ef.killerId
	WHERE
		ef.weapon = '".$DB->real_escape_string($weapon)."'
		AND p.game = '".$DB->real_escape_string($game)."'
		AND p.hideranking = 0
");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
$result = $queryCount->fetch_assoc();
$numitems = $result['wc'];
$totalkills = $result['tc'];
$queryCount->free();

pageHeader(
	array($gamename, l("Weapon Details"), htmlspecialchars($wep_name)),
	array(
		$gamename => "index.php?game=$game",
		l("Weapon Statistics") => "index.php?mode=weapons&amp;game=$game",
		l("Weapon Details")=>""
	)
);
?>

<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="<?php echo "index.php?game=$game&amp;mode=weapons"; ?>"><?php echo l('Back to Weapon Statistics'); ?></a>
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
	<h1><?php echo l("Weapon Details"); ?> |
		<?php echo l("From a total of"); ?> <b><?php echo intval($totalkills); ?></b> <?php echo l('kills'); ?>
		(<?php echo l('Last'); ?> <?php echo $g_options['DELETEDAYS']; ?> <?php echo l('Days'); ?>)
	</h1>
	<?php
		$wepImg = "hlstatsimg/weapons/".$game."/".$weapon.".png";
		if(file_exists("hlstatsimg/weapons/".$game."/".$weapon."-big.png")) {
			$wepImg = "hlstatsimg/weapons/".$game."/".$weapon."-big.png";
		}
	?>
	<img src="<?php echo $wepImg; ?>" alt="<?php echo $wep_name; ?>" title="<?php echo $wep_name; ?>" border="0" /><br />
	<small><?php echo $wep_name; ?></small><br />
	<br />
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th class="<?php echo $rcol; ?>"><?php echo l('Rank'); ?></th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'killerName','sortorder'=>$newSort)); ?>">
					<?php echo l('Player'); ?>
				</a>
				<?php if($sort == "killerName") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'frags','sortorder'=>$newSort)); ?>">
					<?php echo $weapon,' ' ,l('Kills'); ?>
				</a>
				<?php if($sort == "frags") { ?>
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
				echo '&nbsp;<a href="index.php?mode=playerinfo&amp;player=',$entry['killerId'],'">';
				echo makeSavePlayerName($entry['killerName']);
				echo '</a>';
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['frags'];
				echo '</td>',"\n";

				echo '</tr>';
			}
			echo '<tr><td colspan="6" align="right">';
				if($players['pages'] > 1) {
					for($i=1;$i<=$players['pages'];$i++) {
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
		}
		else {
			echo '<tr><td colspan="6">',l('No data recorded'),'</td></tr>';
		}
	?>
	</table>
	</div>
</div>
