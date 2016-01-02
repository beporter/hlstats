<?php
/**
 * manage the roles
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

$gc = false;
$check = false;
$return = false;

// get the game, without it we can not do anyting
if(isset($_GET['gc'])) {
	$gc = trim($_GET['gc']);
	$check = validateInput($gc,'nospace');
	if($check === true) {
		// load the game info
		$query = $DB->query("SELECT name
							FROM `".DB_PREFIX."_Games`
							WHERE code = '".$DB->real_escape_string($gc)."'");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		if($query->num_rows > 0) {
			$result = $query->fetch_assoc();
			$gName = $result['name'];
		}
		$query->free();
	}
}

// do we have a valid gc code?
if(empty($gc) || empty($check)) {
	exit('No game code given');
}


if(isset($_POST['sub']['saveRoles'])) {

	// del
	if(!empty($_POST['del'])) {
		foreach($_POST['del'] as $k=>$v) {
			$query = $DB->query("DELETE FROM `".DB_PREFIX."_Roles`
									WHERE `roleId` = '".$DB->real_escape_string($k)."'");
			if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
			unset($_POST['code'][$k]);
		}
	}

	// update
	if(!empty($_POST['code'])) {
		foreach($_POST['code'] as $k=>$v) {
			$c = trim($v);
			if(!empty($c)) {
				$name = trim($_POST['name'][$k]);

				$hide = 0;
				if(isset($_POST['hidden'][$k])) $hide = 1;

				$query = $DB->query("UPDATE `".DB_PREFIX."_Roles`
										SET `code` = '".$DB->real_escape_string($c)."',
											`name` = '".$DB->real_escape_string($name)."',
											`hidden` = '".$DB->real_escape_string($hide)."'
										WHERE `roleId` = '".$DB->real_escape_string($k)."'");
				if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
				if($query === false) {
					$return['status'] = "1";
					$return['msg'] = l('Data could not be saved');
				}
			}
		}
	}

	// add
	if(isset($_POST['newcode'])) {
		$newOne = trim($_POST['newcode']);
		if(!empty($newOne)) {
			$name = trim($_POST['newname']);

			$hide = 0;
			if(isset($_POST['newhidden'])) $hide = 1;

			$query = $DB->query("INSERT INTO `".DB_PREFIX."_Roles`
									SET `code` = '".$DB->real_escape_string($newOne)."',
										`name` = '".$DB->real_escape_string($name)."',
										`hidden` = '".$DB->real_escape_string($hide)."',
										`game` = '".$DB->real_escape_string($gc)."'");
			if($query === false) {
				$return['status'] = "1";
				$return['msg'] = l('Data could not be saved');
			}
		}
	}

	if($return === false) {
		header('Location: index.php?mode=admin&task=roles&gc='.$gc.'#roles');
	}
}

// get the roles
$roles = false;
$query = $DB->query("SELECT roleId, code, name, hidden
						FROM `".DB_PREFIX."_Roles`
						WHERE game='".$DB->real_escape_string($gc)."'
						ORDER BY code ASC");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$roles[] = $result;
	}
}

$rcol = "row-dark";

pageHeader(array(l("Admin"),l('Roles')), array(l("Admin")=>"index.php?mode=admin",l('Roles')=>''));
?>
<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="index.php?mode=admin&task=gameoverview&code=<?php echo $gc; ?>"><?php echo l('Back to game overview'); ?></a>
			</li>
			<li>
				<a href="index.php?mode=admin"><?php echo l('Back to admin overview'); ?></a>
			</li>
		</ul>
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo l('Roles for'); ?>: <?php echo $gName; ?></h1>
	<p>
		<?php echo l("You can specify descriptive names for each game's role codes"); ?>
	</p>
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
	<a name="roles"></a>
	<form method="post" action="">
		<table cellpadding="2" cellspacing="0" border="0" width="100%">
			<tr>
				<th><?php echo l('Role Code'); ?></th>
				<th><?php echo l('Role Name'); ?></th>
				<th><?php echo l('Hide Role'); ?></th>
				<th><?php echo l('Delete'); ?></th>
			</tr>
		<?php if(!empty($roles)) {
			foreach($roles as $t) {
		?>
			<tr>
				<td class="<?php echo toggleRowClass($rcol); ?>">
					<input type="text" name="code[<?php echo $t['roleId']; ?>]" value="<?php echo $t['code']; ?>" />
				</td>
				<td class="<?php echo $rcol; ?>">
					<input type="text" name="name[<?php echo $t['roleId']; ?>]" value="<?php echo $t['name']; ?>" />
				</td>
				<td class="<?php echo $rcol; ?>">
					<?php ($t['hidden'] == "1") ? $checked='checked="1"':$checked=''; ?>
					<input type="checkbox" name="hidden[<?php echo $t['roleId']; ?>]" value="1" <?php echo $checked; ?> />
				</td>
				<td class="<?php echo $rcol; ?>">
					<input type="checkbox" name="del[<?php echo $t['roleId']; ?>]" value="1" />
				</td>
			</tr>
		<?php
			}
		}
		?>
			<tr>
				<td class="<?php echo toggleRowClass($rcol); ?>">
					<?php echo l('new'); ?> <input type="text" name="newcode" value="" />
				</td>
				<td class="<?php echo $rcol; ?>">
					<input type="text" name="newname" value="" />
				</td>
				<td colspan="2" class="<?php echo $rcol; ?>">
					<input type="checkbox" name="newhidden" value="1" />
				</td>
			</tr>
			<tr>
				<td colspan="4" align="right">
					<button type="submit" name="sub[saveRoles]" title="<?php echo l('Save'); ?>">
						<?php echo l('Save'); ?>
					</button>
				</td>
			</tr>
		</table>
	</form>
	</div>
</div>
