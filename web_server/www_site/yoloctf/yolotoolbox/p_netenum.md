# Network enumeration
.
.
Nous venons de nous connecter à un réseau.

Nous cherchons à savoir quelles sont les machines actives, quel système d'exploitation elles utilisent, quels programmes elles font tourner et dans quelle version.
Ces informations permettrons peut être de trouver des failes de sécurité connues et de les utiliser.
.
.

## Discover Hosts

Nous cherchons les IP des machines connectées au réseau, et celles sous windows.


### Netdiscover

```
# netdiscover -r 192.168.206.0/24
```

### Netbios: Nbtscan

NBtscan permet d'identifier les machines connectées au réseau, fonctionnant sous windows, et exposant des services NetBios: http://www.inetcat.org/software/nbtscan.html

    # nbtscan 192.168.206.0/24

.
.
===================================================
## Port scanner
### Nmap
    # nmap 10.10.10.21
    # nmap -sV -sC -A 10.10.10.93
    # nmap -sV -sC -A 10.10.10.93 -p-
    # nmap -sU 10.10.10.93
        -sV : Attempts to determine the version of the service running on port
        -sC : Scan with default NSE scripts. Considered useful for discovery and safe
        -A  : Enables OS detection, version detection, script scanning, and traceroute
        -p- : Port scan all ports
        -sU : Scan des ports UDP
          


### More Tools

http://www.0daysecurity.com/penetration-testing/enumeration.html

.
.
=============================================================
## 21: Ftp

### Check anonymous access
````
# ftp 10.10.10.9
anonymous:anonymous
````

### Check anonymous access with NMap
````
# nmap --script=ftp-anon.nse 10.10.10.9
````

### Check anonymous access with Msfconsole 
````
# msfconsole -x “use auxiliary/scanner/ftp/anonymous; set ConnectTimeout=1; set FTPTimeout=1; set RHOSTS=xxx.xxx.xxx.0/19; run”
Note : for large network : set variable THREADS increase perf
````

### Brute force : Hydra
````
# hydra -t 1 -l admin -P ./password.lst -vV 10.10.10.9 ftp
````

### Brute force : Msfconsole 
````
> msfconsole -x “use auxiliary/scanner/ftp/ftp_login; set ConnectTimeout=1; set FTPTimeout=1; set RHOSTS=xxx.xxx.xxx.0/19; run” 
````    

### Mirror the site
Pour faire une copie en local de tout ce qui se trouve sur le ftp...
```
wget --mirror 'ftp://ftp_user:ftp_password@10.10.10.9'
```

. 
.
=============================================================
## 22: Ssh

### Brute force : Hydra
    # hydra -l admin             -P password_list.txt ssh://10.10.10.9
    # hydra -L username_list.txt -P password_list.txt ssh://10.10.10.9
    # hydra -l admin             -P password_list.txt ssh://10.10.10.9 -e nsr -V -t8 -f 
    
    -l     : user
    -L     : file with user list
    -P     : file with password list
    -e nsr : try "n" null password, "s" login as pass and/or "r" reversed login
    -V     : print each login:password tested
    -t 4   : 4 threads
.
.
=============================================================
## 23: Telnet

### Brute force : NMap
    # nmap -p 23 --script telnet-brute --script-args userdb=users.lst,passdb=/usr/share/john/password.lst,telnet-brute.timeout=8s <target>

.
.
=============================================================
## 53: Bind

### DNS Zone Transfert (axfr)
Permet d'obtenir des noms dns supplémentaires.

```
# cat /etc/hosts
10.10.10.13 cronos.htb
```

