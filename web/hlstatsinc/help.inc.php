<?php
/**
 * help overview page
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

$query = $DB->query("SELECT g.name AS gamename,
		a.description,
		IF(SIGN(a.reward_player) > 0,
			CONCAT('+', a.reward_player),
			a.reward_player
		) AS s_reward_player,
		IF(a.team != '' AND a.reward_team != 0,
			IF(SIGN(a.reward_team) >= 0,
				CONCAT(t.name, ' +', a.reward_team),
				CONCAT(t.name,  ' ', a.reward_team)
			),
			''
		) AS s_reward_team,
		IF(for_PlayerActions='1', 'Yes', 'No') AS for_PlayerActions,
		IF(for_PlayerPlayerActions='1', 'Yes', 'No') AS for_PlayerPlayerActions,
		IF(for_TeamActions='1', 'Yes', 'No') AS for_TeamActions,
		IF(for_WorldActions='1', 'Yes', 'No') AS for_WorldActions
	FROM `".DB_PREFIX."_Actions` AS a
	LEFT JOIN `".DB_PREFIX."_Games` AS g
		ON g.code = a.game
	LEFT JOIN `".DB_PREFIX."_Teams` AS t
		ON t.code = a.team
		AND t.game = a.game
	ORDER BY a.game ASC, a.description ASC");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
$gameActions = array();
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$gameActions[] = $result;
	}
	$query->free();
}

$query = $DB->query("
		SELECT g.name AS gamename,
			w.code,
			w.name,
			w.modifier
		FROM `".DB_PREFIX."_Weapons` AS w
		LEFT JOIN `".DB_PREFIX."_Games` AS g
			ON g.code = w.game
		ORDER BY w.game ASC,
			w.modifier DESC");
if(SHOW_DEBUG && $DB->error) var_dump($DB->error);
$weaponModifiers = array();
if($query->num_rows > 0) {
	while($result = $query->fetch_assoc()) {
		$weaponModifiers[] = $result;
	}
	$query->free();
}

pageHeader(array(l("Help")), array(l("Help")=>""));
?>

<div id="sidebar" >
	<h1><?php echo l('Questions'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="#help">How and where can I get help if I need it ?</a>
			</li>
			<li>
				<a href="#players">How are players tracked? Or, why is my name listed more than once?</a>
			</li>
			<li>
				<a href="#points">How is the "points" rating calculated ?</a>
			</li>
			<li>
				<a href="#weaponmods">What are all the weapon points modifiers?</a>
			</li>
			<li>
				<a href="#set">How can I set my profile data ? eg. hompage or Facebook profile</a>
			</li>
			<li>
				<a href="#hideranking">My rank is embarrassing. How can I opt out ?</a>
			</li>
			<li>
				<a href="#playersoverview">What are active players etc. ?</a>
			</li>
		</ul>
	</div>
</div>
<div id="main">
	<div class="content">
	<a name="help"></a>
	<h1>How and where can I get help if I need it ?</h1>
	<p>
		First make sure you have <a href="http://hlstats-community.org/Documentation.html" target="_blank">read the documentation</a>.<br />
		In most cases it is only a config error.<br />
		<br />
		Then you can request help in the <a href="http://forum.hlstats-community.org/" target="_blank">hlstats-community.org forum</a>.
		But please use the search function first befor you start a new topic. Also explain as much as possible otherwise no one can help.<br />
		<br />
		For more and quick information we a <a href="http://blog.bananas-playground.net/categories/12-HLstats" target="_blank">blog</a>
		and <a href="http://twitter.com/HLStats" target="_blank">twitter</a>.
	</p>
	<a name="players"></a>
	<h1>How are players tracked? Or, why is my name listed more than once ?</h1>
	<p>
	<?php if ($g_options['MODE'] == "NameTrack") { ?>
		Players are tracked by nickname. All statistics for any player using a particular name will
		be grouped under that name. It is not possible for a name to be listed more than once for each game.<br />
		<br />
	<?php } else {
			if ($g_options['MODE'] == "LAN") {
				$uniqueid = "IP Address";
				$uniqueid_plural = "IP Addresses";
	?>
		Players are tracked by IP Address. IP addresses are specific to a computer on a network.<br />
		<br />
	<?php
			} else {
				$uniqueid = "Unique ID";
				$uniqueid_plural = "Unique IDs";
	?>
		Players are tracked by Unique ID.<br />
		<br />
	<?php } ?>
		A player may have more than one name. On the Player Rankings pages, players are shown with the most
		recent name they used in the game. If you click on a player's name, the Player Details page will
		show you a list of all other names that this player uses, if any, under the Aliases section
		(if the player has not used any other names, the Aliases section will not be displayed).<br />
		<br />
		Your name may be listed more than once if somebody else (with a different <?php echo $uniqueid; ?>)
		uses the same name.<br />
		<br />
		You can use the <a href="index.php?mode=search">Search</a> function to find a player by name or
		<?php echo $uniqueid; ?>.
	<?php } ?>
	</p>
	<a name="points"></a>
	<h1>How is the "points" rating calculated ?</h1>
	<p>
		A new player has 1000 points. Every time you make a kill, you gain a certain amount of
		points depending on a) the victim's points rating, and b) the weapon you used. If you kill
		someone with a higher points rating than you, then you gain more points than if you kill
		someone with a lower points rating than you. Therefore, killing newbies will not get you as
		far as killing the #1 player. And if you kill someone with your knife, you gain more points
		than if you kill them with a rifle, for example.<br />
		<br />
		When you are killed, you lose a certain amount of points, which again depends on the points
		rating of your killer and the weapon they used (you don't lose as many points for being killed
		by the #1 player with a rifle than you do for being killed by a low ranked player with a knife).
		This makes moving up the rankings easier, but makes staying in the top spots harder.<br />
		<br />
		Specifically, the equations are:<br />
		<br />
<pre>Killer Points = Killer Points + (Victim Points / Killer Points)
                 &times; Weapon Modifier &times; 5
Victim Points = Victim Points - (Victim Points / Killer Points)
                 &times; Weapon Modifier &times; 5</pre>
        <br />
		Plus, the following point bonuses are available for completing objectives in some games:<br />
		<?php if(!empty($gameActions)) { ?>
		<table cellpadding="2" cellspacing="0" border="1" width="100%">
			<tr>
				<th><?php echo l('Game'); ?></th>
				<th><?php echo l('Player Action'); ?></th>
				<th><?php echo l('PlyrPlyr Action'); ?></th>
				<th><?php echo l('Team Action'); ?></th>
				<th><?php echo l('World Action'); ?></th>
				<th><?php echo l('Action'); ?></th>
				<th><?php echo l('Player Reward'); ?></th>
				<th><?php echo l('Team Reward'); ?></th>
			</tr>
			<?php
				foreach($gameActions as $a) {
					echo '<tr>';

					echo '<td>',$a['gamename'],'</td>';
					echo '<td>',$a['for_PlayerActions'],'</td>';
					echo '<td>',$a['for_PlayerPlayerActions'],'</td>';
					echo '<td>',$a['for_TeamActions'],'</td>';
					echo '<td>',$a['for_WorldActions'],'</td>';
					echo '<td>',$a['description'],'</td>';
					echo '<td>',$a['s_reward_player'],'</td>';
					echo '<td>',$a['s_reward_team'],'</td>';

					echo '<tr>';
				}
			?>
		</table>
		<?php }	?>
		<b>Note</b> The player who triggers an action may receive both the player reward and the team reward.
	</p>
	<a name="weaponmods"></a>
	<h1>What are all the weapon points modifiers ?</h1>
	<p>
		Weapon points modifiers are used to determine how many points you should gain or lose
		when you make a kill or are killed by another player. Higher modifiers indicate that more
		points will be gained when killing with that weapon (and similarly, more points will be lost
		when being killed <i>by</i> that weapon). Modifiers generally range from 0.00 to 2.00.<br />
		<br />
		<?php if(!empty($weaponModifiers)) { ?>
		<table cellpadding="2" cellspacing="0" border="1" width="100%">
			<tr>
				<th><?php echo l('Game'); ?></th>
				<th><?php echo l('Weapon'); ?></th>
				<th><?php echo l('Name'); ?></th>
				<th><?php echo l('Points Modifier'); ?></th>
			</tr>
			<?php
				foreach($weaponModifiers as $w) {
					echo '<tr>';

					echo '<td>',$w['gamename'],'</td>';
					echo '<td>',$w['code'],'</td>';
					echo '<td>',$w['name'],'</td>';
					echo '<td>',$w['modifier'],'</td>';

					echo '<tr>';
				}
				?>
		</table>
		<?php }	?>
	</p>
	<a name="set"></a>
	<h1>How can I set my profile data ?</h1>
	<p>
		Player profile options can be configured by saying the appropriate <b>SET</b> command
		while you are playing on a participating game server. To say commands, push your
		chat key and type the command text.<br />
		<br />
		Syntax: say <b>/set option value</b>.<br />
		<br />
		Acceptable "options" are:
		<ul>
			<li><b>realname</b><br>
				Sets your Real Name as shown in your profile.<br>
				Example: &nbsp;&nbsp; <b>/hls_set realname Joe Bloggs</b>
			</li>
			<li><b>email</b><br>
				Sets your E-mail Address as shown in your profile.<br>
				Example: &nbsp;&nbsp; <b>/hls_set email joe@hotmail.com</b>
			</li>
			<li><b>homepage</b><br>
				Sets your Home Page as shown in your profile.<br>
				Example: &nbsp;&nbsp; <b>/hls_set homepage http://www.geocities.com/joe/</b>
			</li>
			<li><b>icq</b><br>
				Sets your ICQ Number as shown in your profile.<br>
				Example: &nbsp;&nbsp; <b>/hls_set icq 123456789</b>
			</li>
			<li><b>myspace</b><br>
				Sets your myspace page as shown in your profile.<br>
				Example: &nbsp;&nbsp; <b>/hls_set myspace http://myspace.com/name</b>
			</li>
			<li><b>facebook</b><br>
				Sets your facebook page as shown in your profile.<br>
				Example: &nbsp;&nbsp; <b>/hls_set facebook http://facebook/name</b>
			</li>
			<li><b>jabber</b><br>
				Sets your jabber ID as shown in your profile.<br>
				Example: &nbsp;&nbsp; <b>/hls_set jabber ID</b>
			</li>
			<li><b>steamprofile</b><br>
				Sets your steamprofile URL as shown in your profile.<br>
				Example: &nbsp;&nbsp; <b>/hls_set steamprofile URL</b>
			</li>
		</ul>
		The server will respond with "SET command successful." If you get no response,
		it probably means you typed the command incorrectly.<br />
		<br />
		<b>Note</b> These are not standard Half-Life console commands. If you type them in the console,
		Half-Life will give you an error.
	</p>
	<a name="hideranking"></a>
	<h1>My rank is embarrassing. How can I opt out?</h1>
	<p>
		Say <b>/hls_hideranking</b> while playing on a participating game server.
		This will toggle you between being visible on the Player Rankings and being invisible.<br />
		<br />
		<b>Note</b> You will still be tracked and you can still view your Player Details page.
		Use the <a href="index.php?mode=search">Search</a> page to find yourself.
	</p>
	<a name="playersoverview"></a>
	<h1>What are active players etc. ?</h1>
	<p>
		At the players overview you have multiple options to select which players are show and which
		not.<br />
		The default is to show only active and non BOT players. Which means a player is active if you use the
		<b>player-activity.pl</b> script and define the <b>timeFrame</b> in which a player is
		still active.<br />
		Everytime a player does something a flag is set to 1 and a timestamp is set.
		Then the <b>player-activity.pl</b> checks if the player has a activity within the given
		timeFrame. If not the player is set to in-active.<br />
		BOTs are recored if the <b>IGNOREBOTS</b> option is set to <b>0</b>. This enables
		a additional option which displays those too. Otherwise no BOT is recorded or even shown.
	</p>
	</div>
</div>
