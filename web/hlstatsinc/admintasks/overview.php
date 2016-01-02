<?php
/**
 * admin overview file
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

$gameList = false;
// get the games
$query = $DB->query("SELECT code,name FROM `".DB_PREFIX."_Games`
					ORDER BY name ASC");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$gameList[] = $result;
	}
}

pageHeader(array(l("Admin")), array(l("Admin")=>""));
?>
<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="<?php echo "index.php?mode=admin&task=options"; ?>"><?php echo l('HLStats Options'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=admin&task=adminusers"; ?>"><?php echo l('Admin Users'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=admin&task=games"; ?>"><?php echo l('Gamesupport'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=admin&task=clantags"; ?>"><?php echo l('Clan Tag Patterns'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=admin&task=plugins"; ?>"><?php echo l('Server Plugins'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php?mode=admin&task=worldstats"; ?>"><?php echo l('Worldstats'); ?></a>
			</li>
			<li>
				<a href="<?php echo "index.php"; ?>"><?php echo l('Back to game statistics'); ?></a>
			</li>
		</ul>
	</div>
	<h1><?php echo l('Games'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<?php
			if(!empty($gameList)) {
				foreach($gameList as $g) {
			?>
				<li>
					<a href="index.php?mode=admin&amp;task=gameoverview&amp;code=<?php echo $g['code']; ?>"><?php echo $g['name']; ?></a>
				</li>
			<?php
				}
			} else { ?>
			<li>
				<a href="<?php echo "index.php?mode=admin&task=games"; ?>"><?php echo l('No games available'); ?></a>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo l('Overview'); ?></h1>
	<h2><?php echo l('Tools'); ?></h2>
	<ul>
		<li>
			<a href="index.php?mode=admin&amp;task=toolsEditdetails"><?php echo l('Edit Player or Clan Details'); ?></a><br />
			&#187; <span class="small"><?php echo l("Edit a player or clan's profile information."); ?></span>
		</li>
		<li>
			<a href="index.php?mode=admin&amp;task=toolsAdminevents"><?php echo l('Admin-Event History'); ?></a><br />
			&#187; <span class="small"><?php echo l("View event history of logged Rcon commands and Admin Mod messages."); ?></span>
		</li>
		<li>
			<a href="index.php?mode=admin&amp;task=toolsIpstats"><?php echo l('Host Statistics'); ?></a><br />
			&#187; <span class="small"><?php echo l("See which ISPs your players are using."); ?></span>
		</li>
		<li>
			<a href="index.php?mode=admin&amp;task=toolsOptimize"><?php echo l('Optimize Database'); ?></a><br />
			&#187; <span class="small"><?php echo l("This operation tells the MySQL server to clean up the database tables, optimizing them for better performance. It is recommended that you run this at least once a month."); ?></span>
		</li>
		<li>
			<a href="index.php?mode=admin&amp;task=toolsReset"><?php echo l('Reset Statistics'); ?></a><br />
			&#187; <span class="small"><?php echo l("Delete all players, clans and events from the database."); ?></span>
		</li>
		<li>
			<a href="index.php?mode=admin&amp;task=toolsNews"><?php echo l('News at Front page'); ?></a><br />
			&#187; <span class="small"><?php echo l("Write news to the front page."); ?></span>
		</li>
		<li>
			<a href="index.php?mode=admin&amp;task=toolsSigClear"><?php echo l('Signature cleanup'); ?></a><br />
			&#187; <span class="small"><?php echo l("Remove old signatures."); ?></span>
		</li>
	</ul>
	</div>
</div>
