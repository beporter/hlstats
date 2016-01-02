<?php
/**
 * chart class file. uses the pChart library
 * @package HLStats
 * @author Johannes 'Banana' Keßler
 */


/**
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

/**
 * the chart class to build the chrats with the pChart library
 * @package HLStats
 */
class Chart {

	/**
	 * the current game
	 *
	 * @var string The game
	 */
	private $_game = false;

	/**
	 * the options for this class
	 *
	 * @var array The options
	 */
	private $_option = array();

	/**
	 * the data object of pChart
	 *
	 * @var object The pChart data library
	 */
	private $_pData = false;

	/**
	 * the pChart object
	 *
	 * @var object The pChart pchart library
	 */
	private $_pChart = false;
	
	/**
	 * the used data to draw the chart
	 * @var array
	 */
	private $_currentChartData = array();

	/**
	 * the global DB Object
	 */
	private $_DB = false;

	/**
	 * load up and set default values
	 *
	 * @param string $game The game code
	 */
	public function __construct($game) {

		$this->_DB = $GLOBALS['DB'];

		if(!empty($game)) {
			$this->_game = $game;
		}
		else {
			new Exception("Game is missing for Players.class");
		}

		$this->setOption('width',660);
		$this->setOption('height',300);

		// set the current day value
		$this->setOption('curDate',date("Ymd"));
	}

	/**
	 * set a given option
	 *
	 * @param string $key The option name
	 * @param string $value The option value
	 *
	 * @return void
	 */
	public function setOption($key,$value) {
		if($key !== "" && $value !== "") {
			$this->_option[$key] = $value;
		}
	}

	/**
	 * either return the value or false
	 *
	 * @param string $key The param to get
	 *
	 * @return mixed The value or false
	 */
	public function getOption($key) {
		if(isset($this->_option[$key])) {
			return $this->_option[$key];
		}

		return false;
	}
	
	/**
	 * return the currently used chart data returned from the sub class
	 * this is the raw data returned form the class
	 * @return array
	 */
	public function getChartData() {
		return $this->_currentChartData;
	}

	/**
	 * create the given chart
	 *
	 * @param string $mode The chart to create
	 * @param string $extra Extra parameter to creat the chart eg. playerid
	 *
	 * @return string The path to the created chart image
	 */
	public function getChart($mode,$extra=false) {

		$chart = false;
		$modeString = '';

		switch($mode) {
			case 'playerActivity':
				$modeString = 'playerActivity';
				$methodName = '_getPlayerActivity';
			break;

			case 'mostTimeOnline':
				$modeString = 'mostTimeOnline';
				$methodName = '_mostTimeOnline';
			break;

			case 'playTimePerDay':
				if(empty($extra)) { return false; }
				$modeString = 'playTimePerDay';
				$methodName = '_getPlayerTimePerDay';
			break;

			case 'killsPerDay':
				if(empty($extra)) { return false; }
				$modeString = 'killsPerDay';
				$methodName = '_getKillsPerDay';
			break;

			default:
			//nothing
		}

		$this->setOption('chartFile','tmp/'.$this->_game.'-'.$modeString.'-'.$this->_option['curDate'].".png");

		// check if we have already a picture
		// create one only once a day
		if(file_exists($this->_option['chartFile']) && SHOW_DEBUG === false) {
			$chart = $this->_option['chartFile'];
		}
		else {
			$this->_loadClasses();

			// remove old charts
			$this->_cleanOldCharts($this->_game.'-'.$modeString);
			// create the chart
			$chart = $this->$methodName($extra);
		}

		return $chart;
	}

