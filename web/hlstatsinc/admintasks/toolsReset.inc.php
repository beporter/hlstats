<?php
/**
 * reset the complete HLStats database data
 * games and settings will be saved.
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

pageHeader(array(l("Admin"),l('Reset Statistics')), array(l("Admin")=>"index.php?mode=admin",l('Reset Statistics')=>''));
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
	<h1><?php echo l('Reset Statistics'); ?></h1>
	<?php
		if (isset($_POST['confirm'])) {
			$query = "SHOW TABLES LIKE '".DB_PREFIX."_Events_%'";

			$query = $DB->query($query);
			if ($query->num_rows < 1) die("Fatal error: No events tables found with query:<p><pre>$query</pre><p>There may be something wrong with your hlstats database or your version of MySQL.");

			while (list($table) = $query->fetch_array()) {
				$dbtables[] = $table;
			}

			array_push($dbtables,
				DB_PREFIX."_Clans",
				DB_PREFIX."_PlayerNames",
				DB_PREFIX."_PlayerUniqueIds",
				DB_PREFIX."_Players"
			);

			echo "<ul>\n";
			foreach ($dbtables as $dbt) {
				echo "<li>$dbt ... ";
				if ($DB->query("TRUNCATE TABLE `$dbt`")) {
					if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
					echo "OK\n";
				}
				else {
					$DB->query("DELETE FROM `$dbt`");
					if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
					echo "OK\n";
				}
			}

			echo "<li>Clearing awards ... ";
			$DB->query("UPDATE `".DB_PREFIX."_Awards` SET d_winner_id=NULL, d_winner_count=NULL");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			$DB->query("TRUNCATE TABLE `".DB_PREFIX."_Awards_History`");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			echo "OK\n";

			echo "</ul>\n";

			echo l("Done"),"<p>";

			// add a last reset row into hlstats_options
			$DB->query("UPDATE `".DB_PREFIX."_Options` SET value = '".time()."'
							WHERE `keyname` = 'reset_date'");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			$g_options['reset_date'] = date("d.m.Y");
		}
		else {
			// create last reset date
			if($g_options['reset_date'] == "0") {
				$g_options['reset_date'] = "Never";
			}
			else {
				$g_options['reset_date'] = date("d.m.Y",$g_options['reset_date']);
			}
		}
		?>
	<form method="post" action="">
		<table width="100%" border="0" cellspacing="1" cellpadding="10">
			<tr>
				<td>
					<?php echo l('Are you sure you want to reset all statistics? All players, clans and events will be deleted from the database'); ?>.
					<?php echo l('(All other admin settings will be retained)'); ?><br />
					<br />
					<p class="info"><?php echo l('Note'); ?>:<br />
						<?php echo l('You should kill'); ?> <b>hlstats.pl</b>
						<?php echo l('before resetting the stats.'); ?>.
					</p>
					<input type="hidden" name="confirm" value="1">
					<center>
						<button type="submit" title="<?php echo l('Reset Stats'); ?>">
							<?php echo l('Reset Stats'); ?>
						</button>
					</center>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo l('The last reset was'); ?>: <b><?php echo $g_options['reset_date']; ?></b>
				</td>
			</tr>
		</table>
	</form>
	</div>
</div>
