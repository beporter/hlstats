<?php
/**
 * admin options file. manage the general options
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

// get the available styles
$styleFiles = glob("css/*.css");

$return = false;

if(isset($_POST['sub']['saveOptions'])) {
	$error = false;
	foreach($_POST['option'] as $k=>$v) {
		$v = trim($v);

		$query = $DB->query("UPDATE `".DB_PREFIX."_Options`
							SET `value` = '".$DB->real_escape_string($v)."'
							WHERE `keyname` = '".$DB->real_escape_string($k)."'");
		if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
		if($query !== true) {
			$return['msg'] = l('Could not save data');
			$return['status'] = "1";
			break;
		}
	}

	if($return === false) {
		$return['msg'] = l('Data saved');
		$return['status'] = "2";
		header('Location: index.php?mode=admin&task=options');
	}
}

pageHeader(array(l("Admin"),l('Options')), array(l("Admin")=>"index.php?mode=admin",l('Options')=>''));
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
	<h1><?php echo l('HLStats Options'); ?></h1>
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
	<form method="post" action="">
		<h2><?php echo l('General'); ?></h2>
		<table cellpadding="2" cellspacing="0" border="1" width="100%">
			<tr>
				<th width="220"><?php echo l("Site Name"); ?></th>
				<td>
					<input type="text" name="option[sitename]" size="40"
						value="<?php echo $g_options['sitename']; ?>" />
				</td>
			</tr>
			<tr>
				<th><?php echo l("Site URL"); ?></th>
				<td>
					<input type="text" name="option[siteurl]" size="40"
						value="<?php echo $g_options['siteurl']; ?>" />
				</td>
			</tr>
			<tr>
				<th><?php echo l("Contact URL"); ?></th>
				<td>
					<input type="text" name="option[contact]" size="40"
						value="<?php echo $g_options['contact']; ?>" /><br />
					<span class="small"><?php echo l('Can be an URL or even mailto:address'); ?></span>
				</td>
			</tr>
			<tr>
				<th><?php echo l("Hide Daily Awards"); ?></th>
				<td>
					<select name="option[hideAwards]">
						<option value="0" <?php if($g_options['hideAwards'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1" <?php if($g_options['hideAwards'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php echo l("Hide News"); ?></th>
				<td>
					<select name="option[hideNews]">
						<option value="0" <?php if($g_options['hideNews'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1" <?php if($g_options['hideNews'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php echo l("Show chart graphics"); ?></th>
				<td>
					<select name="option[showChart]">
						<option value="0" <?php if($g_options['showChart'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1" <?php if($g_options['showChart'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php echo l("Allow the use of signatures"); ?></th>
				<td>
					<select name="option[allowSig]">
						<option value="0" <?php if($g_options['allowSig'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1" <?php if($g_options['allowSig'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php echo l("Allow XML interface"); ?></th>
				<td>
					<select name="option[allowXML]">
						<option value="0" <?php if($g_options['allowXML'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1" <?php if($g_options['allowXML'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php echo l("Default Language"); ?></th>
				<td>
					<select name="option[LANGUAGE]">
					<option value="en">EN</option>
					<?php
					$available_langs = glob(getcwd()."/lang/*.ini.php");
					foreach($available_langs as $available_lang) {
						$available_lang = str_replace(".ini.php",'',basename($available_lang));
						$selected = '';
						if($g_options['LANGUAGE'] === $available_lang) $selected="selected='1'";
						echo '<option value="'.$available_lang.'" '.$selected.'>'.strtoupper($available_lang)."</option>";
					}
					unset($available_langs,$available_lang);
					?>
				</select>
				</td>
			</tr>
		</table>
		<h2><?php echo l('System Settings'); ?></h2>
		<blockquote>
			<?php echo l('This settings are also used by the daemon (hlstats.pl). If you change these the daemon have to be stopped and started again !!'); ?>
		</blockquote>
		<table cellpadding="2" cellspacing="0" border="1" width="100%">
			<tr>
				<th width="220">
					<?php echo l("Deletedays"); ?><br />
					<small>DELETEDAYS</small><br 7>
					<small><?php echo l('Default'); ?> 5</small>
				</th>
				<td>
					<input type="text" size="4" name="option[DELETEDAYS]" value="<?php echo $g_options['DELETEDAYS']; ?>"> <?php echo l('Days'); ?><br />
					<small>
						<?php echo l('HLStats will automatically delete history events from the events tables when they are over this many days old.<br>This is important for performance reasons.<br>Set lower if you are logging a large number of game servers or find the load on the MySQL server is too high.<br>A value of 0 means no delete days'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Days after a player is inactive"); ?><br />
					<small>TIMEFRAME</small><br />
					<small><?php echo l('Default'); ?> 5</small>
				</th>
				<td>
					<input type="text" size="4" name="option[TIMEFRAME]" value="<?php echo $g_options['TIMEFRAME']; ?>"> <br />
					<small>
						<?php echo l('For this player-activity.pl script needs to be executed once a day'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Min players"); ?><br />
					<small>MINPLAYERS</small><br />
					<small><?php echo l('Default'); ?> 2</small>
				</th>
				<td>
					<input type="text" size="4" name="option[MINPLAYERS]" value="<?php echo $g_options['MINPLAYERS']; ?>"> <br />
					<small>
						<?php echo l('Specifies the minimum number of players required in the server for player events to be recorded'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Max change at one time"); ?><br />
					<small>SKILLMAXCHANGE</small><br />
					<small><?php echo l('Default'); ?> 100</small>
				</th>
				<td>
					<input type="text" size="4" name="option[SKILLMAXCHANGE]" value="<?php echo $g_options['SKILLMAXCHANGE']; ?>"> <br />
					<small>
						<?php echo l('Specifies the maximum number of skill points a player can gain at one time through frags/events. Because players with low skill ratings gain more for killing players with high skill ratings'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Ignore BOT"); ?><br />
					<small>IGNOREBOTS</small><br />
					<small><?php echo l('Default'); ?> 1</small>
				</th>
				<td>
					<select name="option[IGNOREBOTS]">
						<option value="0" <?php if($g_options['IGNOREBOTS'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1"<?php if($g_options['IGNOREBOTS'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select><br />
					<small>
						<?php echo l('Completly ignore anything which is identified as a BOT.'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Mode"); ?><br />
					<small>MODE</small><br />
					<small><?php echo l('Default'); ?> Normal</small>
				</th>
				<td>
					<select name="option[MODE]">
						<option value="Normal" <?php if($g_options['MODE'] === "Normal") echo 'selected="1"'; ?>><?php echo l('Normal(Internet)'); ?></option>
						<option value="LAN" <?php if($g_options['MODE'] === "LAN") echo 'selected="1"'; ?>><?php echo l('LAN'); ?></option>
						<option value="NameTrack" <?php if($g_options['MODE'] === "NameTrack") echo 'selected="1"'; ?>><?php echo l('NameTrack'); ?></option>
					</select><br />
					<small>
						<?php echo l('Sets the player-tracking mode'); ?>
						<?php echo l('Normal(Internet)'); ?> - <?php echo l('Recommended for public Internet server use'); ?>
						<?php echo l('NameTrack'); ?> - <?php echo l('Players will be tracked by nickname'); ?>
						<?php echo l('LAN'); ?> - <?php echo l('Players will be tracked by IP Address'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Country lookup"); ?><br />
					<small>USEGEOIP</small><br />
					<small><?php echo l('Default'); ?> 0</small>
				</th>
				<td>
					<select name="option[USEGEOIP]">
						<option value="0" <?php if($g_options['USEGEOIP'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1"<?php if($g_options['USEGEOIP'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select><br />
					<small>
						<?php echo l('Use Maxmind GEO IP Database to get the country from each player. Please read the documentation how to achive this.'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Use Rcon"); ?><br />
					<small>RCON</small><br />
					<small><?php echo l('Default'); ?> 1</small>
				</th>
				<td>
					<select name="option[RCON]">
						<option value="0" <?php if($g_options['RCON'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1"<?php if($g_options['RCON'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select><br />
					<small>
						<?php echo l('Allow HLStats to send Rcon commands to the game servers.'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Record Rcon"); ?><br />
					<small>RCONRECORD</small><br />
					<small><?php echo l('Default'); ?> 0</small>
				</th>
				<td>
					<select name="option[RCONRECORD]">
						<option value="0" <?php if($g_options['RCONRECORD'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1"<?php if($g_options['RCONRECORD'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select><br />
					<small>
						<?php echo l('Sets whether to record Rcon commands to the Admin event table'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Rcon Ignore self"); ?><br />
					<small>RCONIGNORESELF</small><br />
					<small><?php echo l('Default'); ?> 0</small>
				</th>
				<td>
					<select name="option[RCONIGNORESELF]">
						<option value="0" <?php if($g_options['RCONIGNORESELF'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1"<?php if($g_options['RCONIGNORESELF'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select><br />
					<small>
						<?php echo l('Ignore (do not log) Rcon commands originating from the same IP as the server being Rcon'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Rcon reply format"); ?><br />
					<small>RCONSAY</small><br />
					<small><?php echo l('Default'); ?> <?php echo l('Ordinary say command'); ?></small>
				</th>
				<td>
					<select name="option[RCONSAY]">
						<option value="say" <?php if($g_options['RCONSAY'] === "say") echo 'selected="1"'; ?>><?php echo l('Ordinary say command'); ?></option>
						<option value="admin_psay"<?php if($g_options['RCONSAY'] === "admin_psay") echo 'selected="1"'; ?>><?php echo l('Return a private say adminMod'); ?></option>
						<option value="amx_psay"<?php if($g_options['RCONSAY'] === "amx_psay") echo 'selected="1"'; ?>><?php echo l('Return a private say with amxMod'); ?></option>
						<option value="sm_psay"<?php if($g_options['RCONSAY'] === "sm_psay") echo 'selected="1"'; ?>><?php echo l('Return a private say with sourceMod'); ?></option>
					</select><br />
					<small>
						<?php echo l('How the Rcon say command would be returned'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Show ingame stat report"); ?><br />
					<small>INGAMEPOINTS</small><br />
					<small><?php echo l('Default'); ?> 0</small>
				</th>
				<td>
					<select name="option[INGAMEPOINTS]">
						<option value="0" <?php if($g_options['INGAMEPOINTS'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1"<?php if($g_options['INGAMEPOINTS'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select><br />
					<small>
						<?php echo l('The response is done via rcon and the option RCONSAY. Reports current skill points after event/frag back into game'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Log chat messages"); ?><br />
					<small>LOGCHAT</small><br />
					<small><?php echo l('Default'); ?> 0</small>
				</th>
				<td>
					<select name="option[LOGCHAT]">
						<option value="0" <?php if($g_options['LOGCHAT'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1"<?php if($g_options['LOGCHAT'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select><br />
					<small>
						<?php echo l('Log all your chat massages. This is conected with the DeleteDays setting. So the chat messages will be only stored in the DB for the DeleteDays value'); ?>
					</small>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo l("Strip some common tags"); ?><br />
					<small>STRIPTAGS</small><br />
					<small><?php echo l('Default'); ?> 1</small>
				</th>
				<td>
					<select name="option[STRIPTAGS]">
						<option value="0" <?php if($g_options['STRIPTAGS'] === "0") echo 'selected="1"'; ?>><?php echo l('No'); ?></option>
						<option value="1"<?php if($g_options['STRIPTAGS'] === "1") echo 'selected="1"'; ?>><?php echo l('Yes'); ?></option>
					</select><br />
					<small>
						<?php echo l('Strip common tags from player names eg. CD or NO-CD. See documentation for more details'); ?>
					</small>
				</td>
			</tr>
		</table>
		<h2><?php echo l('Paths'); ?></h2>
		<table cellpadding="2" cellspacing="0" border="1" width="100%">
			<tr>
				<th width="220"><?php echo l("Map Download URL"); ?></th>
				<td>
					<input type="text" name="option[map_dlurl]" size="40"
						value="<?php echo $g_options['map_dlurl']; ?>" /><br />
					<span class="small">eg. http://domain.tld/maps/%GAME%/%MAP%.zip</span><br />
					<span class="small">=&gt; http://domain.tld/maps/cstrike/nuke.zip</span><br />
				</td>
			</tr>
		</table>
		<h2><?php echo l('Preset Styles'); ?></h2>
		<table cellpadding="2" cellspacing="0" border="1" width="100%">
			<tr>
				<th width="220"><?php echo l("Style"); ?></th>
				<td>
					<select name="option[style]">
						<?php
						foreach($styleFiles as $styleFile) {
							$sfile = str_replace('.css','',basename($styleFile));
							$selected='';
							if($g_options['style'] === $sfile) $selected='selected="1"';

							echo '<option ',$selected,' value="',$sfile,'">',$sfile,'</option>';
						}
						?>
					</select>
				</td>
			</tr>
		</table><br />
		<button type="submit" title="<?php echo l('Save'); ?>" name="sub[saveOptions]">
			<?php echo l('Save'); ?>
		</button>
	</form>
	</div>
</div>
