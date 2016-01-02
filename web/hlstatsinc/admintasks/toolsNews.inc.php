<?php
/**
 * news administration for front page
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

$return['status'] = false;
$return['msg'] = false;

// new one
if(isset($_POST['saveNews'])) {
	$subject = trim($_POST["subject"]);
	$subjectCheck = validateInput($subject,'text');

	$message = trim($_POST["message"]);
	$messageCheck = validateInput($message,'text');

	if(empty($messageCheck) || empty($subjectCheck)) {
		$return['msg'] = l('Please provide a subject and message');
		$return['status'] = "1";
	}
	else {
		$newsdate = date("Y-m-d H:i:s");
		$result = $DB->query("INSERT INTO ".DB_PREFIX."_News
							VALUES ('',
									'".$newsdate."',
									'".$DB->real_escape_string($adminObj->getUsername())."',
									'".$DB->real_escape_string($_POST["email"])."',
									'".$DB->real_escape_string($subject)."',
									'".$DB->real_escape_string($message)."')
							");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		$return['msg'] = l('News has been saved');
		$return['status'] = "2";
	}
}

// edit load
$post = false;
if(!empty($_GET['editpost']) || !empty($_GET['deletepost'])) {
	$postnr = 0;
	if(!empty($_GET['editpost'])) {
		$postnr = sanitize($_GET['editpost']);
	}
	elseif(!empty($_GET['deletepost'])) {
		$postnr = sanitize($_GET['deletepost']);
	}

	$check = validateInput($postnr,'digit');
	if(!empty($postnr) && $check === true) {
		$query = $DB->query("SELECT * FROM `".DB_PREFIX."_News`
						WHERE `id` = '".$DB->real_escape_string($postnr)."'");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		$post = $query->fetch_array();
		$query->free();
	}
}

// edit save
if(isset($_POST['editNews']) && !empty($_GET['editpost'])) {
	$newsID = $_GET['editpost'];

	$subject = trim($_POST["subject"]);
	$subjectCheck = validateInput($subject,'text');

	$message = trim($_POST["message"]);
	$messageCheck = validateInput($message,'text');

	if(empty($messageCheck) || empty($subjectCheck)) {
		$return['msg'] = l('Please provide a subject and message');
		$return['status'] = "1";
	}
	else {
		$newsdate = date("Y-m-d H:i:s");
		$result = $DB->query("UPDATE `".DB_PREFIX."_News`
								SET `date` = '".$newsdate."',
									`user` = '".$DB->real_escape_string($adminObj->getUsername())."',
									`email` = '".$DB->real_escape_string($_POST["email"])."',
									`subject` = '".$DB->real_escape_string($_POST["subject"])."',
									`message` = '".$DB->real_escape_string($_POST["message"])."'
								WHERE `id` = '".$DB->real_escape_string($newsID)."'
							");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		$return['msg'] = l('News has been saved');
		$return['status'] = "2";
	}
}

//delete
if(isset($_POST['deleteNews']) && !empty($_GET['deletepost'])) {
	$newsId = trim($_GET['deletepost']);
	$check = validateInput($newsId,'digit');

	if(!empty($newsId) && $check === true) {
		$query = $DB->query("DELETE FROM `".DB_PREFIX."_News`
								WHERE `id` = '".$DB->real_escape_string($newsId)."'");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		if($query !== false) {
			$return['msg'] = l('News item deleted');
			$return['status'] = "2";
		}
		else {
			$return['msg'] = l('News Item could not be delete');
			$return['status'] = "1";
		}
	}
}

// load existing news
$newsArray = false;
$query = $DB->query("SELECT * FROM `".DB_PREFIX."_News` ORDER BY `date` DESC");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$newsArray[] = $result;
	}
}

pageHeader(array(l("Admin"),l('News at Front page')), array(l("Admin")=>"index.php?mode=admin",l('News at Front page')=>''));
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
	<h1><?php echo l('News at Front page'); ?></h1>
	<p><?php echo l('Here you can write and edit the news which are displayed at the front page'); ?></p>
	<?php
	if(!empty($return)) {
		if($return['status'] === "1") {
			echo '<div class="error"><p>',$return['msg'],'</p></div>';
		}
		elseif($return['status'] === "2") {
			echo '<div class="success"><p>',$return['msg'],'</p></div>';
		}
	}
	if(!empty($post) && isset($_GET['editpost'])) {
	?>
	<form method="post" action="">
		<table border="0" cellpadding="2" cellspacing="0">
			<tr>
				<th width="100px">
					<?php echo l('Author'); ?>:
				</th>
				<td>
					<input type="text" disabled="disabled" name="author"
						value="<?php echo $adminObj->getUsername(); ?>" />
				</td>
			</tr>
			<tr>
				<th width="100px">
					<?php echo l('E-Mail'); ?>:
				</th>
				<td>
					<input type="text" name="email" value="<?php echo $post['email']; ?>" />
				</td>
			</tr>
			<tr>
				<th width="100px">
					<?php echo l('Subject'); ?>:
				</th>
				<td>
					<input type="text" name="subject" value="<?php echo $post['subject']; ?>" />
				</td>
			</tr>
			<tr>
				<th width="100px" valign="top">
					<?php echo l('Message'); ?>:
				</th>
				<td>
					<textarea name="message" cols="70" rows="6" /><?php echo $post['message']; ?></textarea>
				</td>
			</tr>
			<tr>
				<td width="100px">&nbsp;</td>
				<td>
					<button type="submit" title="<?php echo l('Update'); ?>" name="editNews">
						<?php echo l('Update'); ?>
					</button>
				</td>
			</tr>
		</table>
	</form>
	<?php } elseif(isset($_GET['deletepost']) && !empty($_GET['deletepost'])) { ?>
	<form action="" method="post">
		<?php echo l('Do you want to delete the following news item ?'); ?><br />
		<br />
		&#187; <i><?php echo l('Date'),'</i>: ',$post['date'],' <i>',l('Subject'),'</i>: ',$post['subject']; ?></i><br />
		<br />
		<button type="submit" title="<?php echo l('Delete'); ?>" name="deleteNews">
			<?php echo l('Delete'); ?>
		</button>
	</form>
	<?php } else { ?>
	<form method="post" action="">
		<table border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th width="100px">
					<?php echo l('Author'); ?>:
				</th>
				<td>
					<input type="text" disabled="disabled" name="author"
						value="<?php echo $adminObj->getUsername();?>" />
				</td>
			</tr>
			<tr>
				<th width="100px">
					<?php echo l('E-Mail'); ?>:
				</th>
				<td>
					<input type="text" name="email" value="" />
				</td>
			</tr>
			<tr>
				<th width="100px">
					<?php echo l('Subject'); ?>:
				</th>
				<td>
					<input type="text" name="subject" value="" />
				</td>
			</tr>
			<tr>
				<th width="100px" valign="top">
					<?php echo l('Message'); ?>:
				</th>
				<td>
					<textarea name="message" cols="70" rows="6" /></textarea>
				</td>
			</tr>
			<tr>
				<td width="100px">&nbsp;</td>
				<td>
					<button type="submit" title="<?php echo l('Save'); ?>" name="saveNews">
						<?php echo l('Save'); ?>
					</button>
				</td>
			</tr>
		</table>
	</form>
	<?php
	}
	if(!empty($newsArray)) { ?>
		<table cellpadding="2" cellspacing="0" border="1" width="100%">
			<tr>
				<th><?php echo l('Date'); ?></th>
				<th><?php echo l('Subject'); ?></th>
				<th><?php echo l('Author'); ?></th>
				<th><?php echo l('Delete'); ?></th>
			</tr>
			<?php
			foreach($newsArray as $entry) {
				echo '<tr>';

				echo '<td>',$entry['date'],'</td>';
				echo '<td><a href="index.php?mode=admin&amp;task=toolsNews&amp;editpost=',$entry['id'],'">',$entry['subject'],'</a></td>';
				echo '<td>',$entry['user'],'</td>';
				echo '<td><a href="index.php?mode=admin&amp;task=toolsNews&amp;deletepost=',$entry['id'],'">',l('Delete'),'</a></td>';

				echo '</tr>';
			}
			?>
		</table>
	<?php }	?>
	</div>
</div>
