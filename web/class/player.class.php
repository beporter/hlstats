<?php
/**
 * player class file
 * @package HLStats
 * @author Johannes 'Banana' Keßler
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
 * + 2007 - 2012
 *
 * This program is free software is licensed under the
 * COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
 *
 * You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
 * along with this program; if not, visit http://hlstats-community.org/License.html
 *
 */

/**
 * all information about a player is handled with this class
 * @package HLStats
 */
class Player {
	/**
	 * the player id
	 * @var int $playerId The player id
	 */
	public $playerId = 0;

	/**
	 * the game
	 * need for player lookup via uniqueid
	 *
	 * @var string $_game The game code
	 */
	private $_game = false;

	/**
	 * the player data
	 * non empty if successfull
	 * @var array $_playerData The playerData
	 *
	 */
	private $_playerData = false;

	/**
	 * the options
	 *
	 * @var array $_option The options needed for this class
	 */
	private $_option = array();

	/**
	 * fields which has the data from an input
	 * eg. used at player details update
	 *
	 * @var array $_saveFields
	 */
	private $_saveFields = array();

	/**
	 * the system options
	 *
	 * @var array The system options
	 */
	private $g_options = array();

	/**
	 * the global DB Object
	 */
	private $_DB = false;

	/**
	 * mapping which game code has steam stats.
	 * @var array gamecode=>PublicSteamGameCode
	 * https://partner.steamgames.com/documentation/community_data
	 */
	private $_statsGames = array('css' => 'CS:S',
				'l4d' => 'L4D',
				'hl2mp' => 'HL2',
				'l4d2' => 'L4D2',
				'tf2' => 'TF2',
				'css' => 'CS:S'
			);

	/**
	 * load the player id
	 *
	 * @param int $id The player id
	 * @param string $mode If the player lookup is via playerId oder uniqueId
	 * @param string $game The game code
	 *
	 * @return boolean $ret Either true or false
	 */
	public function __construct($id,$mode,$game=false) {
		$ret = false;

		$this->_DB = $GLOBALS['DB'];

		if(!empty($id)) {
			$this->_game = $game;

			if($mode === false) {
				$this->playerId = $id;
			}
			elseif($mode === true && !empty($game)) {

				$this->_lookupPlayerIdFromUniqeId($id);
				if(empty($this->playerId)) {
					throw new Exception("PlayerID can't be looked up via uniqueid in Player.class");
				}
			}
			else {
				throw new Exception("Player mode is missing for Player.class");
			}
		}
		else {
			throw new Exception("Player ID or game is missing for Player.class");
		}

		$ret = $this->_load();

		// set some default values
		$this->setOption('page',1);

		global $g_options;
		$this->g_options = $g_options;

		return $ret;
	}

	/**
	 * return given param from player
	 *
	 * @param string $param The information key to get
	 *
	 * @return mixed Either false or the value for the given key
	 */
	public function getParam($param) {
		$ret = false;

		if(!empty($param)) {
			if(isset($this->_playerData[$param])) {
				$ret = $this->_playerData[$param];
			}
		}

		return $ret;
	}

