<?php
/**
 * sites overview file
 *
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
 * + 2007 - 2013
 * +
 *
 * This program is free software is licensed under the
 * COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
 *
 * You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
 * along with this program; if not, visit http://hlstats-community.org/License.html
 *
 */

/**
 * check the get values
 */

$page = 1;
if (isset($_GET["page"])) {
	$check = validateInput($_GET['page'],'digit');
	if($check === true) {
		$page = 'page';
	}
}

$sort = 'siteName';
if (isset($_GET["sort"])) {
	$check = validateInput($_GET['sort'],'nospace');
	if($check === true) {
		$sort = 'sort';
	}
}

$newSort = "ASC";
$sortorder = 'DESC';
if (isset($_GET["sortorder"])) {
	$check = validateInput($_GET['sortorder'],'nospace');
	if($check === true) {
		$sortorder = 'sortorder';
	}

	if($_GET["sortorder"] == "ASC") {
		$newSort = "DESC";
	}
}



$_entriesPerPage = 10;
$sData = array('data' => '', 'pages' => '');

$queryStr = "SELECT SQL_CALC_FOUND_ROWS s.*
			FROM `".DB_PREFIX."_sites` as s
			WHERE s.valid = 1";
$queryStr .= " ORDER BY ";
if(!empty($sort) && !empty($sortorder)) {
	$queryStr .= " ".$sort." ".$sortorder."";
}

// calculate the limit
if($page === 1) {
	$queryStr .=" LIMIT 0,".$_entriesPerPage;
}
else {
	$start = $_entriesPerPage*($page-1);
	$queryStr .=" LIMIT ".$start.",".$_entriesPerPage;
}

$query = $DB->query($queryStr);
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$pl[$result['id']] = $result;
	}
	$sData['data'] = $pl;
}

// get the max count for pagination
$query = $DB->query("SELECT FOUND_ROWS() AS 'rows'");
$result = $query->fetch_assoc();
$sData['pages'] = (int)ceil($result['rows']/$_entriesPerPage);

?>

<h2>Sites</h2>
<table cellpadding="0" cellspacing="0">
	<tr>
		<th>
			<a href="index.php?<?php echo makeQueryString(array('sort'=>'siteName','sortorder'=>$newSort)); ?>">
				Name
				<?php if($sort == "siteName") {
					if($newSort == "ASC") {
						echo '<span class="icon blue" data-icon="|"></span>';
					} else {
						echo '<span class="icon blue" data-icon="~"></span>';
					}
				} ?>
			</a>
		</th>
		<th>
			<a href="index.php?<?php echo makeQueryString(array('sort'=>'game','sortorder'=>$newSort)); ?>">
				Game
				<?php if($sort == "game") {
					if($newSort == "ASC") {
						echo '<span class="icon blue" data-icon="|"></span>';
					} else {
						echo '<span class="icon blue" data-icon="~"></span>';
					}
				} ?>
			</a>
		</th>
		<th>Stats page</th>
	</tr>
<?php
if(!empty($sData['data'])) {
	foreach($sData['data'] as $site) {
?>
	<tr>
		<td><?php echo $site['siteName'];  ?></td>
		<td><?php echo $site['game'];  ?></td>
		<td><a href="<?php echo $site['siteURL']; ?>" target="_blank">Visit the site</a> <span class="icon small blue" data-icon="_"></span></td>
	</tr>
<?php
	}
}
?>
<tr>
	<td colspan="3" style="text-align: right;">
<?php
if($sData['pages'] > 1) {
	echo '<ul class="button-bar">';
	for($i=1;$i<=$sData['pages'];$i++) {
		if($page == ($i)) {
			echo "<li><a>[",$i,"]</a></li>";
		}
		else {
			echo "<li><a href='index.php?",makeQueryString(array('page'=>$i)),"'>",$i,"</a></li>";
		}
	}
	echo '</ul>';
}
?>
		</td>
	</tr>
</table>
