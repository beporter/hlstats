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

# Name of the screen
# please change this to identify the screen session
NAME=hlstats_daemon

# path to the hlstats files
#DIR=/path/to/HLStats/daemon
DIR=/home/banana/code/HLStats/daemon

# exutable file
DAEMON=hlstats.pl

# optional parameters for hlstats
PARAMS=""

# text for the output on the console
DESC="HLStats 1.65"

# check if the directory
if [ ! -e $DIR ] ; then
	echo "Path to hlstats daemon folder is wrong."
	exit 1
fi

# check if screen is installed
testScreen=`command -v screen`;
if [ ! $testScreen ] ; then
	echo "screen command is not installed.";
	exit 1;
fi


case "$1" in
start)
     echo "Starting $DESC...";
     if [ -f /tmp/hlstats165.pid ]; then
        kill -0 `cat /tmp/hlstats165.pid` >/dev/null 2>&1
        if [ "$?" == "0" ]; then
            echo "$DESC already running!"
        else
            rm -rf /tmp/hlstats165.pid
            cd $DIR;
            screen -A -m -d -S $NAME perl ./$DAEMON $PARAMS

            echo $! > /tmp/hlstats165.pid
            echo "PID file created."
            echo "$DESC started successfully!"
        fi
     else
        cd $DIR;
        screen -A -m -d -S $NAME perl ./$DAEMON $PARAMS
        echo $! > /tmp/hlstats165.pid
        echo "PID file created."
        echo "$DESC started successfully!"
     fi
;;
stop)
     echo "Stopping $DESC..."
     screen -S $NAME -X quit

     if [ "$?" == "0" ]; then
        rm -rf /tmp/hlstats165.pid
        echo "$DESC stopped successfully."
     else
        echo "$DESC is not running!"
     fi
;;
restart)
     if [[ `screen -ls |grep $NAME` ]]
       then
       echo -n "Stopping $DESC."
       screen -S $NAME -X quit

       rm -rf /tmp/hlstats165.pid
       echo " ... done."
     else
       echo "Coulnd't find a running $DESC!"
     fi

     echo "Starting $DESC."
     cd $DIR; screen -A -m -d -S $NAME perl ./$DAEMON $PARAMS

     echo $! > /tmp/hlstats165.pid
     echo "PID file created."
     echo "$DESC restarted successfully!"
    ;;
*)
     echo "Usage: ./`basename $0` [ start | stop | restart ]"
;;
esac

exit 0
