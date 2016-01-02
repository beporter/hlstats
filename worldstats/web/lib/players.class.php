<?php
/**
 * players class file
 * @package HLStats
 * @author Johannes 'Banana' Keßler
 * @copyright Johannes 'Banana' Keßler
 */

/**
 * Original development:
 *
 * + HLStats - Real-time player and clan rankings and statistics for Half-Life
 * + http://sourceforge.net/projects/hlstats/
 * + Copyright (C) 2001  Simon Garner
 *
 *
 * Additional development:
 *
 * + UA HLStats Team
 * + http://www.unitedadmins.com
 * + 2004 - 2007
 *
 *
 *
 * Current development:
 *
 * + Johannes 'Banana' Keßler
 * + http://hlstats.sourceforge.net
 * + 2007 - 2013
 *
 * This program is free software is licensed under the
 * COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
 *
 * You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
 * along with this program; if not, visit http://hlstats-community.org/License.html
 *
 */

/**
 * all information about the players for a given game
 * @package HLStats
 */
class Players {

	private $_entriesPerPage = 10;

	/**
	 * the options for queries
	 *
	 * @var array The options
	 */
	private $_option = array();

	/**
	 * the global DB Object
	 */
	private $_DB = false;

	/**
	 * set some vars and the game. Game code check is already done
	 *
	 * @param string $game The current game
	 */
	public function __construct() {

		$this->_DB = $GLOBALS['DB'];

		// set default values
		$this->setOption('page',1);
		$this->setOption('minkills','1');
	}

	/**
	 * set the options
	 *
	 * @param string $key The key/name for this option
	 * @param string $value The value for this option
	 */
	public function setOption($key,$value) {
		$this->_option[$key] = $value;
	}

	/**
	 * return for the given key the value
	 *
	 * @param string $key The key for the wanted value
	 *
	 * @return string The value for given key
	 */
	public function getOption($key) {
		$ret = false;

		if(isset($this->_option[$key])) {
			$ret = $this->_option[$key];
		}

		return $ret;
	}

