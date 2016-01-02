package HLstats_Player;
#
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
# + 2007 - 2011
# +
#
# This program is free software is licensed under the
# COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL) Version 1.0
#
# You should have received a copy of the COMMON DEVELOPMENT AND DISTRIBUTION LICENSE
# along with this program; if not, visit http://hlstats-community.org/License.html
#

#
# Constructor
#

sub new {
	my $class_name = shift;
	my %params = @_;

	my $self = {};
	bless($self, $class_name);

	# Initialise Properties
	$self->{userid} = 0;
	$self->{server} = "";
	$self->{name} = "";
	$self->{uniqueid} = "";

	$self->{playerid} = 0;
	$self->{clan} = 0;
	$self->{kills}  = 0;
	$self->{deaths} = 0;
	$self->{suicides} = 0;
	$self->{skill}  = 1000;
	$self->{game}   = 0;
	$self->{team}   = "";
	$self->{role}   = "";
	$self->{timestamp} = 0;
	$self->{isBot} = 0;

	# Set Property Values

	# tag remove
	if($::g_option_strip_tags) {
		$params{name} =~ s/\[No.C-D\]//g;	# remove [No C-D] tag
		$params{name} =~ s/\[OLD.C-D\]//g;	# remove [OLD C-D] tag
		$params{name} =~ s/\[NOCL\]//g;		# remove [NOCL] tag
		$params{name} =~ s/\([0-9]\)//g;	# strip (0-9) from player names
	}

	die("HLstats_Player->new(): must specify player's uniqueid\n")
		unless (defined($params{uniqueid}));

	die("HLstats_Player->new(): must specify player's name\n")
		unless ($params{name} ne "");

	$self->setUniqueId($params{uniqueid});
	$self->setName($params{name});

	while (my($key, $value) = each(%params)) {
		if ($key ne "name" && $key ne "uniqueid") {
			$self->set($key, $value);
		}
	}

	&::printNotice("Created new player object " . $self->getInfoString());

	$self->updateDB();

	return $self;
}


#
# Set property 'key' to 'value'
#

sub set {
	my ($self, $key, $value, $no_updatetime) = @_;

	if (defined($self->{$key})) {
		unless ($no_updatetime) {
			$self->{timestamp} = $::ev_unixtime;
		}

		if ($self->get($key) eq $value) {
			if ($::g_debug > 2) {
				&printNotice("Hlstats_Player->set ignored: Value of \"$key\" is already \"$value\"");
			}
			return 0;
		}

		if ($key eq "uniqueid") {
			return $self->setUniqueId($value);
		}
		elsif ($key eq "name") {
			return $self->setName($value);
		}
		else {
			$self->{$key} = $value;
			return 1;
		}
	}
	else {
		warn("HLstats_Player->set: \"$key\" is not a valid property name\n");
		return 0;
	}
}


#
# Increment (or decrement) the value of 'key' by 'amount' (or 1 by default)
#
sub increment {
	my ($self, $key, $amount, $no_updatetime) = @_;

	
	if(defined($amount)) {
		$amount = int($amount);
		$amount = 1 if $amount == 0;
	}
	else {
		$amount = 1;
	}

	my $value = $self->get($key);
	$self->set($key, $value + $amount, $no_updatetime);
}


#
# Get value of property 'key'
#
sub get {
	my ($self, $key) = @_;

	if (defined($self->{$key})) {
		return $self->{$key};
	}
	else {
		warn("HLstats_Player->get: \"$key\" is not a valid property name\n");
	}
}


#
# Set player's uniqueid
#
sub setUniqueId {
	my ($self, $uniqueid) = @_;

	my $playerid = &::getPlayerId($uniqueid);

	if (!$playerid) {
		# This is a new player. Create a new record for them in the Players
		# table.

		my $query = "
			INSERT INTO
				`".$::db_prefix."_Players`
				(
					lastName,
					clan,
					game
				)
			VALUES
			(
				'" . &::quoteSQL($self->get("name")) . "',
				'" . $self->get("clan") . "',
				'" . $::g_servers{$::s_addr}->{game} . "'
			)
		";
		my $result = &::doQuery($query);
		$result->finish;

		$result = &::doQuery("SELECT LAST_INSERT_ID()");
		($playerid) = $result->fetchrow_array;
		$result->finish;

		if ($playerid)
		{
			$query = "
				INSERT INTO
					`".$::db_prefix."_PlayerUniqueIds`
					(
						playerId,
						uniqueId,
						game
					)
				VALUES
				(
					'" . $playerid . "',
					'" . &::quoteSQL($uniqueid) . "',
					'" . $::g_servers{$::s_addr}->{game} . "'
				)
			";
			$result = &::doQuery($query);
			$result->finish;
		}
		else
		{
			&::printNotice("Unable to create player:\n$query");
		}
	}

	$self->{uniqueid} = $uniqueid;
	$self->{playerid} = $playerid;

	return 1;
}



