<?php
/**
 * optimze HLStats database tables
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

$optimize = false;
$analyze = false;

$upgrade = false;
if(!empty($_GET['upgrade'])) {
	if($_GET['upgrade'] == "yes") {
		$upgrade = true;
	}
}

pageHeader(array(l("Admin"),l('Optimize Database')), array(l("Admin")=>"index.php?mode=admin",l('Optimize Database')=>''));
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
	<h1><?php echo l('Optimize Database'); ?></h1>
	<p>
		<a href="index.php?mode=admin&amp;task=toolsOptimize&amp;upgrade=yes"><?php echo l('Click here'); ?></a> <?php echo l('if you get "table handler does not support check/repair"'); ?></a>
	</p>
	<?php
		if ($upgrade === true) {
			$result = $DB->query("SHOW TABLES");

			echo "Upgrading all tables to MyISAM format:<ul>\n";
			flush();
			while (list($table) = $result->fetch_array()) {
				echo "<li>$table ... ";
				$DB->query("ALTER TABLE $table TYPE=MYISAM");
				echo "OK\n";
				flush();
			}
			echo "</ul>\n";

			echo "Done.<p>";
		} else {


			$result = $DB->query("SHOW TABLES");
			$dbtables = '';

			while (list($table) = $result->fetch_array()) {
				if ($dbtables) $dbtables .= ", ";
				$dbtables .= $table;
			}

			$query = $DB->query("OPTIMIZE TABLE $dbtables");
			if($query->num_rows > 0) {
				while($result = $query->fetch_assoc()) {
					$optimize[] = $result;
				}
			}
			unset($result);
			$query->free();

			$query = $DB->query("OPTIMIZE TABLE $dbtables");
			if($query->num_rows > 0) {
				while($result = $query->fetch_assoc()) {
					$analyze[] = $result;
				}
			}
			unset($result);
			$query->free();
	?>
	<h2><?php echo l('Optimizing tables...'); ?></h2>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th class="<?php echo $rcol; ?>">
				<?php echo l('Table'); ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<?php echo l('Operation'); ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<?php echo l('Msg. Type'); ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<?php echo l('Message'); ?>
			</th>
		</tr>
		<?php
		if(!empty($optimize)) {
			foreach($optimize as $k=>$entry) {
				toggleRowClass($rcol);

				echo '<tr>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['Table'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['Op'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['Msg_type'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['Msg_text'];
				echo '</td>',"\n";

				echo '</tr>';
			}
		}
		else {
			echo '<tr><td colspan="5">',l('No data available'),'</td></tr>';
		}
		?>
	</table>
	<h2><?php echo l('Analyzing tables...'); ?></h2>
	<table cellpadding="0" cellspacing="0" border="1" width="100%">
		<tr>
			<th class="<?php echo $rcol; ?>">
				<?php echo l('Table'); ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<?php echo l('Operation'); ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<?php echo l('Msg. Type'); ?>
			</th>
			<th class="<?php echo $rcol; ?>">
				<?php echo l('Message'); ?>
			</th>
		</tr>
		<?php
		if(!empty($analyze)) {
			foreach($analyze as $k=>$entry) {
				toggleRowClass($rcol);

				echo '<tr>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['Table'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['Op'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['Msg_type'];
				echo '</td>',"\n";

				echo '<td class="',$rcol,'">';
				echo $entry['Msg_text'];
				echo '</td>',"\n";

				echo '</tr>';
			}
		}
		else {
			echo '<tr><td colspan="5">',l('No data available'),'</td></tr>';
		}
		?>
	</table>
	<?php } ?>
	</div>
</div>
