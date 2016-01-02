<?php
/**
 * players overview file
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

require('lib/players.class.php');
$playersObj = new Players();

/**
 * check the get values
 */
if (isset($_GET["minkills"])) {
	$check = validateInput($_GET['minkills'],'digit');
	if($check === true) {
		$playersObj->setOption("minkills",$_GET['minkills']);
	}
}
if (isset($_GET["showToday"])) {
	$check = validateInput($_GET['showToday'],'digit');
	if($check === true) {
		$playersObj->setOption("showToday",$_GET['showToday']);
	}
}

if (isset($_GET["page"])) {
	$check = validateInput($_GET['page'],'digit');
	if($check === true) {
		$playersObj->setOption("page",$_GET['page']);
	}
}
if (isset($_GET["sort"])) {
	$check = validateInput($_GET['sort'],'nospace');
	if($check === true) {
		$playersObj->setOption("sort",$_GET['sort']);
	}
}
else {
	$playersObj->setOption("sort",'skill');
}

$newSort = "ASC";
if (isset($_GET["sortorder"])) {
	$check = validateInput($_GET['sortorder'],'nospace');
	if($check === true) {
		$playersObj->setOption("sortorder",$_GET['sortorder']);
	}

	if($_GET["sortorder"] == "ASC") {
		$newSort = "DESC";
	}
}
else {
	$playersObj->setOption("sortorder",'DESC');
}

# get the players overview
$pData = $playersObj->getPlayersOveriew();

?>

<h2>Players</h2>
<table cellpadding="0" cellspacing="0">
	<tr>
		<th>
			<a href="index.php?<?php echo makeQueryString(array('sort'=>'name','sortorder'=>$newSort)); ?>">
				Name
				<?php if($playersObj->getOption("sort") == "name") {
					if($newSort == "ASC") {
						echo '<span class="icon blue" data-icon="|"></span>';
					} else {
						echo '<span class="icon blue" data-icon="~"></span>';
					}
				} ?>
			</a>
		</th>
		<th>
			<a href="index.php?<?php echo makeQueryString(array('sort'=>'skill','sortorder'=>$newSort)); ?>">
				Skill
				<?php if($playersObj->getOption("sort") == "skill") {
					if($newSort == "ASC") {
						echo '<span class="icon blue" data-icon="|"></span>';
					} else {
						echo '<span class="icon blue" data-icon="~"></span>';
					}
				} ?>
			</a>
		</th>
		<th>
			<a href="index.php?<?php echo makeQueryString(array('sort'=>'kills','sortorder'=>$newSort)); ?>">
				Kills
				<?php if($playersObj->getOption("sort") == "kills") {
					if($newSort == "ASC") {
						echo '<span class="icon blue" data-icon="|"></span>';
					} else {
						echo '<span class="icon blue" data-icon="~"></span>';
					}
				} ?>
			</a>
		</th>
		<th>
			<a href="index.php?<?php echo makeQueryString(array('sort'=>'deaths','sortorder'=>$newSort)); ?>">
				Deaths
				<?php if($playersObj->getOption("sort") == "deaths") {
					if($newSort == "ASC") {
						echo '<span class="icon blue" data-icon="|"></span>';
					} else {
						echo '<span class="icon blue" data-icon="~"></span>';
					}
				} ?>
			</a>
		</th>
		<th>
			<a href="index.php?<?php echo makeQueryString(array('sort'=>'lastConnect','sortorder'=>$newSort)); ?>">
				Last time online
				<?php if($playersObj->getOption("sort") == "lastConnect") {
					if($newSort == "ASC") {
						echo '<span class="icon blue" data-icon="|"></span>';
					} else {
						echo '<span class="icon blue" data-icon="~"></span>';
					}
				} ?>
			</a>
		</th>
		<th>
			<a href="index.php?<?php echo makeQueryString(array('sort'=>'countryCode','sortorder'=>$newSort)); ?>">
				Country
				<?php if($playersObj->getOption("sort") == "countryCode") {
					if($newSort == "ASC") {
						echo '<span class="icon blue" data-icon="|"></span>';
					} else {
						echo '<span class="icon blue" data-icon="~"></span>';
					}
				} ?>
			</a>
		</th>
		<th>Stats profile</th>
		<th>Steam profile</th>
	</tr>

<?php
if(!empty($pData['data'])) {
	foreach($pData['data'] as $player) {
?>
	<tr>
		<td><?php echo $player['name'];  ?></td>
		<td><?php echo $player['skill'];  ?></td>
		<td><?php echo $player['kills'];  ?></td>
		<td><?php echo $player['deaths'];  ?></td>
		<td><?php echo $player['lastConnect'];  ?></td>
		<td><img src="img/flag/<?php echo $player['countryCode']; ?>.png" alt="<?php echo $player['country'];  ?>" title="<?php echo $player['country'];  ?>" /></td>
		<td><a href="<?php echo $player['profile'];  ?>" target="_blank">Player profile</a><span class="icon small blue" data-icon="_"></span></td>
		<td><?php echo $player['steamProfile'];  ?><span class="icon small blue" data-icon="_"></span></td>
	</tr>
<?php
	}
?>
	<tr>
	<td colspan="8" style="text-align: right;">
<?php
if($pData['pages'] > 1) {
	echo '<ul class="button-bar">';
	for($i=1;$i<=$pData['pages'];$i++) {
		if($playersObj->getOption('page') == ($i)) {
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
<?php
} else {

}
?>
</table>
