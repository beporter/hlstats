<?php
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

// Player Details
$player = '';
$uniqueid = '';
$killLimit = 5;
$mode = false;
$pl_name = '';
$pl_urlname = '';

if(!empty($_GET["player"])) {
	if(validateInput($_GET["player"],'digit') === true) {
		$player = $_GET["player"];
	}
}
if(!empty($_GET["uniqueid"])) {
	if(validateInput($_GET["uniqueid"],'digit') === true) {
		$uniqueid  = $_GET["uniqueid"];
		$mode = true;
	}
}
if(!empty($_GET['killLimit'])) {
	if(validateInput($_GET['killLimit'],'digit') === true) {
		$killLimit = $_GET['killLimit'];
	}
}

require('class/player.class.php');
$playerObj = new Player($player,$mode,$game);
if($playerObj === false) {
	die('No such player');
}
$playerObj->setOption('killLimit',$killLimit);
$playerObj->loadFullInformation();

$pl_name = makeSavePlayerName($playerObj->getParam('name'));
$pl_urlname = urlencode($playerObj->getParam('lastName'));


// get the game name
// if it fails we use the game code which is stored in the player table
$game = $playerObj->getParam("game");
$query = $DB->query("SELECT name FROM `".DB_PREFIX."_Games`
					WHERE code = '".$DB->real_escape_string($game)."'");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
if ($query->num_rows != 1) {
	$gamename = ucfirst($game);
}
else {
	$result = $query->fetch_assoc();
	$gamename = $result['name'];
}
$query->free();

// show header
pageHeader(
	array($gamename, l("Player Details"), $pl_name),
	array(
		$gamename => "index.php?game=$game",
		l("Player Rankings") => "index.php?mode=players&game=$game",
		l("Player Details")=>""
	),
	$pl_name
);

$rcol = "row-dark";
?>

<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="index.php?mode=playerhistory&amp;player=<?php echo $player; ?>"><img src="hlstatsimg/history.gif" width='16' height='16' border='0' hspace="3" align="middle" alt="history.gif"><?php echo l('Event History'); ?></a>
			</li>
			<li>
				<a href="index.php?mode=playerchathistory&amp;player=<?php echo $player; ?>"><img src="hlstatsimg/history.gif" width='16' height='16' border='0' hspace="3" align="middle" alt="history.gif"><?php echo l('Chat History'); ?></a>
			</li>
		</ul>
	</div>
	<h1><?php echo l('Game'); ?></h1>
	<div class="left-box">
		<img src="hlstatsimg/game-<?php echo $game; ?>-big.png" alt="<?php echo $game; ?>" title="<?php echo $game; ?>" width="100px"  />
	</div>
</div>
<div id="main">
	<div class="content">
	<h1><?php echo $pl_name; ?></h1>
	<h2><?php echo l('Player Profile'); ?> / <?php echo l('Statistics Summary'); ?></h2>
	<table cellspacing="0" cellpadding="4" width="100%" border="1">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th>
			   <?php echo l("Member of Clan"); ?>
			</th>
			<td>
				<?php if ($playerObj->getParam("clan")) { ?>
					<a href="index.php?mode=claninfo&amp;clan=<?php echo $playerObj->getParam("clan"); ?>&amp;game=<?php echo $game; ?>">
					<img src="hlstatsimg/clan.gif" width="16" height="16" hspace="4"
							border="0" align="middle" alt="clan.gif" />
					<?php echo makeSavePlayerName($playerObj->getParam("clan_name")); ?>
					</a>
				<?php }	else {
					echo l('None');
				}
				?>
			</td>
			<th align="right"><?php	echo l("Points"); ?></th>
			<td>
				<?php echo $playerObj->getParam("skill");?>

				<?php if($playerObj->getParam('skill') > $playerObj->getParam('oldSkill')) { ?>
				<img src="hlstatsimg/skill_up.gif" width='16' height='16' hspace='4'
					border='0' align="middle" alt="skill_up.gif" />
				<?php } elseif ($playerObj->getParam('skill') < $playerObj->getParam('oldSkill')) { ?>
				<img src="hlstatsimg/skill_down.gif" width='16' height='16' hspace='4'
					border='0' align="middle" alt="skill_down" />
				<?php } else { ?>
				<img src="hlstatsimg/skill_stay.gif" width='16' height='16' hspace='4'
					border='0' align="middle" alt="skill_stay.gif" />
				<?php } ?>
			 </td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Player ID"); ?></th>
			<td><?php echo $player; ?></td>
			<th><?php echo l("Kills"); ?></th>
			<td><?php echo $playerObj->getParam("kills"); ?></td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th>
			   <?php if ($g_options['MODE'] == "LAN") {
					echo l("IP Addresses");
				} else {
					echo l("Unique ID(s)");
				}
			   ?>
			</th>
			<td>
			   <?php
				if ($g_options['MODE'] == "NameTrack") {
					echo l("Unknown");
				} else {
					echo $playerObj->getParam('uniqueIds');
				}
			   ?>
			</td>
			<th><?php echo l("Deaths"); ?></th>
			<td><?php echo $playerObj->getParam("deaths"); ?></td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Last Connect"); ?>*</th>
			<td>
			<?php
				echo $playerObj->getParam('lastConnect');
				if($playerObj->getParam('country')) {
					echo '&nbsp;<img src="hlstatsimg/site/flag/'.$playerObj->getParam('countryCode').'.png" alt="'.$playerObj->getParam('country').'" title="'.$playerObj->getParam('country').'" height="11" width="16" />';
				}
			?>
			</td>
			<th><?php echo l("Suicides"); ?></th>
			<td><?php echo $playerObj->getParam("suicides"); ?></td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Total Connection Time"); ?>*</th>
			<td><?php echo $playerObj->getParam('maxTime'); ?></td>
			<th><?php echo l("Kills per Death"); ?></th>
			<td><?php echo number_format($playerObj->getParam("kpd"),2); ?></td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l("Average Ping"); ?></th>
			<td><?php echo $playerObj->getParam('avgPing'); ?></td>
			<th><?php echo l("Teammate Kills"); ?>*</th>
			<td><?php echo $playerObj->getParam("teamkills"); ?></td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<td colspan="2">&nbsp;</td>
			<th><?php echo l("Weapon Accuracy"); ?></th>
			<td><?php echo number_format($playerObj->getParam("accuracy"),2); ?>%</td>
		</tr>
	</table>
	<a name="rank"></a>
	<h2>
		<?php echo l('Rank')." (".l('ordered by Points').")" ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#rank"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
	</h2>
	<?php
		if($playerObj->getParam('isBot') === "1") {
			echo "<p>".l('BOT')."</p>";
		}
		elseif($playerObj->getParam('active') === "0") {
			echo "<p>".l('inactive Player')."</p>";
		}
		else {
	?>
	<table cellspacing="0" cellpadding="4" width="100%" border="1">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th width="220">
				<?php echo l("Active players and no Bots"); ?>
			</th>
			<td>
				<?php
					echo "<b>".$playerObj->getParam('rankPoints')."</b> ";
				?>
			</td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th>
				<?php echo l("All players and no Bots"); ?>
			</th>
			<td>
				<?php
					echo "<b>".$playerObj->getParam('allwithoutBot')."</b> ";
				?>
			</td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th>
				<?php echo l("All players and with Bots"); ?>
			</th>
			<td>
				<?php
					echo "<b>".$playerObj->getParam('allPlayers')."</b> ";
				?>
			</td>
		</tr>
	</table>
	<?php } ?>
	<a name="profile"></a>
	<h2>
		<?php echo l('Profile'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#profile"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
	</h2>
	<table border="1" cellspacing="0" cellpadding="4" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th width="30%">
				<img src="hlstatsimg/site/user.png" alt="Username" width="24" style="float: left;" />
				&nbsp;<?php echo l("Real Name"); ?>
			</th>
			<td>
			   <?php
				if ($playerObj->getParam("fullName")) {
					echo "<b>" . htmlspecialchars($playerObj->getParam("fullName")) . "</b>";
				} else {
					echo l("Not specified");
				}
			   ?>
			</td>
			<th>
				<img src="hlstatsimg/site/myspace.png" alt="myspace" width="24" style="float: left;" />
				&nbsp;<?php echo l("MySpace"); ?>
			</th>
			<td><?php
				$url = getLink($playerObj->getParam("myspace"));
				if (!empty($url)) {
					echo $url;
				} else {
					echo l("Not specified");
				}
			   ?>
			</td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th>
				<img src="hlstatsimg/site/email.png" alt="email" width="24" style="float: left;" />
				&nbsp;<?php echo l("E-mail Address"); ?>
			</th>
			<td>
			   <?php
				$email = $playerObj->getParam("email");
				if(!empty($email)) {
					$email = getEmailLink($playerObj->getParam("email"));
					echo $email;
				} else {
					echo l("Not specified");
				}
			   ?>
			</td>
			<th>
				<img src="hlstatsimg/site/facebook.png" alt="Facebook" width="24" style="float: left;" />
				&nbsp;<?php echo l("Facebook"); ?>
			</th>
			<td><?php
				$url = getLink($playerObj->getParam("facebook"));
				if (!empty($url)) {
					echo $url;
				} else {
					echo l("Not specified");
				}
			   ?>
			</td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th>
				<img src="hlstatsimg/site/website.png" alt="website" width="24" style="float: left;" />
				&nbsp;<?php echo l("Home Page"); ?>
			</th>
			<td>
				<?php
				$url = getLink($playerObj->getParam("homepage"));
				if (!empty($url)) {
					echo $url;
				} else {
					echo l("Not specified");
				}
			   ?>
			</td>
			<th>
				<img src="hlstatsimg/site/jabber.png" alt="jabber" width="24" style="float: left;" />
				&nbsp;<?php echo l("Jabber"); ?>
			</th>
			<td>
			   <?php
				if ($playerObj->getParam("jabber")) {
					echo htmlspecialchars($playerObj->getParam("jabber"));
				} else {
					echo l("Not specified");
				}
			   ?>
			</td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th>
				<img src="hlstatsimg/site/icq.png" alt="ICQ" width="24" style="float: left;" />
				&nbsp;<?php echo l("ICQ Number"); ?>
			</th>
			<td>
			   <?php
				if ($playerObj->getParam("icq")) {
					echo "<a href=\"http://www.icq.com/"
						. urlencode($playerObj->getParam("icq")) . "\" target=\"_blank\">"
						. htmlspecialchars($playerObj->getParam("icq")) . "</a>";
				} else {
					echo l("Not specified");
				}
			   ?>
			</td>
			<th>
				<img src="hlstatsimg/site/steam.png" alt="steam" width="24" style="float: left;" />
				&nbsp;<?php echo l("Steam Profile"); ?>
				</th>
			<td><?php
				$url = getLink($playerObj->getParam("steamprofile"));
				if (!empty($url)) {
					echo $url;
				} else {
					echo l("Not specified");
				}
			   ?>
			</td>
		</tr>
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th>
				<img src="hlstatsimg/site/skype.png" alt="skype" width="24" style="float: left;" />
				&nbsp;<?php echo l("Skype username"); ?>
			</th>
			<td>
			   <?php
				if ($playerObj->getParam("skype")) {
					echo htmlspecialchars($playerObj->getParam("skype"));
				} else {
					echo l("Not specified");
				}
			   ?>
			</td>
			<th>&nbsp;</th>
			<td>&nbsp;</td>
		</tr>
		<?php if($g_options['allowSig'] === "1" && $playerObj->getParam('isBot') === "0") { ?>
		<tr>
			<th><?php echo l('Signature'); ?></th>
			<td colspan="3">
				<a href="<?php echo 'sig.php?playerId='.$player.'&style=black'; ?>"><?php echo l('Black'); ?></a> |
				<a href="<?php echo 'sig.php?playerId='.$player.'&style=red'; ?>"><?php echo l('Red'); ?></a> |
				<a href="<?php echo 'sig.php?playerId='.$player.'&style=blue'; ?>"><?php echo l('Blue'); ?></a> |
				<a href="<?php echo 'sig.php?playerId='.$player.'&style=green'; ?>"><?php echo l('Green'); ?></a> |
				<a href="<?php echo 'sig.php?playerId='.$player.'&style=multi'; ?>"><?php echo l('Multi'); ?></a> |
				<a href="<?php echo 'sig.php?playerId='.$player.'&style=css_nitro'; ?>"><?php echo 'CSS Nitro'; ?></a><br />
				<small><?php echo l('Size: 400x100 png with transparent background'); ?></small>
			</td>
		</tr>
		<?php } ?>
	</table>
	<?php
		$steamAchievements = $playerObj->getParam('steamAchievements');
		if(!empty($steamAchievements)) {
	?>
	<h2>
		<?php echo l('Steam Achievements'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#Achievements"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
	</h2>
	<?php
			foreach($steamAchievements as $entry) {
				echo '<img src="'.$entry['picture'].'" title="'.$entry['name'].' - '.$entry['desc'].'" alt="'.$entry['name'].'" width="32" height="32" />&nbsp;';
			}
		}
	?>
<?php
$aliases = $playerObj->getParam('aliases');
if(!empty($aliases)) { ?>
	<a name="aliases"></a>
	<h2>
		<?php echo l('Aliases'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#aliases"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l('Name'); ?></th>
			<th><?php echo l('Used'); ?></th>
			<th><?php echo l('Last Use'); ?></th>
			<th><?php echo l('Kills'); ?></th>
			<th><?php echo l('Deaths'); ?></th>
			<th><?php echo l('Kills per Death'); ?></th>
			<th><?php echo l('Suicides'); ?></th>
		</tr>
		<?php
		foreach ($aliases as $entry) {
			echo '<tr class="',toggleRowClass($rcol),'">';
			echo '<td>',makeSavePlayerName($entry['name']),'</td>';
			echo '<td>',$entry['numuses'],'</td>';
			echo '<td>',$entry['lastuse'],'</td>';
			echo '<td>',$entry['kills'],'</td>';
			echo '<td>',$entry['deaths'],'</td>';
			echo '<td>',number_format($entry['kpd'],2),'</td>';
			echo '<td>',$entry['suicides'],'</td>';
			echo '</tr>';
		}
		?>
	</table>
<?php }

$actions = $playerObj->getParam('actions');
if(!empty($actions)) { ?>
	<a name="playeractions"></a>
	<h2>
		<?php echo l('Player Actions'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#playeractions"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l('Action'); ?></th>
			<th><?php echo l('Achieved'); ?></th>
			<th><?php echo l('Points Bonus'); ?></th>
		</tr>
		<?php
		foreach ($actions as $entry) {
			echo '<tr class="',toggleRowClass($rcol),'">';
			echo '<td>',$entry['description'],'</td>';
			echo '<td>',$entry['obj_count'],'</td>';
			echo '<td>',$entry['obj_bonus'],'</td>';
			echo '</tr>';
		}
		?>
	</table>
<?php }

$playerPlayerActions = $playerObj->getParam('playerPlayerActions');
if(!empty($playerPlayerActions)) { ?>
	<a name="playerplayeractions"></a>
	<h2>
		<?php echo l('Player-Player Actions'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#playerplayeractions"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l('Action'); ?></th>
			<th><?php echo l('Achieved'); ?></th>
			<th><?php echo l('Points Bonus'); ?></th>
		</tr>
		<?php
		foreach ($playerPlayerActions as $entry) {
			echo '<tr class="',toggleRowClass($rcol),'">';
			echo '<td>',$entry['description'],'</td>';
			echo '<td>',$entry['obj_count'],'</td>';
			echo '<td>',$entry['obj_bonus'],'</td>';
			echo '</tr>';
		}
		?>
	</table>
<?php }

$teamSelection = $playerObj->getParam('teamSelection');
if(!empty($teamSelection)) { ?>
	<a name="teams"></a>
	<h2>
		<?php echo l('Team Selection'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#teams"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l('Team'); ?></th>
			<th><?php echo l('Joined'); ?></th>
			<th><?php echo l('Percentage of Times'); ?></th>
		</tr>
		<?php
		foreach ($teamSelection as $entry) {
			echo '<tr class="',toggleRowClass($rcol),'">';
			echo '<td>',$entry['name'],'</td>';
			echo '<td>',$entry['teamcount'],'</td>';
			echo '<td>';
			echo '<div class="percentBar" title="',number_format((int)$entry['percent'],0),'%"><div class="barContent" style="width:',number_format((int)$entry['percent'],0),'px"></div></div>',"\n";
			echo '</td>';
			echo '</tr>';
		}
		?>
	</table>
<?php }

$roleSelection = $playerObj->getParam('roleSelection');
if(!empty($roleSelection)) { ?>
	<a name="roles"></a>
	<h2>
		<?php echo l('Role Selection'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#role"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th width="40">&nbsp;</th>
			<th><?php echo l('Role'); ?></th>
			<th><?php echo l('Joined'); ?></th>
			<th><?php echo l('Percentage of Times'); ?></th>
		</tr>
		<?php
		foreach ($roleSelection as $entry) {
			echo '<tr class="',toggleRowClass($rcol),'">';
			echo '<td><img src="hlstatsimg/roles/',$game,'/',$entry['rolecode'],'.png" /></td>';
			echo '<td>',$entry['name'],'</td>';
			echo '<td>',$entry['rolecount'],'</td>';
			echo '<td>';
			echo '<div class="percentBar" title="',number_format((int)$entry['percent'],0),'%"><div class="barContent" style="width:',number_format((int)$entry['percent'],0),'px"></div></div>',"\n";
			echo '</td>';
			echo '</tr>';
		}
		?>
	</table>
<?php }

$weaponUsage = $playerObj->getParam('weaponUsage');
if(!empty($weaponUsage)) { ?>
	<a name="weaponusage"></a>
	<h2>
		<?php echo l('Weapon Usage'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#weaponusage"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l('Weapon'); ?></th>
			<th><?php echo l('Points Modifier'); ?></th>
			<th><?php echo l('Kills'); ?></th>
			<th><?php echo l('Percentage of Kills'); ?></th>
		</tr>
		<?php
		foreach ($weaponUsage as $entry) {
			echo '<tr class="',toggleRowClass($rcol),'">',"\n";
			echo '<td align="center">',"\n";
			echo '<a href="index.php?mode=weaponinfo&amp;weapon='.$entry['weapon'].'&amp;game='.$game.'"><img src="hlstatsimg/weapons/',$game,'/',$entry['weapon'],'.png" alt="',$entry['name'],'" title="',$entry['name'],'" /></a><br />',"\n";
			echo '<small>',$entry['name'],'</small>';
			echo '</td>',"\n";
			echo '<td>',$entry['modifier'],'</td>',"\n";
			echo '<td>',$entry['kills'],'</td>',"\n";
			echo '<td>',"\n";
			echo '<div class="percentBar" title="',number_format((int)$entry['percent'],0),'%"><div class="barContent" style="width:',number_format((int)$entry['percent'],0),'px"></div></div>',"\n";
			echo '</td>',"\n";
			echo '</tr>',"\n";
		}
		?>
	</table>
<?php }

$weaponStats = $playerObj->getParam('weaponStats');
if(!empty($weaponStats)) { ?>
	<a name="weaponstats"></a>
	<h2>
		<?php echo l('Weapon Stats'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#weaponstats"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l('Weapon'); ?></th>
			<th><?php echo l('Shots'); ?></th>
			<th><?php echo l('Hits'); ?></th>
			<th><?php echo l('Damage'); ?></th>
			<th><?php echo l('Head Shots'); ?></th>
			<th><?php echo l('Kills'); ?></th>
			<th><?php echo l('Deaths'); ?></th>
			<th><?php echo l('Kills per Death'); ?></th>
			<th><?php echo l('Accuracy'); ?></th>
			<th><?php echo l('Damage per Hit'); ?></th>
			<th><?php echo l('Shots per Kill'); ?></th>
		</tr>
		<?php
		foreach ($weaponStats as $entry) {
			if($entry['smshots'] == "0" && $entry['smhits'] == "0" && $entry['smdamage'] == "0"
				&& $entry['smheadshots'] == "0" && $entry['smkills'] == "0" && $entry['smdeaths'] == "0"
			) {
				continue;
			}
			echo '<tr class="',toggleRowClass($rcol),'">';
			echo '<td align="center"><img src="hlstatsimg/weapons/',$game,'/',$entry['smweapon'],'.png" alt="',$entry['smweapon'],'" title="',$entry['smweapon'],'" /></td>';
			echo '<td>',$entry['smshots'],'</td>';
			echo '<td>',$entry['smhits'],'</td>';
			echo '<td>',$entry['smdamage'],'</td>';
			echo '<td>',$entry['smheadshots'],'</td>';
			echo '<td>',$entry['smkills'],'</td>';
			echo '<td>',$entry['smdeaths'],'</td>';
			echo '<td>',number_format($entry['smkdr'],2),'</td>';
			echo '<td>',number_format($entry['smaccuracy'],2),'%</td>';
			echo '<td>',number_format($entry['smdhr'],2),'</td>';
			echo '<td>',number_format($entry['smspk'],2),'</td>';
			echo '</tr>';
		}
		?>
	</table>
<?php }

$weaponTarget = $playerObj->getParam('weaponTarget');
if(!empty($weaponTarget)) { ?>
	<a name="weapontarget"></a>
	<h2>
		<?php echo l('Weapon Target'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#weapontarget"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l('Weapon'); ?></th>
			<th><?php echo l('Head'); ?></th>
			<th><?php echo l('Chest'); ?></th>
			<th><?php echo l('Stomach'); ?></th>
			<th><?php echo l('Left Arm'); ?></th>
			<th><?php echo l('Right Arm'); ?></th>
			<th><?php echo l('Left Leg'); ?></th>
			<th><?php echo l('Right Leg'); ?></th>
		</tr>
		<?php
		foreach ($weaponTarget as $entry) {
			if($entry['smhead'] == "0" && $entry['smchest'] == "0" && $entry['smstomach'] == "0"
				&& $entry['smleftarm'] == "0" && $entry['smrightarm'] == "0" && $entry['smleftleg'] == "0"
				&& $entry['smrightleg'] == "0"
			) {
				continue;
			}
			echo '<tr class="',toggleRowClass($rcol),'">';
			echo '<td align="center"><img src="hlstatsimg/weapons/',$game,'/',$entry['smweapon'],'.png" alt="',$entry['smweapon'],'" title="',$entry['smweapon'],'" /></td>';
			echo '<td>',$entry['smhead'],'</td>';
			echo '<td>',$entry['smchest'],'</td>';
			echo '<td>',$entry['smstomach'],'</td>';
			echo '<td>',$entry['smleftarm'],'</td>';
			echo '<td>',$entry['smrightarm'],'</td>';
			echo '<td>',$entry['smleftleg'],'</td>';
			echo '<td>',$entry['smrightleg'],'</td>';
			echo '</tr>';
		}
		?>
	</table>
<?php }

$hitStats = $playerObj->getParam('histats');

$maps = $playerObj->getParam('maps');
if(!empty($maps)) { ?>
	<a name="maps"></a>
	<h2>
		<?php echo l('Map Performance'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#maps"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l('Map Name'); ?></th>
			<th><?php echo l('Kills'); ?></th>
			<th><?php echo l('Percentage of Kills'); ?></th>
			<th><?php echo l('Deaths'); ?></th>
			<th><?php echo l('Kills per Death'); ?></th>
		</tr>
		<?php
		foreach ($maps as $entry) {
			echo '<tr class="',toggleRowClass($rcol),'">';
			echo '<td><a href="index.php?mode=mapinfo&game=',$game,'&map=',$entry['map'],'">',$entry['map'],'</a></td>';
			echo '<td>',$entry['kills'],'</td>';
			echo '<td>';
			echo '<div class="percentBar" title="',number_format((int)$entry['percentage'],0),'%"><div class="barContent" style="width:',number_format((int)$entry['percentage'],0),'px"></div></div>',"\n";
			echo '</td>';
			echo '<td>',$entry['deaths'],'</td>';
			echo '<td>',number_format($entry['kpd'],2),'</td>';
			echo '</tr>';
		}
		?>
	</table>
<?php }

$playerKillStats = $playerObj->getParam('killstats');
if(!empty($playerKillStats)) { ?>
	<a name="killstats"></a>
	<h2>
		<?php echo l('Player Kill Statistics'); ?>
		<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#killstats"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
		<?php echo $killLimit ?> <?php echo l('or more kills'); ?>
		(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
	</h2>
	<table cellpadding="2" cellspacing="0" border="1" width="100%">
		<tr class="<?php echo toggleRowClass($rcol); ?>">
			<th><?php echo l('Victim'); ?></th>
			<th><?php echo l('Times Killed'); ?></th>
			<th><?php echo l('Deaths by'); ?></th>
			<th><?php echo l('Kills per Death'); ?></th>
		</tr>
		<?php
		foreach ($playerKillStats as $entry) {
			echo '<tr class="',toggleRowClass($rcol),'">';
			echo '<td>';
				if($entry['isBot'] === "1") {
					echo '<img src="hlstatsimg/bot.png" alt="BOT" title="BOT" width="16" height="16" />&nbsp;';
				}
				elseif($entry['active'] == "1") {
					echo '<img src="hlstatsimg/player.gif" width="16" height="16" alt="',l('active Player'),'" alt="',l('active Player'),'" />';
				}
				else {
					echo '<img src="hlstatsimg/player_inactive.gif" width="16" height="16" alt="',l('inactive Player'),'" alt="',l('inactive Player'),'" />';
				}
				echo '&nbsp;<a href="index.php?mode=playerinfo&player=',$entry['playerId'],'">',makeSavePlayerName($entry['name']),'</a>';
			echo '</td>';
			echo '<td>',$entry['kills'],'</td>';
			echo '<td>',$entry['deaths'],'</td>';
			echo '<td>',number_format($entry['kpd'],2),'</td>';
			echo '</tr>';
		}
		?>
	</table>
	<script type="text/javascript" language="javascript">
	function changeLimit(num) {
		location = "index.php?mode=playerinfo&player=<?php echo $player ?>&killLimit=" + num + "#killstats";
	}
	</script>
	<p>
	<?php echo l('Show people this person has killed'); ?>
		<select onchange='changeLimit(this.options[this.selectedIndex].value)'>
	<?php
	  for($j = 1; $j < 16; $j++) {
			echo "<option value=$j";
			if($killLimit == $j) { echo " selected"; }
			echo ">$j</option>";
		}
	?>
	</select>
	<?php echo l('or more times in the last'),' ',$g_options['DELETEDAYS'],' ',l('days'); ?>
	</p>
<?php }


if($g_options['showChart'] == "1") {
	require('class/chart.class.php');
	$chartObj = new Chart($game);
	$playtimeChart = $chartObj->getChart('playTimePerDay',$player);
	if(!empty($chart)) {
?>
		<a name="playtime"></a>
		<h2>
			<?php echo l('Playtime per day'); ?>
			<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#playtime"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
			(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
		</h2>
		<div class="chart"><img src="<?php echo $playtimeChart; ?>" /></div>
<?php }

	$chartObj = new Chart($game);
	$killDayChart = $chartObj->getChart('killsPerDay',$player);
	if(!empty($killDayChart)) {
?>
		<a name="playerkillsperday"></a>
		<h2>
			<?php echo l('Player Kill Statistics per Day'); ?>
			<a href="index.php?mode=playerinfo&amp;player=<?php echo $player; ?>#playerkillsperday"><img src="hlstatsimg/link.gif" alt="<?php echo l('Direct Link'); ?>" title="<?php echo l('Direct Link'); ?>" /></a>
			(<?php echo l('Last'),' ',$g_options['DELETEDAYS'],' ',l('Days'); ?>)
		</h2>
		<div class="chart"><img src="<?php echo $killDayChart; ?>" /></div>
<?php }
}
?>
	<p>&nbsp;</p>
	<p>
		<b><?php echo l('Note'); ?>:</b><br />
		<?php echo l('Player event histories cover only the last'); ?>&nbsp;
		<?php echo $g_options['DELETEDAYS']; ?> <?php echo l('days'); ?>. <?php echo l('Items marked "Last'); ?>&nbsp;
		<?php echo $g_options['DELETEDAYS']; ?> <?php echo l('Days" or "*" above are generated from the player\'s Event History. Player kill, death and suicide totals and points ratings cover the entire recorded period'); ?>.
	</p>
	<p style="text-align: right">
	    <b><?php echo l('Admin Options'); ?>:</b>
	    <a href="<?php echo "index.php?mode=admin&amp;task=toolsEditdetails&amp;playerId=$player"; ?>"><?php echo l('Edit Player Details'); ?></a>
	</p>
	</div>
</div>
