#!/bin/bash
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

# get saved dirname; will return something with /tool at the end
saveDir=`dirname $(readlink -f ${0})`;

cd $saveDir;

case "$1" in
 start)
     echo "Starting HLStats...";
     if [ -f hlstats165.pid ]; then
        kill -0 `cat hlstats165.pid` >/dev/null 2>&1
        if [ "$?" == "0" ]; then
            echo "HLStats already running!"
        else
            rm -rf hlstats165.pid
            perl ../daemon/hlstats.pl >/dev/null 2>&1 &
            echo $! >hlstats165.pid
            echo "PID file created."
            echo "HLStats Started successfully!"
        fi
     else
        perl ../daemon/hlstats.pl >/dev/null 2>&1 &
        echo $! >hlstats165.pid
        echo "PID file created."
        echo "HLStats Started successfully!"
     fi
 ;;
 stop)
     echo "Stopping HLStats..."
     kill -9 `cat hlstats165.pid` >/dev/null 2>&1
     if [ "$?" == "0" ]; then
        rm -rf hlstats165.pid
        echo "HLStats Stopped successfully."
     else
        echo "HLStats is not running!"
     fi
 ;;
 restart)
     echo "Restarting HLStats..."
     kill -9 `cat hlstats165.pid` >/dev/null 2>&1
     if [ "$?" == "0" ]; then
         rm -rf hlstats165.pid
         perl ../daemon/hlstats.pl >/dev/null 2>&1 &
         echo $! >hlstats165.pid
         echo "PID file created."
         echo "HLStats Restarted successfully!"
     else
         echo "HLStats is not running!"
         if [ -f hlstats165.pid ]; then
           rm -rf hlstats165.pid
         fi
         perl ../daemon/hlstats.pl >/dev/null 2>&1 &
         echo $! >hlstats165.pid
         echo "PID file created."
         echo "HLStats Started successfully!"
     fi
 ;;
 *)
     echo "Usage: ./`basename $0` [ start | stop | restart ]"
 ;;
esac

exit 0
