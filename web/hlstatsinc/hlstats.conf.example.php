<?php
/**
 * example configuration file for web-interface
 * copy this file to hlstats.conf.php and set the options
 * @package HLStats
 * @author Johannes 'Banana' Keßler
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

///
/// Database Settings
///

/**
 * DB_NAME - The name of the database
 * @global string DB_NAME
 * @name DB_NAME
 */
define("DB_NAME", "hlstats");

/**
 * DB_USER - The username to connect to the database as
 * @global string DB_USER
 * @name DB_USER
 */
define("DB_USER", "user");

/**
 * DB_PASS - The password for DB_USER
 * @global string DB_PASS
 * @name DB_PASS
 */
define("DB_PASS", "test");

/**
 * DB_ADDR - The address of the database server, in host:port format.
 * 			(You might also try setting this to e.g. ":/tmp/mysql.sock" to
 * 			use a Unix domain socket, if your mysqld is on the same box as
 * 			your web server.)
 * @global string DB_ADDR
 * @name DB_ADDR
 */
define("DB_ADDR", "localhost");

/**
 * DB_PREFIX - The table prefix. Default is hlstats (the leading _ comes from the sql file)
 * @global string DB_PREFIX
 * @name DB_PREFIX
 */
define("DB_PREFIX", "hlstats");

/**
 * DB_PCONNECT - Set to 1 to use persistent database connections. Persistent
 * 			connections can give better performance, but may overload
 * 			the database server. Set to 0 to use non-persistent
 * 			connections.
 * @global string DB_PCONNECT
 * @name DB_PCONNECT
 */
define("DB_PCONNECT", 0);

?>