#
# Set player's name
#
sub setName {
	my ($self, $name) = @_;

	my $oldname = $self->get("name");

	if ($oldname eq $name) {
		return 2;
	}

	if ($oldname) {
		$self->updateDB();
	}

	$self->{name} = $name;
	$self->{clan} = &::getClanId($name);

	my $playerid = $self->get("playerid");

	if ($playerid) {
		my $query = "
			SELECT
				playerId
			FROM
				`".$::db_prefix."_PlayerNames`
			WHERE
				playerId='" . $playerid . "'
				AND name='" . &::quoteSQL($self->get("name")) . "'
		";
		my $result = &::doQuery($query);

		if ($result->rows < 1) {
			$query = "
				INSERT INTO
					`".$::db_prefix."_PlayerNames`
					(
						playerId,
						name,
						lastuse,
						numuses
					)
				VALUES
				(
					'" . $playerid . "',
					'" . &::quoteSQL($self->get("name")) . "',
					" . $::ev_datetime . ",
					1
				)
			";
			&::doQuery($query);
		}
		else {
			$query = "
				UPDATE
					`".$::db_prefix."_PlayerNames`
				SET
					lastuse=" . $::ev_datetime . ",
					numuses=numuses+1
				WHERE
					playerId='" . $playerid . "'
					AND name='" . &::quoteSQL($self->get("name")) . "'
			";
			&::doQuery($query);
		}

		$result->finish;
	}
	else {
		&::printNotice("HLstats_Player->setName(): No playerid");
	}
}



#
# Update player information in database
#
sub updateDB {
	my ($self, $leaveLastUse) = @_;

	my $playerid = $self->get("playerid");
	my $name = $self->get("name");
	my $clan = $self->get("clan");
	my $kills  = $self->get("kills");
	my $deaths = $self->get("deaths");
	my $suicides = $self->get("suicides");
	my $skill  = $self->get("skill");
	my $isBot = $self->get("isBot");

	unless ($playerid) {
		warn ("Player->Update() with no playerid set!\n");
		return 0;
	}

	# Update player details
	my $query = "
		UPDATE
			`".$::db_prefix."_Players`
		SET
			lastName = '" . &::quoteSQL($name) . "',
			clan = '$clan',
			kills = kills + $kills,
			deaths = deaths + $deaths,
			suicides = suicides + $suicides,
			oldSkill = skill,
			skillchangeDate = '".$::ev_unixtime."',
			active = '1',
			skill = $skill,
			isBot = $isBot
		WHERE
			playerId = '$playerid'
	";
	my  ($c_package, $c_filename, $c_line) = caller;
	my $callref = $c_package." ".$c_filename." ".$c_line;
	&::doQuery($query, "Player->updateDB(): $callref");

	if ($name) {
		# Update alias details
		$query = "
			UPDATE
				`".$::db_prefix."_PlayerNames`
			SET
				kills = kills + $kills,
				deaths = deaths + $deaths,
				suicides = suicides + $suicides"
		;

		unless ($leaveLastUse) {
			# except on ChangeName we update the last use on a player's old name

			$query .= ",
				lastuse = " . $::ev_datetime . ""
			;
		}

		$query .= "
			WHERE
				playerId = '" . $playerid . "'
				AND name = '" . &::quoteSQL($self->get("name")) . "'
		";
		&::doQuery($query);
	}

	# reset player stat properties
	# we do not store the complete values here. Onl the differenc between the updates.
	$self->set("kills", 0);
	$self->set("deaths", 0);
	$self->set("suicides", 0);

	&::printNotice("Updated player object " . $self->getInfoString());

	return 1;
}


#
# Update player timestamp (time of last event for player - used to detect idle
# players)
#

sub updateTimestamp {
	my ($self, $timestamp) = @_;

	$timestamp = $::ev_unixtime
		unless ($timestamp);

	$self->{timestamp} = $timestamp;

	return $timestamp;
}


#
# Returns a string of information about the player.
#

sub getInfoString {
	my ($self) = @_;

	my $name = $self->get("name");
	my $playerid = $self->get("playerid");
	my $userid   = $self->get("userid");
	my $uniqueid = $self->get("uniqueid");
	my $team = $self->get("team");

	return "\"$name\" \<P:$playerid,U:$userid,W:$uniqueid,T:$team\>";
}


1;
