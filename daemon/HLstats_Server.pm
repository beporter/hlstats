package HLstats_Server;
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


sub new {
	my ($class_name, $serverId, $address, $port, $game, $currentMap) = @_;

	my ($self) = {};

	bless($self, $class_name);

	$self->{id}      = $serverId;
	$self->{address} = $address;
	$self->{port}    = $port;
	$self->{game}    = $game;

	$self->{map}     = $currentMap;
	$self->{numplayers} = 0;

	return $self;
}

1;
