<?php
/**
 * display a timeline of all available players and their dates
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

require('class/players.class.php');
$playersObj = new Players($game);

pageHeader(
	array($gamename, l('Players timeline')),
	array($gamename => "index.php?game=$game",
			l("Player Rankings") => "index.php?mode=players&game=$game",
			l("Players timeline")=>"")
);

$timeline = $playersObj->getTimeline();

?>
<div id="sidebar">
	<h1><?php echo l('Options'); ?></h1>
	<div class="left-box">
		<ul class="sidemenu">
			<li>
				<a href="?mode=players&amp;game=<?php echo $game; ?>"><?php echo l('Player Rankings'); ?></a>
			</li>
		</ul>
	</div>
	<h1><?php echo l('Game'); ?></h1>
	<div class="left-box">
		<img src="hlstatsimg/game-<?php echo $game; ?>-big.png" alt="<?php echo $game; ?>" title="<?php echo $game; ?>" width="100px" />
	</div>
</div>
<div id="main">
	<div class="content">
	<?php
	if(!empty($timeline)) {
		foreach($timeline as $day=>$entry) {
			echo '<h1>',$day,'</h1>';
			echo '<ul>';
			foreach($entry as $player) {
				echo '<li><a href="index.php?mode=playerinfo&amp;player=',$player['playerId'],'">',makeSavePlayerName($player['lastName']),'</a></li>';
			}
			echo '</ul>';
		}
	}
	?>
	</div>
</div>
