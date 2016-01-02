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

use DBI;
use File::Basename;
use Config::Tiny;
use Getopt::Long;
use Time::Local;

my $opt_libdir = dirname(__FILE__);
my $opt_configfile = "$opt_libdir/$opt_configfile_name";

require "$opt_libdir/HLstats.plib";

$|=1;
Getopt::Long::Configure ("bundling");

## load config with config-tiny module
my $Config = Config::Tiny->read($opt_configfile);
if($Config::Tiny::errstr ne '') {
	print "Config file not found !\n";
	print $Config::Tiny::errstr;
	print "\n";
	exit(0)
}

my $opt_help = 0;
my $opt_version = 0;
my $opt_numdays = 1;
my $opt_quiet = 0;

my $db_name = $Config->{Database}->{DBName};
my $db_host = $Config->{Database}->{DBHost};
my $db_user = $Config->{Database}->{DBUsername};
my $db_pass = $Config->{Database}->{DBPassword};
my $db_prefix = $Config->{Database}->{DBPrefix};

# Usage message
my $usage = <<EOT
Usage: player-activity.pl [OPTION]...
Update player activity from Half-Life server statistics.

  -h, --help                      display this help and exit
  -v, --version                   output version information and exit
      --db-host=HOST              database ip:port
      --db-name=DATABASE          database name
      --db-password=PASSWORD      database password (WARNING: specifying the
                                    password on the command line is insecure.
                                    Use the configuration file instead.)
      --db-username=USERNAME      database username
  -q, --quiet                     disables all output. usefull while run with cron

Long options can be abbreviated, where such abbreviation is not ambiguous.

Options are located in the configuration file:
  $opt_configfile
AND in the _Options table.

HLStats: http://www.hlstats-community.org
EOT
;

GetOptions(
	"help|h"			=> \$opt_help,
	"version|v"			=> \$opt_version,
	"db-host=s"			=> \$db_host,
	"db-name=s"			=> \$db_name,
	"db-password=s"		=> \$db_pass,
	"db-username=s"		=> \$db_user
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
	print "\nplayer-activity.pl (HLStats): $main::g_version\n"
		. "Real-time player and clan rankings and statistics for Half-Life\n\n"
		. "http://www.hlstats-community.org\n"
		. "This is free software; see the source for copying conditions.  There is NO\n"
		. "warranty; not even for MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.\n\n";
	exit(0);
}

# startup
print "++ HLStats $main::g_version starting...\n\n";

# Connect to the database
print "-- Connecting to MySQL database '$db_name' on '$db_host' as user '$db_user' ... ";

$main::db_conn = DBI->connect(
	"DBI:mysql:$db_name:$db_host",
	$db_user, $db_pass, { 'RaiseError' => 1, "mysql_enable_utf8" => 1,
		'mysql_auto_reconnect' => 1, 'ShowErrorStatement' => 1 }
) or die ("\nCan't connect to MySQL database '$db_name' on '$db_host'\n" .
	"Server error: $DBI::errstr\n");

&doQuery("SET character set utf8");
&doQuery("SET NAMES utf8");

print " OK\n";

print "-- Loading options... ";

# load the options from DB
my $result = &doQuery("SELECT `keyname`,`value` FROM `${db_prefix}_Options`");
my ($keyname, $value, %oHash);
while( ($keyname, $value) = $result->fetchrow_array ) {
	$oHash{$keyname} = $value;
}
$result->finish();

# we need only this one
my $conf_timeFrame = $oHash{TIMEFRAME};

GetOptions(
	"period=i"	=> \$conf_timeFrame,
) or die($usage);

print "OK\n";

## main process
my $frame = time() - ($conf_timeFrame*86400); # time in seconds

&doQuery("UPDATE `${db_prefix}_Players`
			SET `active` = '0'
			WHERE (`skillchangeDate` < '".$frame."'
				OR `skillchangeDate` IS NULL)
			AND `active` = '1'");

print "\n++ Player activity updated successfully.\n";
exit(0);
