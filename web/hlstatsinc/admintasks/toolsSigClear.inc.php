<?php
/**
 * remove old signatures
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

pageHeader(array(l("Admin"),l('Signature cleanup')), array(l("Admin")=>"index.php?mode=admin",l('Signature cleanup')=>''));
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
	<h1><?php echo l('Signature cleanup'); ?></h1>
	<?php
		// remove all the signatures which are older then today
		$timeFrame = time()-84600;
		$allSig = glob('signatures/preRender/*.png');
		$removeCount = 0;
		if(!empty($allSig)) {
			foreach($allSig as $s) {
				$ct = filectime($s);
				if($ct < $timeFrame) {
					unlink($s);
					$removeCount++;
				}
			}
		}

		if(!empty($removeCount)) {
			echo $removeCount." ".l('old signatures removed !');
		}
		else {
			echo '<p class="info">'.l('Nothing removed.').'</p>';
		}
	?>
	</div>
</div>