	/**
	 * create the kills per day chart for the given player
	 *
	 * @param int $playerId The player ID
	 *
	 * @return string Path to the created image chart
	 */
	private function _getKillsPerDay($playerId) {
		$ret = false;

		if(!in_array('Player',get_declared_classes())) {
			require 'player.class.php';
		}
		$playerObj = new Player($playerId,false,$this->_game);
		$data = $playerObj->getKillsPerDay();
		
		# used to access the loaded data for other uses
		$this->_currentChartData = $data;
		
		if(!empty($data)) {

			$c = 0;
			$kills = array();
			$xLine = array();

			$kCount = count($data);
			foreach($data as $entry) {
				$kills[] = $entry['dayEvents'];

				// this shows the date only every 5 days
				//$xLine[] = $entry['eventDay'];
				// if less then 15 days show everytime
				if($c % 4 == 0 || $kCount < 15) { $xLine[] = $entry['eventDay']; }
				else { $xLine[] = ''; }
				$c++;
			}

			// add the kills
			$this->_pData->AddPoint($kills,'1');
			$this->_pData->AddSerie('1');
			$this->_pData->SetSerieName(l("Kills"),'1');

			// the dates for x axe
			$this->_pData->AddPoint($xLine,'x');
			$this->_pData->SetAbsciseLabelSerie("x");

			// create the canvas
			$this->_pChart->setFontProperties("class/pchart/Fonts/tahoma.ttf",8);
			$this->_pChart->setGraphArea(50,30,$this->_option['width']-10,$this->_option['height']-80);
			$this->_pChart->drawFilledRoundedRectangle(3,3,$this->_option['width']-3,$this->_option['height']-3,5,240,240,240);
			$this->_pChart->drawGraphArea(250,250,250,TRUE);
			$this->_pChart->drawScale($this->_pData->GetData(),$this->_pData->GetDataDescription(),SCALE_START0,150,150,150,true,30,2,true);
			$this->_pChart->drawGrid(4,TRUE,230,230,230,50);


			// draw the bar graph
			$this->_pChart->drawBarGraph($this->_pData->GetData(),$this->_pData->GetDataDescription(),TRUE);


			// Finish the graph
			//$this->_pChart->setFontProperties("class/pchart/Fonts/tahoma.ttf",8);
			$this->_pChart->drawLegend(10,$this->_option['height']-40,$this->_pData->GetDataDescription(),250,250,250);
			$this->_pChart->setFontProperties("class/pchart/Fonts/tahoma.ttf",10);
			$this->_pChart->drawTitle(0,20,l("Player kills per day"),50,50,50,$this->_option['width']);

			$this->_pChart->Render($this->_option['chartFile']);

			$ret =  $this->_option['chartFile'];
		}

		return $ret;
	}

	/**
	 * create the player time per day chart
	 *
	 * @todo to complete
	 */
	private function _getPlayerTimePerDay($playerId) {
		if(!in_array('Player',get_declared_classes())) {
			require 'player.class.php';
		}
		$playerObj = new Player($playerId,false,$this->_game);
		$data = $playerObj->getPlaytimePerDayData();
	}

	/**
	 * get the chart for player activity
	 * @param object $playersObj The already existing players Object
	 *
	 * @return the path to the image
	 */
	private function _getPlayerActivity($playersObj=false) {
		$ret = false;

		if(!in_array('Players',get_declared_classes())) {
			require 'players.class.php';
		}

		$cl = 'Players';
		if(!($playersObj instanceof $cl)) {
			$playersObj = new Players($this->_game);
		}
		$data = $playersObj->getPlayerCountPerDay();
		
		# used to access the loaded data for other uses
		$this->_currentChartData = $data;

		if(!empty($data)) {

			$c = 0;
			$xLine = array();
			$connects = array();

			// we need only the count for each day
			$dcCount = count($data);
			foreach($data as $d=>$e) {
				$connects[] = count($e);

				// this shows the date only every 5 days
				// if less then 15 days show everytime
				if($c % 4 == 0 || $dcCount < 15) { $xLine[] = $d; }
				else { $xLine[] = ''; }
				$c++;
			}
			
			// add the players
			$this->_pData->AddPoint($connects,'1');
			$this->_pData->AddSerie('1');
			$this->_pData->SetSerieName(l("Players per day"),'1');

			// the dates for x axe
			$this->_pData->AddPoint($xLine,'x');
			$this->_pData->SetAbsciseLabelSerie("x");

			// create the canvas
			$this->_pChart->setFontProperties("class/pchart/Fonts/tahoma.ttf",8);
			$this->_pChart->setGraphArea(50,30,$this->_option['width']-10,$this->_option['height']-80);
			$this->_pChart->drawFilledRoundedRectangle(3,3,$this->_option['width']-3,$this->_option['height']-3,5,240,240,240);
			$this->_pChart->drawGraphArea(250,250,250,TRUE);
			$this->_pChart->drawScale($this->_pData->GetData(),$this->_pData->GetDataDescription(),SCALE_START0,150,150,150,TRUE,30,2,true);
			$this->_pChart->drawGrid(4,TRUE,230,230,230,50);

			// display only more the 3 days as a curve, otherwise as a bar
			if(count($xLine) >= 4) {
				// Draw the cubic curve graph
				$this->_pChart->drawCubicCurve($this->_pData->GetData(),$this->_pData->GetDataDescription(),.1);
			}
			else {
				// draw the bar graph
				$this->_pChart->drawBarGraph($this->_pData->GetData(),$this->_pData->GetDataDescription(),TRUE);
			}

			// Finish the graph
			//$this->_pChart->setFontProperties("class/pchart/Fonts/tahoma.ttf",8);
			$this->_pChart->drawLegend(10,$this->_option['height']-40,$this->_pData->GetDataDescription(),250,250,250);
			$this->_pChart->setFontProperties("class/pchart/Fonts/tahoma.ttf",10);
			$this->_pChart->drawTitle(0,20,l("Players per day"),50,50,50,$this->_option['width']);


			$this->_pChart->Render($this->_option['chartFile']);

			$ret =  $this->_option['chartFile'];
		}

		return $ret;
	}

