#!/usr/bin/perl
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
#
# This program is free software is licensed under the
# COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
#
# You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
# along with this program; if not, visit http://hlstats-community.org/License.html
#

my $DEBUG = 1;
my $dry_run = 0;

use strict;
use Encode;
use open qw( :std :encoding(UTF-8) );
use HTTP::Request;
use LWP::UserAgent;
use DBI;
use Config::Tiny;
use File::Basename;
use File::Path qw(remove_tree);

use Data::Dumper; #debug

# the server has those modules not installed
# extending the inlcude path with the lib dir is the new way to inlcude
# missing modules !
use lib "./lib";
use XML::LibXML;

## load config with config-tiny module
my $Config = Config::Tiny->read("./worldstats.conf.ini");
if($Config::Tiny::errstr ne '') {
	print "Error in config file. Please compare with example file !\n";
	print $Config::Tiny::errstr;
	print "\n";
	exit(1);
}
my $db_name = $Config->{Database}->{DBName};
my $db_host = $Config->{Database}->{DBHost};
my $db_user = $Config->{Database}->{DBUsername};
my $db_pass = $Config->{Database}->{DBPassword};
my $db_prefix = $Config->{Database}->{DBPrefix};

# db connection
my $db = DBI->connect(
	"DBI:mysql:$db_name:$db_host",
	$db_user, $db_pass, { 'RaiseError' => 1, 'mysql_enable_utf8' => 1, 'mysql_auto_reconnect' => 1,
			'ShowErrorStatement' => 1 }
) or die ("\nCan't connect to MySQL database");
doQuery("SET character set utf8");
doQuery("SET NAMES utf8");



# read the xml data and write it into the db
print "Parsing xml files...\n" if $DEBUG;
my @xmlFiles = <./xmlData/*>;
foreach (@xmlFiles) {
	print "file: $_\n" if $DEBUG;

	my $parser = XML::LibXML->new();
	my $doc = $parser->parse_file($_);

	my $siteName = basename($_,".xml");

	foreach my $player ($doc->findnodes('/root/players/player')) {
		my $pName = $player->findnodes('./name');
		my $pCountry = $player->findnodes('./country');
		my $pProfile = $player->findnodes('./profile');
		my $pDeaths = $player->findnodes('./deaths');
		my $pCountryCode = $player->findnodes('./countryCode');
		my $pSkill = $player->findnodes('./skill');
		my $pOldSkill = $player->findnodes('./oldSkill');
		my $pKills = $player->findnodes('./kills');
		my $pLastConnect = $player->findnodes('./lastConnect');
		my $pUniqueId = $player->findnodes('./uniqueId');
		my $pgame = $player->findnodes('./game');

		if($dry_run ne 1) {
			# build the query string
			my $queryStr = "INSERT INTO `".$db_prefix."_playerDataTable`
				(uniqueID, name, profile, country, countryCode, skill, oldSkill, kills, deaths, lastConnect,
					game,day,sitename)
				VALUES (
					".$db->quote($pUniqueId).", ".$db->quote($pName).", ".$db->quote($pProfile).",
					".$db->quote($pCountry).", ".$db->quote($pCountryCode).",
					".$db->quote($pSkill).", ".$db->quote($pOldSkill).", ".$db->quote($pKills).",
					".$db->quote($pDeaths).", ".$db->quote($pLastConnect).",
					".$db->quote($pgame).", CURDATE(), ".$db->quote($siteName)."
				)
				ON DUPLICATE KEY UPDATE
					name = VALUES(name),
					profile = VALUES(profile),
					country = VALUES(country),
					countryCode = VALUES(countryCode),
					skill = VALUES(skill),
					oldSkill = VALUES(oldSkill),
					kills = VALUES(kills),
					deaths = VALUES(deaths),
					lastConnect = VALUES(lastConnect),
					lastUpdate = CURRENT_TIMESTAMP()
			";

			#print $queryStr."\n" if $DEBUG;

			# do the query
			my $result = doQuery($queryStr);
		}
	}
}

## sub

# run sql queries
sub doQuery {

	my ($query, $callref) = @_;

	if(!$db)  {
		$db ||= DBI->connect("DBI:mysql:$db_name:$db_host",$db_user, $db_pass,
				{ 'RaiseError' => 1, 'mysql_enable_utf8' => 1, 'mysql_auto_reconnect' => 1,
					'ShowErrorStatement' => 1})
			or die("Unable to connect to MySQL server");

		my $rs = $db->prepare("SET character set utf8");
		$rs->execute;

		$rs = $db->prepare("SET NAMES utf8");
		$rs->execute;
	}


	my $result = $db->prepare($query)
		or die("Unable to prepare query:\n$query\n$DBI::errstr\n$callref");
	$result->execute
		or die("Unable to execute query:\n$query\n$DBI::errstr\n$callref");

	return $result;
}

# remove whitespace
sub trim() {
	my $string = shift;
	$string =~ s/^\s+//;
	$string =~ s/\s+$//;
	return $string;
}

# end of file
