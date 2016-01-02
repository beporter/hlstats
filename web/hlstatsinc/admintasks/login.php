<?php
/**
 * login to admin area
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

if(isset($_POST['sub']['auth'])) {
	if(isset($_POST['login']['username']) && isset($_POST['login']['pass'])) {
		$username = trim($_POST['login']['username']);
		$pass = trim($_POST['login']['pass']);
		$check = validateInput($username,'nospace');
		$check1 = validateInput($pass,'nospace');
		if($check === true && $check1 === true) {
			$do = $adminObj->doLogin($username,$pass);
			if($do === true) {
				$return['status'] = "3";
				$return['msg'] = l('Login successfull');
				header('Location: index.php?mode=admin');
			}
			else {
				$return['status'] = "2";
				$return['msg'] = l('Invalid auth data');
			}
		}
		else {
			$return['status'] = "1";
			$return['msg'] = l('Please provide authentication data');
		}
	}
}

pageHeader(array(l("Admin")), array(l("Admin")=>""));
?>

<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="<?php echo "index.php"; ?>"><?php echo l('Back to game statistics'); ?></a>
			</li>
		</ul>
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo l('Authorisation Required'); ?></h1>
	<?php
	if(!empty($return['status'])) {
		if($return['status'] === "1") {
			echo '<p class="error">',$return['msg'],'</p>';
		}
		elseif($return['status'] === "2") {
			echo '<p class="error">',$return['msg'],'</p>';
		}
		elseif($return['status'] === "3") {
			echo '<meta http-equiv="refresh" content="2; url=index.php?mode=admin"> ';
			echo '<p class="success">',$return['msg'],' <a href="index.php?mode=admin">&#187;&#187;</a></p>';
		}
	}
	?>
	<form method="post">
		<b><?php echo l('Username'); ?> :</b><br />
		<input type="text" name="login[username]" value="" /><br />
		<br />
		<b><?php echo l('Password'); ?> :</b><br />
		<input type="password" name="login[pass]" value="" /><br />
		<br />
		<button type="submit" name="sub[auth]" title="<?php echo l('Login'); ?>">
			<?php echo l('Login'); ?>
		</button>
	</form>
	<p class="info"><?php echo l('Please ensure cookies are enabled in your browser security options'); ?></p>
	</div>
</div>
