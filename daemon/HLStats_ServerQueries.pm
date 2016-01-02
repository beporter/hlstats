package HLstats_ServerQueries;
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
# based on http://search.cpan.org/~masanorih/Net-SRCDS-Queries-0.0.3/
#

use warnings;
use strict;
use IO::Socket::INET;
use IO::Select;
use Carp qw(croak);
use Encode qw(from_to);

# implemented queries
# see http://developer.valvesoftware.com/wiki/Source_Server_Queries
# for all queries.

# http://developer.valvesoftware.com/wiki/Talk:Server_Queries#A2S_SERVERQUERY_GETCHALLENGE_not_working_since_last_HLDS_update
#use constant GETCHALLENGE => "\xFF\xFF\xFF\xFF\x57";
use constant GETCHALLENGE => "\xFF\xFF\xFF\xFF\x55\xFF\xFF\xFF\xFF";

use constant A2S_INFO     => "\xFF\xFF\xFF\xFFTSource Engine Query\0";
use constant A2S_PLAYER   => "\xFF\xFF\xFF\xFF\x55";
use constant A2S_RULES    => "\xFF\xFF\xFF\xFF\x56";
use constant MAX_SOCKBUF  => 65535;
use constant A2A_PING => "\xFF\xFF\xFF\xFF\x69";

sub new {
    my( $class, %args ) = @_;

    my $socket = IO::Socket::INET->new(
        Proto    => 'udp',
        Blocking => 0,
    ) or croak "cannot bind socket : $!";
    my $select = IO::Select->new($socket);
    my $self   = {
        socket   => $socket,
        select   => $select,
        timeout  => $args{timeout} || 3,
        encoding => $args{encoding} || undef,
        addr => $args{addr},
        port => $args{port}
    };
    $self->{float_order} =
        unpack( 'H*', pack( 'f', 1.05 ) ) eq '6666863f' ? 0 : 1;
    bless $self, $class;
}

#
# get the A2S Info
#
sub getA2S_Info {
    my($self)   = @_;
    my $select  = $self->{select};
    my $timeout = $self->{timeout};
    my $result;

    my $dest = sockaddr_in $self->{port}, inet_aton $self->{addr};
    $self->send_a2s_info($dest);

LOOP: while (1) {
		my @ready = $select->can_read($timeout);
		for my $fh (@ready) {
			my $sender = $fh->recv( my $buf, MAX_SOCKBUF );

			$result = $self->parse_packet( $buf );
		}
		# exit loop when you get nothing
        unless (@ready) {
            last LOOP;
        }
	}

	return $result;
}


#
# get current players
#
sub getA2S_Players {
    my($self)   = @_;
    my $select  = $self->{select};
    my $timeout = $self->{timeout};
    my $result;

    my $dest = sockaddr_in $self->{port}, inet_aton $self->{addr};
    $self->send_challenge($dest);

LOOP: while (1) {
		my @ready = $select->can_read($timeout);
		for my $fh (@ready) {
			my $sender = $fh->recv( my $buf, MAX_SOCKBUF );

			$result = $self->parse_packet( $buf );
		}
		# exit loop when you get nothing
        unless (@ready) {
            last LOOP;
        }
	}

	if($result->{type} eq 'A') {
		$self->send_a2s_player($dest,$result->{cnum});

		LOOP1: while (1) {
			my @ready = $select->can_read($timeout);
			for my $fh (@ready) {
				my $sender = $fh->recv( my $buf, MAX_SOCKBUF );

				$result = $self->parse_packet( $buf );
			}
			# exit loop when you get nothing
			unless (@ready) {
				last LOOP1;
			}
		}
	}

	return $result;
}

#
# check if alive
#
sub is_alive {
	my($self)   = @_;
    my $select  = $self->{select};
    my $timeout = $self->{timeout};
    my $result;

    my $dest = sockaddr_in $self->{port}, inet_aton $self->{addr};
    $self->send_a2a_ping($dest);

    LOOP: while (1) {
		my @ready = $select->can_read($timeout);
		for my $fh (@ready) {
			my $sender = $fh->recv( my $buf, MAX_SOCKBUF );

			$result = $self->parse_packet( $buf );
		}
		# exit loop when you get nothing
        unless (@ready) {
            last LOOP;
        }
	}

	return $result;
}


#
# intern stuff
#
sub send_challenge {
    my( $self, $dest ) = @_;
    my $socket = $self->{socket};
    $socket->send( GETCHALLENGE, 0, $dest );
}

sub send_a2s_info {
    my( $self, $dest ) = @_;
    my $socket = $self->{socket};
    $socket->send( A2S_INFO, 0, $dest );
}

sub send_a2a_ping {
    my( $self, $dest ) = @_;
    my $socket = $self->{socket};
    $socket->send( A2A_PING, 0, $dest );
}

