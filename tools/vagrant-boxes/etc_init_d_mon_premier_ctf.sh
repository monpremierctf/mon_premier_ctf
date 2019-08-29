
#!/bin/sh

### BEGIN INIT INFO
# Provides:		mon_premier_ctf
# Required-Start:	
# Required-Stop:	
# Default-Start:	2 3 4 5
# Default-Stop:		
# Short-Description:	Mon premier CTF
### END INIT INFO

set -e
case "$1" in
  start)
	cd /home/vagrant/mon_premier_ctf; 
    ./go_first_install_webserver_run -y
	;;
  stop)
	;;
  *)
	exit 1
esac

exit 0