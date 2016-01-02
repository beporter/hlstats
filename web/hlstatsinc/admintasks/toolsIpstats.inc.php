<?php
/**
 * view ip/host stats
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

// the players for the selected country
$hostStats['data'] = array();
$hostStats['pages'] = array();

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


// query to get the data from the db with the given options
$queryStr = "SELECT SQL_CALC_FOUND_ROWS
			ec.country,
			ec.countryCode,
			ec.ipAddress,
			COUNT(ec.id) AS obj_count
		FROM `".DB_PREFIX."_Events_Connects` AS ec
		LEFT JOIN `".DB_PREFIX."_Servers` AS s
			ON s.serverId = ec.serverId
		GROUP BY ec.ipAddress
		ORDER BY ".$sort." ".$sortorder."";

// calculate the limit
if($page === 1) {
	$queryStr .=" LIMIT 0,50";
}
else {
	$start = 50*($page-1);
	$queryStr .=" LIMIT ".$start.",50";
}

$query = $DB->error$queryStr);
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$hostStats['data'][] = $result;
	}
}
$query->free();

// query to get the total rows which would be fetched without the LIMIT
// works only if the $queryStr has SQL_CALC_FOUND_ROWS
$query = $DB->error"SELECT FOUND_ROWS() AS 'rows'");
$result = $query->fetch_assoc();
$hostStats['pages'] = (int)ceil($result['rows']/50);
$query->free();

pageHeader(array(l("Admin"),l('Host Statistics')), array(l("Admin")=>"index.php?mode=admin",l('Host Statistics')=>''));

?>
<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="<?php echo "index.php?mode=admin"; ?>"><?php echo l('Back to admin overview'); ?></a>
			</li>
		</ul>
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo l('Host Statistics'); ?></h1>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'countryCode','sortorder'=>$newSort)); ?>">
					<?php echo l('Country'); ?>
				</a>
				<?php if($sort == "countryCode") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'ipAddress','sortorder'=>$newSort)); ?>">
					<?php echo l('IP Address'); ?>
				</a>
				<?php if($sort == "ipAddress") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<a href="index.php?<?php echo makeQueryString(array('sort'=>'obj_count','sortorder'=>$newSort)); ?>">
					<?php echo l('Connects'); ?>
				</a>
				<?php if($sort == "obj_count") { ?>
				<img src="hlstatsimg/<?php echo $sortorder; ?>.gif" alt="Sorting" width="7" height="7" />
				<?php } ?>
			</th>

		</tr>
	<?php
		if(!empty($hostStats['data'])) {
			if($page > 1) {
				$rank = ($page - 1) * (50 + 1);
			}
			else {
				$rank = 1;
			}
			foreach($hostStats['data'] as $k=>$entry) {
				toggleRowClass($rcol);

				echo '<tr>',"\n";

				echo '<td class="',$rcol,'">';
				echo '<img src="hlstatsimg/site/flag/'.strtolower($entry['countryCode']).'.png" alt="'.$entry['country'].'" title="'.$entry['country'].'" />&nbsp;';
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['ipAddress'].'</a>';
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['obj_count'];
				echo '</td>',"\n";

				echo '</tr>';
			}
			echo '<tr><td colspan="4" align="right">';
			if($hostStats['pages'] > 1) {
				for($i=1;$i<=$hostStats['pages'];$i++) {
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
			echo '<tr><td colspan="4">',l('No data recorded'),'</td></tr>';
		}
	?>
	</table>
	</div>
</div>