sub send_a2s_player {
    my( $self, $dest, $cnum ) = @_;
    my $socket = $self->{socket};
    $socket->send( A2S_PLAYER . $cnum, 0, $dest );
}

sub send_a2s_rules {
    my( $self, $dest, $cnum ) = @_;
    my $socket = $self->{socket};
    $socket->send( A2S_RULES . $cnum, 0, $dest );
}

#
# intern parser stuff
#
sub parse_packet {
    my( $self, $buf ) = @_;
    my $t = unpack 'x4a', $buf;
    my $result;

    if ( $t eq 'A' ) {
        $result = $self->parse_challenge($buf);
    }
    elsif ( $t eq 'I' ) {
        $result = $self->parse_a2s_info($buf);
    }
    elsif ( $t eq 'D' ) {
        $result = $self->parse_a2s_player($buf);
    }
    elsif ( $t eq 'E' ) {
        $result = $self->parse_a2s_rules($buf);
    }
    elsif ( $t eq 'j' ) {
        return 1;
    }
    return $result;
}

sub parse_a2s_info {
    my( $self, $buf ) = @_;
    my( $type, $version, $str ) = unpack 'x4aca*', $buf;
    my( $sname, $map, $dir, $desc, $remains ) = split /\0/, $str, 5;
    my(
        $app_id, $players, $max,    $bots, $dedicated,
        $os,     $pw,      $secure, $remains2
    ) = unpack 'vcccaacca*', $remains;
    my( $gversion, $remains3 ) = split /\0/, $remains2, 2;

    my $result = {
        type      => $type,
        version   => $version,
        sname     => $sname,
        map       => $map,
        dir       => $dir,
        desc      => $desc,
        app_id    => $app_id,
        players   => $players,
        max       => $max,
        bots      => $bots,
        dedicated => $dedicated,
        os        => $os,
        password  => $pw,
        secure    => $secure,
        gversion  => $gversion,
    };
    my( $edf, $opt ) = unpack 'ca*', $remains3;
    if ( $edf & 0x80 ) {
        my $port;
        ( $port, $opt ) = unpack 'va*', $opt;
        $result->{port} = $port;
    }
    if ( $edf & 0x40 ) {
        # print "opt is spectator port\n";
        $result->{spectator} = '';
    }
    if ( $edf & 0x20 ) {
        chop $opt;
        $result->{game_tag} = $opt;
    }
    return $result;
}

sub parse_a2s_player {
    my( $self, $buf ) = @_;
    my $encoding = $self->{encoding};
    my( $type, $num_players, $followings ) = unpack 'x4aca*', $buf;
    my $player_info;
    while ($followings) {
        my( $index, $r1 ) = unpack 'ca*', $followings;
        my( $name, $r2 ) = ( split /\0/, $r1, 2 );
        from_to( $name, 'utf8', $encoding ) if $encoding;
        my( $kills, $connected, $r3 ) = unpack 'lfa*', $r2;
        # XXX : reverse float for some environment
        if ( $self->{float_order} ) {
            my $hex = unpack 'H*', pack 'f', $connected;
            my @b;
            $hex =~ s/(.{2})/push(@b, $1)/seg;
            $hex = join '', reverse @b;
            $connected = unpack 'f', pack 'H*', $hex;
        }
        push @{$player_info},
            {
            name      => $name,
            kills     => $kills,
            connected => $connected,
            };
        $followings = $r3;
    }

    my $result = {
        num_players => $num_players,
        player_info => $player_info,
    };
    return $result;
}

sub parse_a2s_rules {
    my( $self, $buf ) = @_;
    my $encoding = $self->{encoding};
    my( $type, $num_rules, $r1 ) = unpack 'x4aca*', $buf;
    my( undef, $followings ) = ( split /\0/, $r1, 2 );
    my $rules_info;
    while ($followings) {
        my( $name, $value, $r2 ) = ( split /\0/, $followings, 3 );
        push @{$rules_info},
            {
            name  => $name,
            value => $value,
            };
        $followings = $r2;
    }

    my $result = {
        type       => $type,
        num_rules  => $num_rules,
        rules_info => $rules_info,
    };
    return $result;
}

sub parse_challenge {
    my( $self, $buf ) = @_;
    my( $type, $cnum ) = unpack 'x4aa4', $buf;
    return {
        type => $type,
        cnum => $cnum,
    };
}

use constant base_number => 76561197960265728;
sub id2community_id {
    my( $self, $id ) = @_;
    my( $n1, $n2, $n3 ) = ( $id =~ /STEAM_(\d):(\d):(\d+)/i );
    return unless defined $n1;
    my $community_id = base_number + $n2 + ( $n3 * 2 );
    return $community_id;
}

1;
