<?php
/**
 * roles overview file
 * display an overview about each team the game has.
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
$roles['data'] = array();
$roles['pages'] = array();

$page = 1;
if (isset($_GET["page"])) {
	$check = validateInput($_GET['page'],'digit');
	if($check === true) {
		$page = $_GET['page'];
	}
}
$sort = 'rolecount';
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

$queryStr = "SELECT IFNULL(r.name, ecr.role) AS name,
			COUNT(ecr.id) AS rolecount,
			r.roleId,
			r.code
		FROM `".DB_PREFIX."_Events_ChangeRole` AS ecr
		LEFT JOIN `".DB_PREFIX."_Roles` AS r
			ON ecr.role = r.code
		LEFT JOIN `".DB_PREFIX."_Servers` AS s
			ON s.serverId = ecr.serverId
		WHERE r.game = '".$DB->real_escape_string($game)."'
			AND s.game = '".$DB->real_escape_string($game)."'
			AND (r.hidden <>'1' OR r.hidden IS NULL)
		GROUP BY ecr.role
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
		$roles['data'][] = $result;
	}
}

// get the max count for pagination
$query = $DB->query("SELECT FOUND_ROWS() AS 'rows'");
$result = $query->fetch_assoc();
$roles['pages'] = (int)ceil($result['rows']/50);
$query->free();


pageHeader(
	array($gamename, l("Role Statistics")),
	array($gamename=>"index.php?game=$game", l("Role Statistics")=>"")
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
	<h1><?php echo l("Role Statistics"); ?></h1>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th width="50">&nbsp;</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'name','sortorder'=>$newSort)); ?>">
					<?php echo l('Role'); ?>
				</a>
				<?php if($sort == "name") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'rolecount','sortorder'=>$newSort)); ?>">
					<?php echo l('Selected'); ?>
				</a>
				<?php if($sort == "rolecount") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
		</tr>
		<?php
		if(!empty($roles['data'])) {
			foreach($roles['data'] as $k=>$entry) {
				toggleRowClass($rcol);

				echo '<tr>',"\n";

				echo '<td class="',$rcol,'">';
				echo '<img src="hlstatsimg/roles/',$game,'/',$entry['code'],'.png" alt="',$entry['name'],'" />';
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['name'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['rolecount'];
				echo '</td>',"\n";

				echo '</tr>';
			}
			echo '<tr><td colspan="3" align="right">';
				if($roles['pages'] > 1) {
					for($i=1;$i<=$roles['pages'];$i++) {
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
			echo '<tr><td colspan="3">',l('No data recorded'),'</td></tr>';
		}
		?>
	</table>
	</div>
</div>