	/**
	 * get the players for the current game
	 * for the players overview page
	 *
	 * @return array The players
	 */
	public function getPlayersOveriew() {
		$ret['data'] = array();
		$ret['pages'] = false;

		// construct the query with the given options
		$queryStr = "SELECT SQL_CALC_FOUND_ROWS pdt.*
			FROM `".DB_PREFIX."_playerDataTable` as pdt";

		$queryStr .= " WHERE pdt.kills >= '".$this->_DB->real_escape_string($this->_option['minkills'])."'";

		if(isset($this->_option['showToday']) && $this->_option['showToday'] === "1") {
			// should we show only players from today
			$startDay = time()-86400;
			$startDay = date("Y-m-d H:i:s", $startDay);
			$queryStr .= " AND pdt.lastConnect > '".$startDay."'";
		}


		$queryStr .= " ORDER BY ";
		if(!empty($this->_option['sort']) && !empty($this->_option['sortorder'])) {
			$queryStr .= " ".$this->_option['sort']." ".$this->_option['sortorder']."";
		}
		//$queryStr .=" ,t1.lastName ASC";

		// calculate the limit
		if($this->_option['page'] === 1) {
			$queryStr .=" LIMIT 0,".$this->_entriesPerPage;
		}
		else {
			$start = $this->_entriesPerPage*($this->_option['page']-1);
			$queryStr .=" LIMIT ".$start.",".$this->_entriesPerPage;
		}

		$query = $this->_DB->query($queryStr);
		if(SHOW_DEBUG && $this->_DB->error) var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				#$result['name'] = makeSavePlayerName($result['name']);

				$result['steamProfile'] = getSteamProfileUrl($result['uniqueID']);

				$pl[$result['uniqueID']] = $result;
			}
			$ret['data'] = $pl;
		}

		// get the max count for pagination
		$query = $this->_DB->query("SELECT FOUND_ROWS() AS 'rows'");
		$result = $query->fetch_assoc();
		$ret['pages'] = (int)ceil($result['rows']/$this->_entriesPerPage);

		return $ret;
	}

	/**
	 * get the top player for this game
	 * @todo: right now sorted by skill. Future could be other sorting stuff
	 *
	 * @return array The playerinformation
	 */
	public function topPlayer() {
		$ret = false;

		$queryStr = "SELECT t1.playerId AS playerId,
						t1.lastName AS lastName,
						t1.isBot AS isBot
					FROM `".DB_PREFIX."_Players` AS t1
					WHERE t1.game= '".$this->_DB->real_escape_string($this->_game)."'
					AND t1.hideranking = 0";

		if($this->g_options['IGNOREBOTS'] === "1") {
			$queryStr .= " AND t1.isBot = 0";
		}

		$queryStr .= " ORDER BY t1.skill DESC LIMIT 1";

		$query = $this->_DB->query($queryStr);
		if(SHOW_DEBUG && $this->_DB->error) var_dump($this->_DB->error);
		if(!empty($query) && $query->num_rows > 0) {
			$ret = $query->fetch_assoc();
		}

		return $ret;
	}



	/**
	 * get the player count per day
	 *
	 * @return array
	 */
	public function getPlayerCountPerDay() {
		$data = array();

		$queryStr = "SELECT DATE(ee.eventTime) AS eventTime,
							p.playerId
						FROM `".DB_PREFIX."_Events_Entries` AS ee
						INNER JOIN ".DB_PREFIX."_Players as p ON ee.playerId = p.playerId
						INNER JOIN ".DB_PREFIX."_PlayerUniqueIds as pu ON ee.playerId = pu.playerId
						WHERE p.game = '".$this->_DB->real_escape_string($this->_game)."'";

		// should we show all the players or not
		if(isset($this->_option['showall']) && $this->_option['showall'] === "1") {
			$queryStr .= " ";
		}
		else {
			$queryStr .= " AND p.active = '1'";
		}

		// should we hide the bots
		if($this->_option['showBots'] === "0") { # this is not the config setting, it is the link setting
			$queryStr .= " AND pu.uniqueID not like 'BOT:%'";
		}

		if(isset($this->_option['showToday']) && $this->_option['showToday'] === "1") {
			// should we show only players from today
			$startDay = time()-86400;
			$startDay = date("Y-m-d",$startDay);
			$queryStr .= " AND `eventTime` > '".$startDay."'";
		}
		elseif($this->g_options['DELETEDAYS'] !== "0") {
			$startDay = time()-(86400*$this->g_options['DELETEDAYS']);
			$startDay = date("Y-m-d",$startDay);
			$queryStr .= " AND `eventTime` > '".$startDay."'";
		}

		$queryStr .= " ORDER BY `eventTime`";

		$query = $this->_DB->query($queryStr);
		if(SHOW_DEBUG && $this->_DB->error) var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while ($result = $query->fetch_assoc()) {
				// we group by day and playerId
				$result['eventTime'] = strftime('%d %b %Y',strtotime($result['eventTime']));
				$data[$result['eventTime']][$result['playerId']] = $result['playerId'];
			}
		}
		$query->free();

		return $data;
	}

	/**
	 * get the most time online
	 *
	 * @todo To complete
	 *
	 * @return array The data
	 */
	public function getMostTimeOnline() {
		$data = array();

		$queryStr = "SELECT p.lastName AS name,
					est.playerId AS pId,
					sum(TIME_TO_SEC(est.time)) as tTime,
					p.skillchangeDate
				FROM `".DB_PREFIX."_Events_StatsmeTime` AS est
				LEFT JOIN `".DB_PREFIX."_Servers` AS s
					ON s.serverId = est.serverId
				LEFT JOIN `".DB_PREFIX."_Players` AS p
					ON p.playerId = est.playerId
				WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'";


		// should we show all the players or not
		if(isset($this->_option['showall']) && $this->_option['showall'] === "1") {
			$queryStr .= " ";
		}
		else {
			$queryStr .= " AND p.active = '1'";
		}

		if(isset($this->_option['showToday']) && $this->_option['showToday'] === "1") {
			// should we show only players from today
			$startDay = time()-86400;
			$queryStr .= " AND p.skillchangeDate = '".$startDay."'";
		}

		$queryStr .= " GROUP BY est.playerId
		 		ORDER BY `tTime` DESC
				LIMIT 10";

		$query = $this->_DB->query($queryStr);

		if(SHOW_DEBUG && $this->_DB->error) var_dump($this->_DB->error);
		while($result = $query->fetch_assoc()) {
			$data[$result['pId']] = array('timeSec' => $result['tTime'],
										'playerName' => $result['name']);
		}
		$query->free();

		return $data;
	}

	/**
	 * data to create a player timeline over all players available
	 * @return array
	 */
	public function getTimeline() {
		$data = array();

		$queryStr = "SELECT DATE(ee.eventTime) AS eventTime,
							p.playerId, p.lastName
						FROM `".DB_PREFIX."_Events_Entries` AS ee
						INNER JOIN ".DB_PREFIX."_Players as p ON ee.playerId = p.playerId
						INNER JOIN ".DB_PREFIX."_PlayerUniqueIds as pu ON ee.playerId = pu.playerId
						WHERE p.game = '".$this->_DB->real_escape_string($this->_game)."'
						ORDER BY eventTime DESC ,p.lastName";

		$query = $this->_DB->query($queryStr);

		if(SHOW_DEBUG && $this->_DB->error) var_dump($this->_DB->error);
		while($result = $query->fetch_assoc()) {
			$result['eventTime'] = strftime('%d %b %Y',strtotime($result['eventTime']));
			$data[$result['eventTime']][$result['playerId']] = $result;
		}
		$query->free();

		return $data;
	}
}
?>