```
# dig axfr @10.10.10.13 cronos.htb

; <<>> DiG 9.11.4-P2-3-Debian <<>> axfr @10.10.10.13 cronos.htb
; (1 server found)
;; global options: +cmd
cronos.htb.		604800	IN	SOA	cronos.htb. admin.cronos.htb. 3 604800 86400 2419200 604800
cronos.htb.		604800	IN	NS	ns1.cronos.htb.
cronos.htb.		604800	IN	A	10.10.10.13
admin.cronos.htb.	604800	IN	A	10.10.10.13
ns1.cronos.htb.		604800	IN	A	10.10.10.13
www.cronos.htb.		604800	IN	A	10.10.10.13
cronos.htb.		604800	IN	SOA	cronos.htb. admin.cronos.htb. 3 604800 86400 2419200 604800
;; Query time: 29 msec
;; SERVER: 10.10.10.13#53(10.10.10.13)
;; WHEN: dim. sept. 08 11:47:44 CEST 2019
;; XFR size: 7 records (messages 1, bytes 203)
```
==> admin.cronos.htb





=============================================================
## 80: HTTP

### Magic file
    /robots.txt
    Comments in the HTML source code.


### Nman Enum script
    # nmap -script http-enum.nse -p80 192.168.168.168

### Dirbuster
    Find hidden files & dir
    https://github.com/digination/dirbuster-ng
    # dirb http://10.10.10.93
    # dirb http://10.10.10.93/aspnet_client/system_web/ fuzz.txt -r                        : -r dont search recurvively
    # dirb http://10.10.10.93/ /usr/share/wordlists/dirb/common.txt -r
    # dirb http://10.10.10.24/ /usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt

### Nikto
    Identify server
    https://github.com/sullo/nikto
    $ nikto -host xxx
    $ nikto -h 192.168.168.168 -p (port)

### Gobuster
    Find hidden files & dir
    https://github.com/OJ/gobuster
    wget https://github.com/OJ/gobuster/releases/download/v3.0.1/gobuster-linux-amd64.7z

    directory-list-2.3-medium.txt : assez longue
    /opt/gobuster/gobuster dir -w /usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt -u http://172.16.27.142  -l -x html,php,js,txt

    common.txt : plus rapide
    # /opt/gobuster/gobuster dir -u http://10.10.10.13  -l -x html,php,js,txt -w /usr/share/wordlists/SecLists/Discovery/Web-Content/common.txt


    # gobuster -u http://172.16.27.142/ -w /opt/SecLists/Discovery/Web-Content/common.txt -x html,php -s 200,301,401,403
    # ./gobuster -w /usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt -u http://172.16.27.142  -l -x html,php,js,txt
    # gobuster -u http://192.168.168.168/ -w /usr/share/seclists/Discovery/Web_Content/common.txt -s 200,204,301,302,307,403,500 –e

### Curl
    curl http://192.168.168.168/admin.php?action=users&login=0

    
### Web server Common Directories
    https://github.com/digination/dirbuster-ng/tree/master/wordlists
    IIS : https://github.com/digination/dirbuster-ng/blob/master/wordlists/vulns/iis.txt
    Kali Dictionaries:
    /usr/share/wordlists/dirb/common.txt
    /usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt

### Cewl
    Get word list from web site
    $ cewl http://192.168.168.168/index.html -m 2 -w cewl.lst


### wfuzz

    Si touts les url retournent un 200 OK, on fuzz sur la longueur de la réponse
    # wfuzz -z file,/usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt -u http://bart.htb/FUZZ/
    000018:  C=200    630 L     3775 W        158607 Ch       "2006"
    000017:  C=200    630 L     3775 W        158607 Ch       "download"
    000026:  C=200    630 L     3775 W        158607 Ch       "about"
    => 158607

    # wfuzz -z file,/usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt -u http://bart.htb/FUZZ/ --hh 158607
    ==================================================================
    ID      Response   Lines      Word         Chars          Payload
    ==================================================================

    000014:  C=302      0 L        0 W            0 Ch        ""
    000067:  C=200    548 L     2412 W        35529 Ch        "forum"
    001614:  C=200     80 L      221 W         3423 Ch        "monitor"


### Bruteforce HTTP Basic Auth
    
    hydra -l admin -P /usr/share/wordlists/rockyou.txt  -f 10.10.10.157 http-get /monitoring


### Screenshot of url

    wkhtmltoimage url pngfile 


