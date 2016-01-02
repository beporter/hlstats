#!/usr/bin/perl -w

#
# Original development:
# +
# + HLStats - Real-time player and clan rankings and statistics for Half-Life
# + http://sourceforge.net/projects/hlstats/
# +
# + Copyright (C) 2001  Simon Garner
# +
#
# Additional development:
# +
# + UA HLStats Team
# + http://www.unitedadmins.com
# + 2004 - 2007
# +
#
#
# Current development:
# +
# + Johannes 'Banana' Keßler
# + http://hlstats.sourceforge.net
# + 2007 - 2012
# +
#
# This program is free software is licensed under the
# COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
#
# You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
# along with this program; if not, visit http://hlstats-community.org/License.html
#

##
## Settings
##

# $opt_configfile_name - Filename of configuration file.
my $opt_configfile_name = "hlstats.conf.ini";

##
##
################################################################################
## No need to edit below this line
##

use strict;
no strict 'vars';

BEGIN { 
    binmode STDOUT, ':encoding(UTF-8)';
    binmode STDERR, ':encoding(UTF-8)';
}

use Getopt::Long;
use DBI;
use Config::Tiny;
use File::Basename;

my $opt_libdir = dirname(__FILE__);
my $opt_configfile = "$opt_libdir/$opt_configfile_name";

require "$opt_libdir/HLstats.plib";

$|=1;
Getopt::Long::Configure ("bundling");

##
## MAIN
##

## load config with config-tiny module
$Config = Config::Tiny->read($opt_configfile);
if($Config::Tiny::errstr ne '') {
	print "Config file not found !\n";
	print $Config::Tiny::errstr;
	print "\n";
	exit(0)
}

$opt_help = 0;
$opt_version = 0;
$opt_numdays = 1;
$opt_quiet = 0;

$db_name = $Config->{Database}->{DBName};
$db_host = $Config->{Database}->{DBHost};
$db_user = $Config->{Database}->{DBUsername};
$db_pass = $Config->{Database}->{DBPassword};
$db_prefix = $Config->{Database}->{DBPrefix};

# Usage message

$usage = <<EOT
Usage: hlstats-awards.pl [OPTION]...
Generate awards from Half-Life server statistics.

  -h, --help                      display this help and exit
  -v, --version                   output version information and exit
      --numdays                   number of days in period for awards
      --db-host=HOST              database ip:port
      --db-name=DATABASE          database name
      --db-password=PASSWORD      database password (WARNING: specifying the
                                    password on the command line is insecure.
                                    Use the configuration file instead.)
      --db-username=USERNAME      database username
   -q, --quiet                    disables all output. usefull while run with cron

Long options can be abbreviated, where such abbreviation is not ambiguous.

Most options can be specified in the configuration file:
  $opt_configfile
Note: Options set on the command line take precedence over options set in the
configuration file.

HLStats: http://www.hlstats-community.org
EOT
;

# Read Command Line Arguments

GetOptions(
	"help|h"			=> \$opt_help,
	"version|v"			=> \$opt_version,
	"numdays=i"			=> \$opt_numdays,
	"db-host=s"			=> \$db_host,
	"db-name=s"			=> \$db_name,
	"db-password=s"		=> \$db_pass,
	"db-username=s"		=> \$db_user,
	"quiet|q"           => \$opt_quiet
) or die($usage);

if ($opt_help) {
	print $usage;
	exit(0);
}

# can be used to supress all the output
# does not work with windows
if($opt_quiet) {
	open STDIN, '/dev/null'   or die  $!;
	open STDOUT, '>>/dev/null' or die $!;
	open STDERR, '>>/dev/null' or die $!;
}

if ($opt_version) {
	print "hlstats-awards.pl (HLStats) $g_version\n"
		. "Real-time player and clan rankings and statistics for Half-Life\n\n"
		. "http://www.hlstats-community.org\n"
		. "This is free software; see the source for copying conditions.  There is NO\n"
		. "warranty; not even for MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.\n";
	exit(0);
}


# Startup

