#!/bin/sh -e

### BEGIN INIT INFO
# Provides:		mon_premier_ctf
# Required-Start:	$all
# Required-Stop:	
# Default-Start:	2 3 4 5
# Default-Stop:		0 1 6
# Short-Description:	Mon premier CTF
### END INIT INFO

# $ service --status-all
# $ sudo systemctl daemon-reload
# $ sudo systemctl start mon_premier_ctf
# $ sudo systemctl status mon_premier_ctf

set -e

DAEMON="/home/vagrant/mon_premier_ctf/go_first_install_webserver_run" #ligne de commande du programme.
daemon_OPT="-y -n"  #argument à utiliser par le programme.
DAEMONDIR="/home/vagrant/mon_premier_ctf" 
DAEMONUSER="vagrant" #utilisateur du programme
daemon_NAME="go_first_install_webserver_run" #Nom du programme (doit être identique à l'exécutable).



case "$1" in
  start)
    cd /home/vagrant/mon_premier_ctf
    echo "Restarted at : " >> ./last_restart.log 
    date >> ./last_restart.log 
    start-stop-daemon --name $daemon_NAME --start  --chdir $DAEMONDIR --chuid $DAEMONUSER --exec $DAEMON -- $daemon_OPT
    ;;
  stop)
    ;;
  *)
    exit 1
esac

exit 0
