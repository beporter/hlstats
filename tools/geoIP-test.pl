#!/usr/bin/perl

use strict;
no strict 'vars';

require "../daemon/geoip/PurePerl.pm";

my $gi = Geo::IP::PurePerl->open( "../daemon/geoip/GeoIP.dat" );

my $country_code = $gi->country_code_by_addr('87.178.65.231');
my $country_name = $gi->country_name_by_addr('87.178.65.231');

print $country_code . "\n";
print $country_name . "\n";