print "++ HLStats Awards $g_version starting...\n\n";

# Connect to the database

print "-- Connecting to MySQL database '$db_name' on '$db_host' as user '$db_user' ... ";

$db_conn = DBI->connect(
	"DBI:mysql:$db_name:$db_host",
	$db_user, $db_pass, {RaiseError => 1,'mysql_enable_utf8' => 1, 'mysql_auto_reconnect' => 1,
				'ShowErrorStatement' => 1 }
) or die ("Can't connect to MySQL database '$db_name' on '$db_host'\n" .
	"$DBI::errstr\n");

&doQuery("SET character set utf8");
&doQuery("SET NAMES utf8");


print "connected OK\n";


# Main data routine

# get the awards for each game
$resultAwards = &doQuery("
	SELECT
		a.awardId,
		a.game,
		a.awardType,
		a.code
	FROM
		`${db_prefix}_Awards` AS a
	LEFT JOIN ${db_prefix}_Games AS g 
		ON g.code = a.game
	WHERE g.hidden='0'
	ORDER BY a.game,
		a.awardType
");

$result = &doQuery("SELECT value, DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)
					FROM `${db_prefix}_Options`
					WHERE keyname='awards_d_date'");

if ($result->rows > 0) {
	($awards_d_date, $awards_d_date_new) = $result->fetchrow_array;

	&doQuery("UPDATE `${db_prefix}_Options`
				SET value='$awards_d_date_new'
				WHERE keyname='awards_d_date'");

	print "\n++ Generating awards for $awards_d_date_new (previous: $awards_d_date)...\n\n";
}
else {
	&doQuery("INSERT INTO `${db_prefix}_Options` (keyname,value)
			VALUES ('awards_d_date',DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY))");
}



while( ($awardId, $game, $awardType, $code) = $resultAwards->fetchrow_array ) {
	print "$game ($awardType) $code";

	if ($awardType eq "O") {
		$table = "`${db_prefix}_Events_PlayerActions`";
		$join  = "LEFT JOIN `${db_prefix}_Actions` ON `${db_prefix}_Actions`.`id` = $table.`actionId`";
		$matchfield = "`${db_prefix}_Actions`.`code`";
		$playerfield = "$table.`playerId`";
	}
	elsif ($awardType eq "W") {
		$table = "`${db_prefix}_Events_Frags`";
		$join  = "";
		$matchfield = "$table.`weapon`";
		$playerfield = "$table.`killerId`";
	}

	$result = &doQuery("
		SELECT
			$playerfield,
			COUNT($matchfield) AS awardcount
		FROM
			$table
		LEFT JOIN `${db_prefix}_Players` ON
			`${db_prefix}_Players`.`playerId` = $playerfield
		$join
		WHERE
			$table.`eventTime` < CURRENT_DATE()
			AND $table.`eventTime` > DATE_SUB(CURRENT_DATE(), INTERVAL $opt_numdays DAY)
			AND `${db_prefix}_Players`.`game` = '$game'
			AND `${db_prefix}_Players`.`hideranking` = '0'
			AND $matchfield = '$code'
		GROUP BY
			$playerfield
		ORDER BY
			awardcount DESC,
			`${db_prefix}_Players`.`skill` DESC
		LIMIT 1
	");

	($d_winner_id, $d_winner_count) = $result->fetchrow_array;

	if (!$d_winner_id || $d_winner_count < 1) {
		$d_winner_id = "NULL";
		$d_winner_count = "NULL";
	}

	print "  - $d_winner_id ($d_winner_count)\n";

	&doQuery("INSERT INTO `${db_prefix}_Awards_History`
				SET d_winner_id = $d_winner_id,
					d_winner_count = $d_winner_count,
					`date` = DATE_SUB(CURRENT_DATE(), INTERVAL $opt_numdays DAY),
					fk_award_id = $awardId,
					game= '$game'
				ON DUPLICATE KEY UPDATE
					d_winner_id = $d_winner_id,
					d_winner_count = $d_winner_count");
}

print "\n++ Awards generated successfully.\n";
exit(0);