	/**
	 * set the given option to the given value
	 *
	 * @param string $key The key for this option
	 * @param string $value The value for the given key
	 *
	 * @return void
	 */
	public function setOption($key,$value) {
		if(!empty($key)) {
			$this->_option[$key] = $value;
		}
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
	 * get the player history for the events
	 * I know this is big, but I don't think there is a better way.
	 *
	 * @param string Special parameters if needed
	 * @return array The history
	 */
	public function getEventHistory($special = false) {
		$ret = array('data' => array(),
					'pages' => false);

		$queryStr = "SELECT SQL_CALC_FOUND_ROWS
					'".l('Team Bonus')."' AS eventType,
					eventTime,
					CONCAT('".l('My team received a points bonus of')." ', bonus, ' ".l('for triggering')." \"', `".DB_PREFIX."_Actions`.`description`, '\"') AS eventDesc,
					`".DB_PREFIX."_Servers`.`name` AS serverName,
					map
					FROM `".DB_PREFIX."_Events_TeamBonuses` AS t
					LEFT JOIN `".DB_PREFIX."_Actions` ON
						t.actionId = `".DB_PREFIX."_Actions`.`id`
					LEFT JOIN `".DB_PREFIX."_Servers` ON
						`".DB_PREFIX."_Servers`.`serverId` = t.serverId
					WHERE
						t.playerId=".$this->_DB->real_escape_string($this->playerId)."";
		$queryStr .= " UNION ALL
			SELECT '".l('Connect')."' AS eventType,
				eventTime,
				CONCAT('".l('I connected to the server')."') AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_Connects` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			WHERE
				t.playerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Disconnect')."' AS eventType,
				eventTime,
				'".l('I left the game')."' AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_Disconnects` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			WHERE
				t.playerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT 'Entry' AS eventType,
				eventTime,
				'".l('I entered the game')."' AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_Entries` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			WHERE
				t.playerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Kill')."' As eventType,
			eventTime,
			CONCAT('".l('I killed')." <a href=\"index.php?mode=playerinfo&player=', victimId, '\">', `".DB_PREFIX."_Players`.`lastName`, '</a>', ' ".l('with')." ', weapon) AS eventDesc,
			`".DB_PREFIX."_Servers`.`name` AS serverName,
			map
		FROM `".DB_PREFIX."_Events_Frags` AS t
		LEFT JOIN `".DB_PREFIX."_Servers` ON
			`".DB_PREFIX."_Servers`.`serverId` = t.serverId
		LEFT JOIN `".DB_PREFIX."_Players` ON
			`".DB_PREFIX."_Players`.`playerId` = t.victimId
		WHERE
			t.killerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Death')."' AS eventType,
				eventTime,
				CONCAT('<a href=\"index.php?mode=playerinfo&player=', killerId, '\">', `".DB_PREFIX."_Players`.`lastName`, '</a>', ' ".l('killed me with')." ', weapon) AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_Frags` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			LEFT JOIN `".DB_PREFIX."_Players` On
				`".DB_PREFIX."_Players`.`playerId` = t.killerId
			WHERE
				t.victimId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Team Kill')."' AS eventType,
				eventTime,
				CONCAT('".l('I killed teammate')." <a href=\"index.php?mode=playerinfo&player=', victimId, '\">', `".DB_PREFIX."_Players`.`lastName`, '</a>', ' ".l('with')." ', weapon) AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_Teamkills` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			LEFT JOIN `".DB_PREFIX."_Players` On
				`".DB_PREFIX."_Players`.`playerId` = t.victimId
			WHERE
				t.killerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Friendly Fire')."' AS eventType,
				eventTime,
				CONCAT('".l('My teammate')." <a href=\"index.php?mode=playerinfo&player=', killerId, '\">', `".DB_PREFIX."_Players`.`lastName`, '</a>', ' ".l('killed me with')." ', weapon) AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_Teamkills` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			LEFT JOIN `".DB_PREFIX."_Players` On
				`".DB_PREFIX."_Players`.`playerId` = t.killerId
			WHERE
				t.victimId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Role')."' AS eventType,
				eventTime,
				CONCAT('".l("I changed role to")." ', role) AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_ChangeRole` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			WHERE
				t.playerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Name')."' AS eventType,
				eventTime,
				CONCAT('".l('I changed my name from')." \"', oldName, '\" ".l('to')." \"', newName, '\"') AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_ChangeName` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			WHERE
				t.playerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Action')."' AS eventType,
				eventTime,
				CONCAT('".l('I received a points bonus of')." ', bonus, ' ".l('for triggering')." \"', `".DB_PREFIX."_Actions`.`description`, '\"') AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_PlayerActions` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			LEFT JOIN `".DB_PREFIX."_Actions` ON
				`".DB_PREFIX."_Actions`.`id` = t.actionId
			WHERE
				t.playerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Action')."' AS eventType,
				eventTime,
				CONCAT('".l('I received a points bonus of')." ', bonus, ' ".l('for triggering')." \"', `".DB_PREFIX."_Actions`.`description`, '\" ".l('against')." <a href=\"index.php?mode=playerinfo&player=', victimId, '\">', `".DB_PREFIX."_Players`.`lastName`, '</a>') AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_PlayerPlayerActions` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			LEFT JOIN `".DB_PREFIX."_Actions` ON
				`".DB_PREFIX."_Actions`.`id` = t.actionId
			LEFT JOIN `".DB_PREFIX."_Players` ON
				`".DB_PREFIX."_Players`.`playerId` = t.victimId
			WHERE
				t.playerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Action')."' AS eventType,
				eventTime,
				CONCAT('<a href=\"index.php?mode=playerinfo&player=', t.playerId, '\">', `".DB_PREFIX."_Players`.`lastName`, '</A> ".l('triggered')." \"', `".DB_PREFIX."_Actions`.`description`, '\" ".l('against me')."') AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_PlayerPlayerActions` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			LEFT JOIN `".DB_PREFIX."_Actions` ON
				`".DB_PREFIX."_Actions`.`id` = t.actionId
			LEFT JOIN `".DB_PREFIX."_Players` ON
				`".DB_PREFIX."_Players`.`playerId` = t.playerId
			WHERE
				t.victimId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Suicide')."' AS eventType,
				eventTime,
				CONCAT('".l('I committed suicide with')." \"', weapon, '\"') AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS servername,
				map
			FROM `".DB_PREFIX."_Events_Suicides` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			WHERE
				t.playerId=".$this->_DB->real_escape_string($this->playerId)."
		";
		$queryStr .= " UNION ALL
			SELECT '".l('Team')."' AS eventType,
				eventTime,
				IF(`".DB_PREFIX."_Teams`.`name` IS NULL,
					CONCAT('".l('I joined team')." \"', team, '\"'),
					CONCAT('".l('I joined team')." \"', team, '\" (', `".DB_PREFIX."_Teams`.`name`, ')')
				) AS eventDesc,
				`".DB_PREFIX."_Servers`.`name` AS serverName,
				map
			FROM `".DB_PREFIX."_Events_ChangeTeam` AS t
			LEFT JOIN `".DB_PREFIX."_Servers` ON
				`".DB_PREFIX."_Servers`.`serverId` = t.serverId
			LEFT JOIN `".DB_PREFIX."_Teams` ON
				`".DB_PREFIX."_Teams`.`code` = t.team
			WHERE
				t.playerId=".$this->_DB->real_escape_string($this->playerId)."
		";

		if(!empty($special)) {
			if($special === "lastEvent") {
				// we want the last event
				$queryStr .= " ORDER BY eventTime DESC";
				$queryStr .= " LIMIT 1";

				$query = $this->_DB->query($queryStr);
				$result = $query->fetch_assoc();
				$ret = $result['eventTime'];
			}
		}
		else {
			$queryStr .= " ORDER BY ";
			if(!empty($this->_option['sort']) && !empty($this->_option['sortorder'])) {
				$queryStr .= " ".$this->_option['sort']." ".$this->_option['sortorder']."";
			}

			if($this->_option['page'] === 1) {
				$queryStr .= " LIMIT 0,50";
			}
			else {
				$start = 50*($this->_option['page']-1);
				$queryStr .= " LIMIT ".$start.",50";
			}

			$query = $this->_DB->query($queryStr);
			if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
			if($query->num_rows > 0) {
				while($result = $query->fetch_assoc()) {
					$ret['data'][] = $result;
				}
			}
			$query->free();

			// get the max count for pagination
			$query = $this->_DB->query("SELECT FOUND_ROWS() AS 'rows'");
			$result = $query->fetch_assoc();
			$ret['pages'] = (int)ceil($result['rows']/50);
			$query->free();
		}
		return $ret;
	}

	/**
	 * get the player Chat historydata if we have any
	 *
	 * @return array
	 */
	public function getChatHistory() {
		$ret = array('data' => array(),
					'pages' => false);

		$queryStr = "SELECT SQL_CALC_FOUND_ROWS
	 			ec.eventTime,
	 			CONCAT('".l('I said')." \"', ec.message, '\"') AS message,
	 			s.name AS serverName, ec.map
				FROM `".DB_PREFIX."_Events_Chat` AS ec
				LEFT JOIN `".DB_PREFIX."_Servers` AS s
					ON s.serverId = ec.serverId
			WHERE ec.playerId = ".$this->_DB->real_escape_string($this->playerId)."";

		$queryStr .= " ORDER BY ";
		if(!empty($this->_option['sort']) && !empty($this->_option['sortorder'])) {
			$queryStr .= " ".$this->_option['sort']." ".$this->_option['sortorder']."";
		}

		if($this->_option['page'] === 1) {
			$queryStr .= " LIMIT 0,50";
		}
		else {
			$start = 50*($this->_option['page']-1);
			$queryStr .= " LIMIT ".$start.",50";
		}

		$query = $this->_DB->query($queryStr);
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$ret['data'][] = $result;
			}
		}

		//get data for pagination
		$query = $this->_DB->query("SELECT FOUND_ROWS() AS 'rows'");
		$result = $query->fetch_assoc();
		$ret['pages'] = (int)ceil($result['rows']/50);

		return $ret;
	}

	/**
	 * load the full information needed for player info page
	 *
	 * @return void
	 */
	public function loadFullInformation() {
		// load additional stuff and save it into the _playerData array
		$this->_getUniqueIds();
		$this->_getLastConnect();
		$this->_getMaxConnectTime();
		$this->_getAvgPing();
		$this->_getTeamkills();
		$this->_getWeaponaccuracy();
		$this->_getAliasTable();
		$this->_getActions();
		$this->_getPlayerPlayerActions();
		$this->_getTeamSelection();
		$this->_getWeaponUsage();
		$this->_getWeaponStats();
		$this->_getWeaponTarget();
		$this->_getMaps();
		$this->_getPlayerKillStats();
		$this->_getRoleSelection();
		#$this->_getHistats();

		$this->_getRank('rankPoints');
		$this->_getRank('allPlayers');
		$this->_getRank('allwithoutBot');
	}

	/**
	 * get the play-time for this player per day
	 * @todo: to complete
	 *
	 * @return array The playerTime data for the chart
	 */
	public function getPlaytimePerDayData() {
		$ret = false;
		$query = $this->_DB->query("SELECT est.*,
				TIME_TO_SEC(est.time) as tTime
			FROM `".DB_PREFIX."_Events_StatsmeTime` AS est
			LEFT JOIN `".DB_PREFIX."_Servers` AS s
				ON s.serverId = est.serverId
			WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
				AND est.playerId = '".$this->_DB->real_escape_string($this->playerId)."'");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$ret[] = $result;
			}
		}

		return $ret;
	}

	/**
	 * get the kills per day
	 *
	 * @return $ret array
	 */
	public function getKillsPerDay() {
		$ret = false;

		// the extract function does not support year_month_day.....
		$query = $this->_DB->query("SELECT COUNT(*) AS dayEvents,
							 CONCAT(EXTRACT(YEAR FROM `eventTime`),'-',EXTRACT(MONTH FROM `eventTime`),'-',EXTRACT(DAY FROM `eventTime`)) AS eventDay
							 FROM `".DB_PREFIX."_Events_Frags`
							 WHERE `killerId` = '".$this->_DB->real_escape_string($this->playerId)."'
							 GROUP BY eventDay
							 ORDER BY eventTime");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$ret[] = $result;
			}
		}

		return $ret;
	}

	/**
	 * this is used to check if we have missing fields in an input
	 * @param array $params
	 * @return array $ret
	 */
	public function checkFields($params) {
		$ret = false;

		$missing = array();
		$this->_saveFields = array();
		if(!empty($params)) {
			foreach ($params as $k=>$v) {
				$v = trim($v);

				// check if we have a req_key
				if(strstr($k,'req_')) {
					$newKey = str_replace('req_','',$k);
					if($v !== "") {
						$this->_saveFields[$newKey] = $v;
					}
					else {
						$missing[] = $newKey; // is missing
					}
				}
				else {
					$this->_saveFields[$k] = $v;
				}
			}
		}

		if(!empty($missing)) {
			$ret = $missing;
		}
		else {
			$ret = true;
		}
		return $ret;
	}

	/**
	 * update the current loaded player with the data from $_saveFields
	 * if empty $_saveFields to nothin
	 * @return boolean $ret
	 */
	public function updatePlayerProfile() {
		$ret = false;

		// we do not have to update anything
		if(empty($this->_saveFields)) return true;

		// check if we have to reset or delete from clan
		$deleteFromClan = false;
		if(isset($this->_saveFields['deletefromclan']) && $this->_saveFields['deletefromclan'] == "1") {
			$deleteFromClan = true;
		}
		unset($this->_saveFields['deletefromclan']);
		$resetStats = false;
		if(isset($this->_saveFields['resetstats']) && $this->_saveFields['resetstats'] == "1") {
			$resetStats = true;
		}
		unset($this->_saveFields['resetstats']);


		if(!empty($this->playerId)) {
			$queryStr = "UPDATE `".DB_PREFIX."_Players` SET";

			foreach($this->_saveFields as $k=>$v) {
				$queryStr .= " `".$k."` = '".$this->_DB->real_escape_string($v)."',";
			}
			$queryStr = trim($queryStr,",");

			$queryStr .= " WHERE `playerId` = '".$this->_DB->real_escape_string($this->playerId)."'";

			$run = $this->_DB->query($queryStr);
			if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
			if($run !== false) {
				$ret = true;
			}
		}

		return $ret;
	}

	/**
	 * load the player data from db
	 *
	 * @return void
	 */
	private function _load() {
		if(!empty($this->playerId)) {
			$query = $this->_DB->query("SELECT
					p.lastName AS name,
					p.clan,
					p.fullName,
					p.email,
					p.homepage,
					p.icq,
					p.myspace,
					p.facebook,
					p.skype,
					p.jabber,
					p.steamprofile,
					p.game,
					p.skill,
					p.oldSkill,
					p.kills,
					p.deaths,
					p.hideranking,
					p.active,
					p.isBot,
					IFNULL(p.kills/p.deaths, 0) AS kpd,
					p.suicides,
					CONCAT(c.tag, ' ', c.name) AS clan_name
				FROM `".DB_PREFIX."_Players` AS p
				LEFT JOIN `".DB_PREFIX."_Clans` AS c
					ON c.clanId = p.clan
				WHERE p.playerId = '".$this->_DB->real_escape_string($this->playerId)."'");
			if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
			if($query->num_rows) {
				$result = $query->fetch_assoc();
				$this->_playerData = $result;
				if(empty($this->_game)) {
					$this->_game = $result['game'];
				}
			}
		}
	}

	/**
	 * get the playerId via the player uniqueid
	 * the game is also needed !
	 *
	 * @param string $id The player unique id string
	 *
	 * @return boolean true or false
	 */
	private function _lookupPlayerIdFromUniqeId($id) {
		$ret = false;

		$query = $this->_DB->query("SELECT playerId FROM `".DB_PREFIX."_PlayerUniqueIds`
					WHERE uniqueId = '".$this->_DB->real_escape_string($id)."'
						AND game = '".$this->_DB->real_escape_string($this->_game)."'");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			$result = $query->fetch_assoc();
			$this->playerId = $result['playerId'];

			$ret = true;
		}

		return $ret;
	}

	/**
	 * get the player uniqueids if any
	 *
	 * @return void
	 */
	private function _getUniqueIds() {
		$this->_playerData['uniqueIds'] = '-';
		$query = $this->_DB->query("SELECT uniqueId
						FROM `".DB_PREFIX."_PlayerUniqueIds`
						WHERE playerId = '".$this->_DB->real_escape_string($this->playerId)."'");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			$ret = '';
			while ($result = $query->fetch_assoc()) {

				if(strstr($result['uniqueId'],'STEAM_')) {
					$ret = getSteamProfileUrl($result['uniqueId']);
					$this->_getSteamStats($result['uniqueId']);
					break;
				}
				$ret .= $result['uniqueId'].",<br />";
			}
			$this->_playerData['uniqueIds'] = $ret;
			$query->free();
		}
	}

	/**
	 * get the last connect from connect table
	 *
	 * @return void
	 */
	private function _getLastConnect() {
		$this->_playerData['lastConnect'] = l('No info');
		$query = $this->_DB->query("SELECT country, countryCode, eventTime
					FROM `".DB_PREFIX."_Events_Connects`
					WHERE playerId = '".$this->_DB->real_escape_string($this->playerId)."'
						AND eventTime = (
							SELECT MAX(eventTime) FROM `".DB_PREFIX."_Events_Connects`
							WHERE playerId = '".$this->_DB->real_escape_string($this->playerId)."'
						)
					");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			$result = $query->fetch_assoc();
			if(empty($result['eventTime'])) {
				// no connect recorded ?
				$this->_playerData['lastConnect'] = $this->getEventHistory('lastEvent');
				$this->_playerData['country'] = false;
				$this->_playerData['countryCode'] = false;
			}
			else {
				$this->_playerData['lastConnect'] = $result['eventTime'];
				$this->_playerData['country'] = $result['country'];
				$this->_playerData['countryCode'] = strtolower($result['countryCode']);
			}
			$query->free();
		}
	}

	/**
	 * get the max connection time
	 * if we have the information
	 *
	 * @return void
	 */
	private function _getMaxConnectTime() {
		$this->_playerData['maxTime'] = l('No info');
		$query = $this->_DB->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(time))) AS tTime
					FROM `".DB_PREFIX."_Events_StatsmeTime`
					WHERE playerId = '".$this->_DB->real_escape_string($this->playerId)."'");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			$result = $query->fetch_assoc();
			$this->_playerData['maxTime'] = $result['tTime'];
			$query->free();
		}
	}

	/**
	 * get the average ping
	 * if we have the information
	 *
	 * @return void
	 */
	private function _getAvgPing() {
		$this->_playerData['avgPing'] = l('No info');
		$query = $this->_DB->query("SELECT ROUND(SUM(ping) / COUNT(ping), 1) AS av_ping
					FROM `".DB_PREFIX."_Events_StatsmeLatency`
					WHERE playerId = '".$this->_DB->real_escape_string($this->playerId)."'");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			$result = $query->fetch_assoc();
			$this->_playerData['avgPing'] = $result['av_ping'];
			$query->free();
		}
	}

	/**
	 * get the rank by given ORDER
	 *
	 * @param $mode string The mode on which order the rank is based
	 *
	 * @return void
	 */
	private function _getRank($mode) {
		switch($mode) {
			case 'allPlayers':
				$queryStr = "SELECT count(*) AS rank
							FROM `".DB_PREFIX."_Players` AS t1
							LEFT JOIN `".DB_PREFIX."_PlayerUniqueIds` AS t2
								ON t2.playerId = t1.playerId
							WHERE t1.hideranking = '0'
								AND t1.kills >= '1'
								AND t1.game = '".$this->_DB->real_escape_string($this->_game)."'";
				$queryStr .= " AND t1.skill >
									(SELECT skill FROM ".DB_PREFIX."_Players
										WHERE playerId = '".$this->_DB->real_escape_string($this->playerId)."')";
				$query = $this->_DB->query($queryStr);
			break;

			case 'allwithoutBot':
				$queryStr = "SELECT count(*) AS rank
							FROM `".DB_PREFIX."_Players` AS t1
							LEFT JOIN `".DB_PREFIX."_PlayerUniqueIds` AS t2
								ON t2.playerId = t1.playerId
							WHERE t1.hideranking = '0'
								AND t1.kills >= '1'
								AND t1.game = '".$this->_DB->real_escape_string($this->_game)."'";
					$queryStr .= " AND t2.uniqueId NOT LIKE 'BOT:%'";

				$queryStr .= " AND t1.skill >
									(SELECT skill FROM ".DB_PREFIX."_Players
										WHERE playerId = '".$this->_DB->real_escape_string($this->playerId)."')";
				$query = $this->_DB->query($queryStr);
			break;

			case 'rankPoints':
			default:
				$queryStr = "SELECT count(*) AS rank
							FROM `".DB_PREFIX."_Players` AS t1
							LEFT JOIN `".DB_PREFIX."_PlayerUniqueIds` AS t2
								ON t2.playerId = t1.playerId
							WHERE t1.active = '1'
								AND t1.hideranking = '0'
								AND t1.kills >= '1'
								AND t1.game = '".$this->_DB->real_escape_string($this->_game)."'";
					$queryStr .= " AND t2.uniqueId NOT LIKE 'BOT:%'";

				$queryStr .= " AND t1.skill >
									(SELECT skill FROM ".DB_PREFIX."_Players
										WHERE playerId = '".$this->_DB->real_escape_string($this->playerId)."')";
				$query = $this->_DB->query($queryStr);
		}
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			$result = $query->fetch_assoc();
			// the result gives the rows which are "above" the searched row
			// we have to add 1 to get the position
			$this->_playerData[$mode] = $result['rank']+1;
			$query->free();
		}
	}

	/**
	 * get the team-kills for this player and game
	 *
	 * @return void
	 */
	private function _getTeamkills() {
		$this->_playerData['teamkills'] = l('No info');
		$query = $this->_DB->query("SELECT COUNT(*) tk
				FROM `".DB_PREFIX."_Events_Teamkills` AS et
				LEFT JOIN `".DB_PREFIX."_Servers` AS s
					ON s.serverId = et.serverId
				WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
					AND killerId = '".$this->_DB->real_escape_string($this->playerId)."'");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			$result = $query->fetch_assoc();
			$this->_playerData['teamkills'] = $result['tk'];
		}
	}

	/**
	 * get the weapon accuracy
	 * if we have the info
	 *
	 * @return void
	 */
	private function _getWeaponaccuracy() {
		$this->_playerData['accuracy'] = l('No info');
		$query = $this->_DB->query("SELECT
					IFNULL(ROUND((SUM(es.hits)
						/ SUM(es.shots) * 100), 1), 0.0) AS accuracy
					FROM `".DB_PREFIX."_Events_Statsme` AS es
				LEFT JOIN `".DB_PREFIX."_Servers` AS s
					ON s.serverId = es.serverId
				WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
					AND playerId='".$this->_DB->real_escape_string($this->playerId)."'");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			$result = $query->fetch_assoc();
			$this->_playerData['accuracy'] = $result['accuracy'];
			$query->free();
		}
	}

	/**
	 * get the last 10 aliases
	 *
	 * @return void
	 */
	private function _getAliasTable() {
		$this->_playerData['aliases'] = array();
		$query = $this->_DB->query("SELECT name, lastuse, numuses, kills,
								  deaths, IFNULL(kills / deaths,0) AS kpd,suicides
							  FROM `".DB_PREFIX."_PlayerNames`
							  WHERE playerId='".$this->_DB->real_escape_string($this->playerId)."'
							  ORDER BY lastuse DESC
							  LIMIT 10");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$this->_playerData['aliases'][] = $result;
			}
			$query->free();
		}
	}

	/**
	 * get the player action table
	 *
	 * @return void
	 */
	private function _getActions() {
		$this->_playerData['actions'] = array();
		$query = $this->_DB->query("SELECT a.description,
						COUNT(epa.id) AS obj_count,
						COUNT(epa.id) * a.reward_player AS obj_bonus
					FROM `".DB_PREFIX."_Actions` AS a
					LEFT JOIN `".DB_PREFIX."_Events_PlayerActions` AS epa
						ON epa.actionId = a.id
					LEFT JOIN `".DB_PREFIX."_Servers` AS s
						ON s.serverId = epa.serverId
					WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
						AND epa.playerId = ".$this->_DB->real_escape_string($this->playerId)."
					GROUP BY a.id
					ORDER BY obj_count DESC");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$this->_playerData['actions'][] = $result;
			}
			$query->free();
		}
	}

	/**
	 * get the player player actions
	 *
	 * @return void
	 */
	private function _getPlayerPlayerActions() {
		$this->_playerData['playerPlayerActions'] = array();
		$query = $this->_DB->query("SELECT a.description,
						COUNT(eppa.id) AS obj_count,
						COUNT(eppa.id) * a.reward_player AS obj_bonus
					FROM `".DB_PREFIX."_Actions` AS a
					LEFT JOIN `".DB_PREFIX."_Events_PlayerPlayerActions` AS eppa
						ON eppa.actionId = a.id
					LEFT JOIN `".DB_PREFIX."_Servers` AS s
						ON s.serverId = eppa.serverId
					WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
						AND eppa.playerId = ".$this->_DB->real_escape_string($this->playerId)."
					GROUP BY a.id
					ORDER BY obj_count DESC");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$this->_playerData['playerPlayerActions'][] = $result;
			}
			$query->free();
		}
	}

	/**
	 * get how much and which team the player was in
	 *
	 * @return void
	 */
	private function _getTeamSelection() {
		$this->_playerData['teamSelection'] = array();

		$queryTjoins = $this->_DB->query("SELECT COUNT(*) AS tj
							FROM `".DB_PREFIX."_Events_ChangeTeam`
							WHERE playerId = ".$this->_DB->real_escape_string($this->playerId)."");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		$result = $queryTjoins->fetch_assoc();
		$numteamjoins = $result['tj'];

		$query = $this->_DB->query("SELECT IFNULL(t.name, ect.team) AS name,
					COUNT(ect.id) AS teamcount,
					COUNT(ect.id) / $numteamjoins * 100 AS percent
				FROM `".DB_PREFIX."_Events_ChangeTeam` AS ect
				LEFT JOIN `".DB_PREFIX."_Teams`AS t
					ON ect.team = t.code
				LEFT JOIN `".DB_PREFIX."_Servers` AS s
					ON s.serverId = ect.serverId
				WHERE t.game = '".$this->_DB->real_escape_string($this->_game)."'
					AND s.game = '".$this->_DB->real_escape_string($this->_game)."'
					AND ect.playerId = ".$this->_DB->real_escape_string($this->playerId)."
					AND (t.hidden <>'1' OR t.hidden IS NULL)
				GROUP BY ect.team
				ORDER BY teamcount DESC");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$this->_playerData['teamSelection'][] = $result;
			}
			$query->free();
		}
	}

	/**
	 * get the weapon usage for the current player
	 *
	 * @return void
	 */
	private function _getweaponUsage() {
		$this->_playerData['weaponUsage'] = array();
		$query = $this->_DB->query("SELECT ef.weapon,
						w.name,
						IFNULL(w.modifier, 1.00) AS modifier,
						COUNT(ef.weapon) AS kills,
						COUNT(ef.weapon) / ".$this->_playerData['kills']." * 100 AS percent
					FROM `".DB_PREFIX."_Events_Frags` AS ef
					LEFT JOIN `".DB_PREFIX."_Weapons` AS w
						ON w.code = ef.weapon
					LEFT JOIN `".DB_PREFIX."_Servers` AS s
						ON s.serverId = ef.serverId
					WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
						AND ef.killerId = '".$this->_DB->real_escape_string($this->playerId)."'
						AND (w.game = '".$this->_DB->real_escape_string($this->_game)."' OR w.weaponId IS NULL)
					GROUP BY ef.weapon
					ORDER BY kills DESC");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$this->_playerData['weaponUsage'][] = $result;
			}
			$query->free();
		}
	}

	/**
	 * get the weapon stats
	 * if we have the info in the db
	 *
	 * @return void
	 */
	private function _getWeaponStats() {
		$this->_playerData['weaponStats'] = array();
		$query = $this->_DB->query("SELECT es.weapon AS smweapon,
					w.name,
					SUM(es.kills) AS smkills,
					SUM(es.hits) AS smhits,
					SUM(es.shots) AS smshots,
					SUM(es.headshots) AS smheadshots,
					SUM(es.deaths) AS smdeaths,
					SUM(es.damage) AS smdamage,
					IFNULL(
						(
							(
								SUM(es.damage) / SUM(es.hits)
							)
						), 0
					) as smdhr,
					SUM(es.kills)
						/
						IF(
							(
							SUM(es.deaths)=0
							), 1,
							(SUM(es.deaths))
						) as smkdr,
					(SUM(es.hits) / SUM(es.shots) * 100) as smaccuracy,
					IFNULL(((SUM(es.shots) / SUM(es.kills))), 0) as smspk
				FROM `".DB_PREFIX."_Events_Statsme` AS es
					LEFT JOIN `".DB_PREFIX."_Servers` AS s
						ON s.serverId = es.serverId
					LEFT JOIN `".DB_PREFIX."_Weapons` AS w
					 	ON w.code = es.weapon
				WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
					AND es.PlayerId = ".$this->_DB->real_escape_string($this->playerId)."
				GROUP BY es.weapon
				ORDER BY smaccuracy DESC");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$this->_playerData['weaponStats'][] = $result;
			}
			$query->free();
		}
	}

	/**
	 * get the weapon target
	 * if we have the information in db
	 * uses Events_Statsme2 and Events_PlayerAttackedPlayer
	 * both tables are summed up
	 *
	 * @return void
	 */
	private function _getWeaponTarget() {
		$this->_playerData['weaponTarget'] = array();
		/*
		$query = $this->_DB->query("SELECT es2.weapon AS smweapon,
					w.name,
					SUM(es2.head) AS smhead,
					SUM(es2.chest) AS smchest,
					SUM(es2.stomach) AS smstomach,
					SUM(es2.leftarm) AS smleftarm,
					SUM(es2.rightarm) AS smrightarm,
					SUM(es2.leftleg) AS smleftleg,
					SUM(es2.rightleg) AS smrightleg
				FROM `".DB_PREFIX."_Events_Statsme2` AS es2
				LEFT JOIN `".DB_PREFIX."_Servers` AS s
					ON s.serverId = es2.serverId
				LEFT JOIN `".DB_PREFIX."_Weapons` AS w
					ON w.code = es2.weapon
				WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
					AND es2.PlayerId=".$this->_DB->real_escape_string($this->playerId)."
				GROUP BY es2.weapon
				ORDER BY smhead DESC, smweapon DESC");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$this->_playerData['weaponTarget'][] = $result;
			}
			$query->free();
		}

		/*
		SELECT COUNT(hitgroup) AS hits, SUM(damage) AS damage, epap.*
		FROM hlstats_Events_PlayerAttackedPlayer AS epap
		GROUP BY playerId,weapon,hitgroup
		*/
		/*
		# now get the data from the Events_PlayerAttacked_Player
		$query = $this->_DB->query("SELECT epap.*, w.name
				FROM `".DB_PREFIX."_Events_PlayerAttackedPlayer` AS epap
				LEFT JOIN `".DB_PREFIX."_Servers` AS s
					ON s.serverId = epap.serverId
				LEFT JOIN `".DB_PREFIX."_Weapons` AS w
					ON w.code = epap.weapon
				WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
					AND es2.PlayerId=".$this->_DB->real_escape_string($this->playerId)."");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$result['hitgroup'] = 'sm'.str_replace(' ','',$result['hitgroup']);
				$tmp[$result['weapon']][$result['hitgroup']] =


				#$this->_playerData['weaponTarget'][] = $result;
			}
			$query->free();
		}
		*/

	}

	/**
	 * get the map performance
	 *
	 * @return void
	 */
	private function _getMaps() {
		$this->_playerData['maps'] = array();

		$query = $this->_DB->query("SELECT IF( map = '', '(Unaccounted)', map) AS map,
			SUM(killerId = ".$this->_DB->real_escape_string($this->playerId).") AS kills,
			SUM(victimId = ".$this->_DB->real_escape_string($this->playerId).") AS deaths,
			IFNULL(SUM(killerId = ".$this->_DB->real_escape_string($this->playerId).") / SUM(victimId=".$this->_DB->real_escape_string($this->playerId)."), 0) AS kpd,
			CONCAT(SUM(killerId = ".$this->_DB->real_escape_string($this->playerId).")) / ".$this->_DB->real_escape_string($this->_playerData['kills'])." * 100 AS percentage
		FROM `".DB_PREFIX."_Events_Frags` AS ef
		LEFT JOIN `".DB_PREFIX."_Servers` AS s
			ON s.serverId = ef.serverId
		WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."' AND killerId='".$this->_DB->real_escape_string($this->playerId)."'
			OR victimId = '".$this->_DB->real_escape_string($this->playerId)."'
		GROUP BY map
		ORDER BY kills DESC, percentage DESC");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$this->_playerData['maps'][] = $result;
			}
			$query->free();
		}
	}

	/**
	 * get the kill stats table
	 *
	 * @return void
	 */
	private function _getPlayerKillStats() {
		$this->_playerData['killstats'] = array();

		//there might be a better way to do this, but I could not figure one out.
		$this->_DB->query("DROP TABLE IF EXISTS `".DB_PREFIX."_".$this->playerId."_Frags_Kills`");
		$this->_DB->query("CREATE TEMPORARY TABLE `".DB_PREFIX."_".$this->playerId."_Frags_Kills`
						(playerId INT(10),kills INT(10),deaths INT(10)) DEFAULT CHARSET=utf8");
		$this->_DB->query("INSERT INTO `".DB_PREFIX."_".$this->playerId."_Frags_Kills`
						(playerId,kills)
					   	SELECT victimId, killerId
					   	FROM `".DB_PREFIX."_Events_Frags` AS ef
					   	LEFT JOIN `".DB_PREFIX."_Servers` AS s
							ON s.serverId = ef.serverId
					   WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
						   AND killerId = ".$this->_DB->real_escape_string($this->playerId)."");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);

		$this->_DB->query("INSERT INTO `".DB_PREFIX."_".$this->playerId."_Frags_Kills` (playerId,deaths)
						SELECT killerId,victimId
						FROM `".DB_PREFIX."_Events_Frags` AS ef
						LEFT JOIN `".DB_PREFIX."_Servers` AS s
							ON s.serverId = ef.serverId
					WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
						AND victimId = ".$this->_DB->real_escape_string($this->playerId)."");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);

		$query = $this->_DB->query("SELECT p.lastName AS name,
					p.active,
					p.playerId,
					p.isBot,
					Count(fk.kills) AS kills,
					Count(fk.deaths) AS deaths,
					fk.playerId as victimId,
					IFNULL(Count(fk.kills)/Count(fk.deaths),
					IFNULL(FORMAT(Count(fk.kills), 2), 0)) AS kpd
				FROM `".DB_PREFIX."_".$this->playerId."_Frags_Kills` AS fk
				INNER JOIN `".DB_PREFIX."_Players` AS p
					ON fk.playerId = p.playerId
				WHERE p.hideranking = 0
				GROUP BY fk.playerId
				HAVING Count(fk.kills) >= ".$this->_DB->real_escape_string($this->_option['killLimit'])."
				ORDER BY kills DESC, deaths DESC
				LIMIT 10");
		if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
		if($query->num_rows > 0) {
			while($result = $query->fetch_assoc()) {
				$this->_playerData['killstats'][] = $result;
			}
		}
		$query->free();
	}

	/**
	 * get the role selection data
	 *
	 * @return void
	 */
	private function _getRoleSelection() {
		$this->_playerData['roleSelection'] = array();

		$queryRoles = $this->_DB->query("SELECT COUNT(*) AS rj FROM
									`".DB_PREFIX."_Events_ChangeRole`
									WHERE playerId = ".$this->_DB->real_escape_string($this->playerId)."");
		$result = $queryRoles->fetch_assoc();
		$numrolejoins = $result['rj'];
		$queryRoles->free();
		if(!empty($numrolejoins)) {
			$query = $this->_DB->query(" SELECT
						IFNULL(r.name, ecr.role) AS name,
						COUNT(ecr.id) AS rolecount,
						COUNT(ecr.id) / $numrolejoins * 100 AS percent,
						r.code AS rolecode
					FROM `".DB_PREFIX."_Events_ChangeRole` AS ecr
					LEFT JOIN `".DB_PREFIX."_Roles` AS r
						ON ecr.role = r.code
					LEFT JOIN `".DB_PREFIX."_Servers` AS s
						ON s.serverId = ecr.serverId
					WHERE s.game = '".$this->_DB->real_escape_string($this->_game)."'
						AND ecr.playerId = ".$this->_DB->real_escape_string($this->playerId)."
						AND (r.hidden <>'1' OR r.hidden IS NULL)
					GROUP BY ecr.role
					ORDER BY `name`
					LIMIT 10");
			if(SHOW_DEBUG && $this->_DB->error != '') var_dump($this->_DB->error);
			if($query->num_rows > 0) {
				while($result = $query->fetch_assoc()) {
					$this->_playerData['roleSelection'][] = $result;
				}
			}
			$query->free();
		}
	}

	/**
	 * get the public steam stats if available for this player/game
	 * https://partner.steamgames.com/documentation/community_data
	 * http://steamcommunity.com/profiles/76561197968575517/stats/L4D/?xml=1
	 */
	private function _getSteamStats($steamID) {
		$sPID = calculateSteamProfileID($steamID);
		if(!empty($sPID) && isset($this->_statsGames[$this->_game])) {
			$url = "http://steamcommunity.com/profiles/".$sPID."/stats/".$this->_statsGames[$this->_game]."/?xml=1";
			$data = getDataFromURL($url);
			if(!empty($data)) {
				$xml = @simplexml_load_string($data);
				if($xml !== false) {
					foreach($xml->achievements->achievement as $achievement) {
						$att = $achievement->attributes();
						$closed = (string)$att['closed'];
						if($closed === "1") { # closed is achieved
							$this->_playerData['steamAchievements'][] = array(
								'name' => (string)$achievement->name,
								'desc' => (string)$achievement->description,
								'picture' => (string)$achievement->iconClosed
							);
						}
					}
				}
			}
		}
	}
}

?>