	/**
	 * create the image for player activity
	 * @param object $playersObj The already existing players Object
	 *
	 * @return the path to the image
	 */
	private function _mostTimeOnline($playersObj=false) {
		
		$ret = false;

		if(!in_array('Players',get_declared_classes())) {
			require 'players.class.php';
		}

		$cl = 'Players';
		if(!($playersObj instanceof $cl)) {
			$playersObj = new Players($this->_game);
		}
		$data = $playersObj->getMostTimeOnline();
		
		# used to access the loaded data for other uses
		$this->_currentChartData = $data;
		
		if(!empty($data)) {
		
			$players = array();
			$times = array();
			foreach($data as $playerId=>$entry) {
				$players[] = $entry['playerName'];
				$times[] = $entry['timeSec']/60/60;
			}
		
			// add the players
			$this->_pData->AddPoint($times,'1');
			$this->_pData->AddSerie('1');
			$this->_pData->SetSerieName(l("Most time online (hours)"),'1');
		

			// the dates for x axe
			$this->_pData->AddPoint($players,'x');
			$this->_pData->SetAbsciseLabelSerie("x");

			$this->_pChart->setFontProperties("class/pchart/Fonts/tahoma.ttf",8);
			$this->_pChart->setGraphArea(50,30,$this->_option['width']-10,$this->_option['height']-100);
			$this->_pChart->drawFilledRoundedRectangle(3,3,$this->_option['width']-3,$this->_option['height']-3,5,240,240,240);
			$this->_pChart->drawGraphArea(250,250,250,true);
			$this->_pChart->drawScale($this->_pData->GetData(),$this->_pData->GetDataDescription(),SCALE_START0,150,150,150,TRUE,30,2,TRUE);
			$this->_pChart->drawGrid(4,false,230,230,230,250);


			// draw the bar graph
			$this->_pChart->drawBarGraph($this->_pData->GetData(),$this->_pData->GetDataDescription(),TRUE);

			#  // Finish the graph
			//$this->_pChart->setFontProperties("class/pchart/Fonts/tahoma.ttf",8);
			$this->_pChart->drawLegend(10,$this->_option['height']-40,$this->_pData->GetDataDescription(),250,250,250);
			$this->_pChart->setFontProperties("class/pchart/Fonts/tahoma.ttf",10);
			$this->_pChart->drawTitle(0,20,l("Most time online (hours)"),50,50,50,$this->_option['width']);
		
			$this->_pChart->Render($this->_option['chartFile']);

			$ret =  $this->_option['chartFile'];
		}

		return $ret;
	}

	/**
	 * load the required pChart classes
	 *
	 * @return void
	 */
	private function _loadClasses() {
		if($this->_pData == false) {
			require_once('class/pchart/pData.class.php');
			$this->_pData = new pData();
		}

		if($this->_pChart == false) {
			require_once('class/pchart/pChart.class.php');
			$this->_pChart = new pChart($this->_option['width'],$this->_option['height']);
		}
	}

	/**
	 * cleans old chart data to avoid data waste
	 *
	 * @return void
	 */
	private function _cleanOldCharts($name) {
		$data = glob('tmp/'.$name.'*');
		if(!empty($data)) {
			foreach($data as $c) {
				unlink($c);
			}
		}
	}


}
?>
