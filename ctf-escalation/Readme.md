# Privilege escalation

## sticky bit : bash

USER01 theprofessor
PASSWORD01 darko  
USER02 tokio
PASSWORD02 esther
flag01.txt => /home/USER02/flag01.txt

##  sticky bit : less
USER02 tokio
PASSWORD02 esther
USER03 moscow
PASSWORD03 alvaro  
flag02.txt => /home/USER03/flag02.txt

##  sticky bit : find
USER03 moscow
PASSWORD03 alvaro 
USER04 rio
PASSWORD04 paco
flag03.txt => /home/USER04/flag03.txt

##  sticky bit :awk
USER04 rio
PASSWORD04 paco
USER05 berlin
PASSWORD05 ursula  
flag04.txt => /home/USER05/flag04.txt

##  sticky bit :vim
USER05 berlin
PASSWORD05 ursula 
USER06 nairobi
PASSWORD06 enrique  
flag05.txt => /home/USER06/flag05.txt

##  mysql running as root
USER06 nairobi
PASSWORD06 enrique 
USER07 monica
PASSWORD07 jaime  
flag06.txt => /home/USER07/flag06.txt
