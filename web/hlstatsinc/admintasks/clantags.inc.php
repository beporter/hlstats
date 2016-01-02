<?php
/**
 * manage the clan tags
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

$return = false;
if(isset($_POST['sub']['patterns'])) {

	if(!empty($_POST['del'])) {
		foreach($_POST['del'] as $k=>$v) {
			$query = $DB->query("DELETE FROM `".DB_PREFIX."_ClanTags`
									WHERE `id` = '".$DB->real_escape_string($k)."'");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			unset($_POST['pat'][$k]);
		}
	}

	if(!empty($_POST['pat']) && !empty($_POST['sel'])) {
		// update given patterns
		foreach($_POST['pat'] as $k=>$v) {
			$v = trim($v);
			if(!empty($v) && isset($_POST['sel'][$k])) {
				$query = $DB->query("UPDATE `".DB_PREFIX."_ClanTags`
										SET `pattern` = '".$DB->real_escape_string($v)."',
											`position` = '".$DB->real_escape_string($_POST['sel'][$k])."'
										WHERE `id` = '".$DB->real_escape_string($k)."'");
				if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
				if($query === false) {
					$return['status'] = "1";
					$return['msg'] = l('Data could not be saved');
				}
			}
		}
	}

	if(isset($_POST['newpat'])) {
		$newOne = trim($_POST['newpat']);
		if(!empty($newOne) && !empty($_POST['newsel'])) {
			$query = $DB->query("INSERT INTO `".DB_PREFIX."_ClanTags`
									SET `pattern` = '".$DB->real_escape_string($newOne)."',
										`position` = '".$DB->real_escape_string($_POST['newsel'])."'");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			if($query === false) {
				$return['status'] = "1";
				$return['msg'] = l('Data could not be saved');
			}
		}
	}

	if($return === false) {
		header('Location: index.php?mode=admin&task=clantags#tags');
	}

}

$patterns = false;
// get the patterns
$query = $DB->query("SELECT id,pattern,position
		FROM `".DB_PREFIX."_ClanTags`
		ORDER BY position, pattern, id");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$patterns[] = $result;
	}
}

pageHeader(array(l("Admin"),l('Clan Tag Patterns')), array(l("Admin")=>"index.php?mode=admin",l('Clan Tag Patterns')=>''));
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
	<h1><?php echo l('Clan Tag Patterns'); ?></h1>
	<p>
		<?php echo l("Here you can define the patterns used to determine what clan a player is in. These patterns are applied to players' names when they connect or change name"); ?>.
	</p>
	<p>
		<h2><?php echo l("Special characters in the pattern"); ?></h2>
		<table border="1" cellspacing="0" cellpadding="4">
			<tr>
				<th><?php echo l('Character'); ?></th>
				<th><?php echo l('Description'); ?></th>
			</tr>

			<tr>
				<td><tt>A</tt></td>
				<td><?php echo l('Matches one character. Character is required'); ?></td>
			</tr>

			<tr>
				<td><tt>X</tt></td>
				<td><?php echo l('Matches zero or one characters. Character is optional'); ?></td>
			</tr>

			<tr>
				<td><tt>a</tt></td>
				<td><?php echo l('Matches literal A or a'); ?></td>
			</tr>

			<tr>
				<td><tt>x</tt></td>
				<td><?php echo l('Matches literal X or x'); ?></td>
			</tr>
		</table>
	</p>
	<p>
		<h2><?php echo l('Example patterns'); ?></h2>
		<table border="1" cellspacing="0" cellpadding="4">
			<tr>
				<th><?php echo l('Pattern'); ?></th>
				<th><?php echo l('Description'); ?></th>
				<th><?php echo l('Example'); ?></th>
			</tr>
			<tr>
				<td><tt>[AXXXXX]</tt></td>
				<td><?php echo l('Matches 1 to 6 characters inside square braces'); ?></td>
				<td><tt>[ZOOM]Player</tt></td>
			</tr>

			<tr>
				<td><tt>{AAXX}</tt></td>
				<td><?php echo l('Matches 2 to 4 characters inside curly braces'); ?></td>
				<td><tt>{S3G}Player</tt></td>
			</tr>

			<tr>
				<td><tt>rex>></tt></td>
				<td><?php echo l('Matches the string rex>>, REX>>, etc.'); ?></td>
				<td><tt>REX>>Tyranno</tt></td>
			</tr>
		</table>
	</p>
	<p>
		<?php echo l('Avoid adding patterns to the database that are too generic. Always ensure you have at least one literal (non-special) character in the pattern -- for example if you were to add the pattern "AXXA", it would match any player with 2 or more letters in their name'); ?>!<br />
		<br />
		<?php echo l("The Match Position field sets which end of the player's name the clan tag is allowed to appear"); ?>.
	</p>
	<a name="tags"></a>
	<?php
		if(!empty($return)) {
			if($return['status'] === "1") {
				echo '<div class="error">',$return['msg'],'</div>';
			}
			elseif($return['status'] === "2") {
				echo '<div class="success">',$return['msg'],'</div>';
			}
		}
	?>
	<?php if(!empty($patterns)) { ?>
	<form method="post" action="">
	<table cellpadding="2" cellspacing="0" border="0" width="100%">
		<tr>
			<th><?php echo l('Pattern'); ?></th>
			<th><?php echo l('Match Position'); ?></th>
			<th><?php echo l('Delete'); ?></th>
		</tr>
	<?php
			foreach($patterns as $pat) {
				echo '<tr>';

				echo '<td class="',toggleRowClass($rcol),'"><input type="text" size="30" name="pat[',$pat['id'],']" value="',$pat['pattern'],'"/></td>';

				echo '<td class="',$rcol,'">';
				echo '<select name="sel[',$pat['id'],']">';
					$selected = '';
					if($pat['position'] == "EITHER") $selected = 'selected="1"';
					echo '<option ',$selected,' value="EITHER">',l('EITHER'),'</option>';
					$selected = '';
					if($pat['position'] == "START") $selected = 'selected="1"';
					echo '<option ',$selected,' value="START">',l('START'),'</option>';
					$selected = '';
					if($pat['position'] == "END") $selected = 'selected="1"';
					echo '<option ',$selected,' value="END">',l('END'),'</option>';
				echo '</select>';
				echo '</td>';

				echo '<td class="',$rcol,'"><input type="checkbox" name="del[',$pat['id'],']" value="yes" /></td>';

				echo '</tr>';
			}
	?>
		<tr>
			<td class="<?php echo toggleRowClass($rcol); ?>">
				<?php echo l('new'); ?> <input type="text" size="30" name="newpat" value="" />
			</td>
			<td colspan="2" class="<?php echo ($rcol); ?>">
				<select name="newsel">
					<option value="EITHER"><?php echo l('EITHER'); ?></option>
					<option  value="START"><?php echo l('START'); ?></option>
					<option  value="END"><?php echo l('END'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="right">
				<button type="submit" name="sub[patterns]" title="<?php echo l('Save'); ?>">
					<?php echo l('Save'); ?>
				</button>
			</td>
		</tr>
	</table>
	</form>
	<?php } ?>
	</div>
</div>
