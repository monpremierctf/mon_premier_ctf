# Mon premier CTF



Si vous désirez organiser un Capture The flag à destination de grands débutants, ce repo est pour vous.
Vous trouverez ici, une série de challenges destinés à permettre aux participants de commencer à se constituer la trousse à outil minimale pour participer à un CTF.

</br>
Un jeu de slide à destination des participants est disponible en https://github.com/monpremierctf/mon_premier_ctf/blob/master/doc/Introduction_au_CTF.pdf
</br>

## Prerequis

Prévoir une VM Ubuntu 18 vierge avec 3G de mémoire.</br>
Testé sur Ubuntu 18.04.01-desktop avec un utilisateur ayant les droits sudo et appartenant au groupe docker.</br>
Les challenges réseaux utilisent de nombreux ports 21, 25, 80, 8000, 8080, 9000,... Le mieux est de n'avoir aucun service d'activé.</br>
Un accès internet est indispensable pour télécharger les images docker de référence.</br>

</br>
Installer docker et docker-compose
```bash
$ sudo apt-get update
$ sudo apt-get install docker-compose
```
Les droits sudo ne servent que pour l'installation et le démarrage du service docker.</br>
L'installation, la configuration et le lancement des services du CTF se fait avec un compte utilisateur sans utiliser de sudo, sous réserve que le compte fasse parti du groupe 'docker'.  </br>
```bash
sudo gpasswd -a $USER docker
```
Après cet ajout, il faut déconnecter/reconnecter l'utilisateur. Idéalement rebooter le serveur...</br>

Lancer le service docker si ce n'est pas déjà fait
```bash
$ sudo service docker start
```
Eventuellement isntaller git
```bash
$ sudo apt-get install git
```
</br>

## Démarrage rapide 


Cloner mon_premier_ctf sur github.
```bash
$ git clone https://github.com/monpremierctf/mon_premier_ctf.git
$ cd mon_premier_ctf
```

Par défaut les challenges affichent une IP locale : 127.0.0.1</br>
Editer ./challenges_list.cfg pour remplacer par l'IP de votre serveur: [12.0.0.10]
```
$ cat challenges_list.cfg 
#
# L'adresse IP du serveur sur lequel tourne les challenges est entre []
# Cette IP sera insérée dans le texte de description des challenges
# Un répertoire de challenges par ligne
# Commentaires avec #
# 
#

[12.0.0.10]
ctf-shell
ctf-escalation
ctf-net
ctf-sqli
ctf-buffer
ctf-decode
ctf-transfert
```

```
$ ./go_first_install_run 

  __  __            ___               _            ___ _____ ___ 
 |  \/  |___ _ _   | _ \_ _ ___ _ __ (_)___ _ _   / __|_   _| __|
 | |\/| / _ \ ' \  |  _/ '_/ -_) '  \| / -_) '_| | (__  | | | _| 
 |_|  |_\___/_||_| |_| |_| \___|_|_|_|_\___|_|    \___| |_| |_| 

= Verification du système...
=> Verification : Ubuntu : Ok
#49-Ubuntu SMP Wed Feb 6 09:33:07 UTC 2019
=> Verification : Utilisateur non root : Ok
=> Verification : docker-compose installé : Ok
docker-compose version 1.17.1, build unknown
=> Verification : Docker installé : Ok
Docker version 18.09.2, build 6247962
=> Verification : python 2.7 installé : Ok
Python 2.7.15rc1
=> Verification : zip installé : Ok
=> Verification : Si non root, Utilisateur dans le group Docker : Ok
=> Verification : Docker démarré : Ok
Server Version: 18.09.2
Verification du système terminée
```
Le script vérifie que docker et docker-compose sont installés et lancés...</br>
Si nécessaire il propose une commande.</br></br>
Il affiche ensuite la configuration des challenges qui se fait grace aux fichier ctf-xxx/.env
```
Verification de la configuration

Dump challenges .env
Lecture de la liste de challenges : challenges_list.cfg
Set IPSERVER=192.168.1.22
['ctf-shell', 'ctf-escalation', 'ctf-net', 'ctf-sqli', 'ctf-buffer', 'ctf-decode', 'ctf-transfert']

[ctf-shell/.env]
PORT_SSH=2222

[ctf-escalation/.env]
PORT_SSH=2223

[ctf-net/.env]
CTFNET_IP_ALICE=192.168.1.222
CTFNET_TCPSERVER_PORT=9999
CTFNET_TELNET_PORT=23
CTFNET_FTP_PORT20=20
CTFNET_FTP_PORT=21
CTFNET_FTP_PASV_ADDRESS=12.0.0.10
CTFNET_SMTP_PORT=25
CTFNET_SMTP_MYNETWORK=12.0.0.1/24
CTFNET_POP3_PORT=110

[ctf-sqli/.env]
PORT_HTTP=8080

[ctf-buffer/.env]
PORT_SSH=2250

[ctf-decode/.env]
no .env file

[ctf-transfert/.env]
PORT_SSH=2224
PORT_HTTP=8080
La config est-elle ok ? (O/n) ? : 
Ok, on continue.

```
Regardez bien les IP et les ports. Si c'est ok, on continue et la configuration pour le serveur CTFd est générée: myctf.zip
```
Extracting default config
Mise à jour des fichiers de config propres à chaque challenge
MaJ des fichiers de config des challenges
Lecture de la liste de challenges : challenges_list.cfg
Set IPSERVER=192.168.1.22
['ctf-shell', 'ctf-escalation', 'ctf-net', 'ctf-sqli', 'ctf-buffer', 'ctf-decode', 'ctf-transfert']

[ctf-shell/challenge_set_config.sh]
no challenge_set_config.sh file

[ctf-escalation/challenge_set_config.sh]
no challenge_set_config.sh file

[ctf-net/challenge_set_config.sh]

[ctf-sqli/challenge_set_config.sh]
no challenge_set_config.sh file

[ctf-buffer/challenge_set_config.sh]
no challenge_set_config.sh file

[ctf-decode/challenge_set_config.sh]
no challenge_set_config.sh file

[ctf-transfert/challenge_set_config.sh]
no challenge_set_config.sh file
Generation des fichers de config pour CTFd
Lecture de la liste de challenges : challenges_list.cfg
Set IPSERVER=192.168.1.22
['ctf-shell', 'ctf-escalation', 'ctf-net', 'ctf-sqli', 'ctf-buffer', 'ctf-decode', 'ctf-transfert']
Enter [ctf-shell]
- Processing Challenge_0
- Processing Challenge_00
- Processing Challenge_1
- Processing Challenge_2
- Processing Challenge_3
- Processing Challenge_4
- Processing Challenge_5
- Processing Challenge_6
- Processing Challenge_7
- Processing Challenge_10
- Processing Challenge_11
- Processing Challenge_12
Enter [ctf-escalation]
- Processing Challenge_2
- Processing Challenge_3
- Processing Challenge_4
Enter [ctf-net]
- Processing Challenge_1
- Copy [ctf-net/flag01.gz.pcapng]  => ctfd_config/tmp/uploads/ctf-net/flag01.gz.pcapng
- Processing Challenge_2
- Copy [ctf-net/flag02.gz.pcapng]  => ctfd_config/tmp/uploads/ctf-net/flag02.gz.pcapng
- Processing Challenge_3
- Copy [ctf-net/flag03.gz.pcapng]  => ctfd_config/tmp/uploads/ctf-net/flag03.gz.pcapng
- Processing Challenge_4
- Copy [ctf-net/flag04.gz.pcapng]  => ctfd_config/tmp/uploads/ctf-net/flag04.gz.pcapng
- Processing Challenge_5
- Copy [ctf-net/flag05.gz.pcapng]  => ctfd_config/tmp/uploads/ctf-net/flag05.gz.pcapng
- Processing Challenge_arp_poison
- Processing Challenge_nc
- Processing Challenge_telnet
- Processing Challenge_ftp
- Processing Challenge_pop3
Enter [ctf-sqli]
- Processing Challenge_1
- Processing Challenge_2
- Processing Challenge_3
Enter [ctf-buffer]
- Processing Challenge_1
- Processing Challenge_2
- Processing Challenge_3
Enter [ctf-decode]
- Processing Challenge_hexa
- Processing Challenge_ascii
- Processing Challenge_base64
- Processing Challenge_base64_2
- Processing Challenge_url
Enter [ctf-transfert]
- Processing Challenge_1
- Copy [ctf-transfert/flag01_enc.bin]  => ctfd_config/tmp/uploads/ctf-transfert/flag01_enc.bin
- Processing Challenge_2
- Copy [ctf-transfert/dechiffre_02]  => ctfd_config/tmp/uploads/ctf-transfert/dechiffre_02
- Processing Challenge_3
- Copy [ctf-transfert/dechiffre_03]  => ctfd_config/tmp/uploads/ctf-transfert/dechiffre_03
updating: db/tags.json (stored 0%)
updating: db/submissions.json (stored 0%)
updating: db/solves.json (stored 0%)
updating: db/files.json (deflated 83%)
updating: db/alembic_version.json (deflated 4%)
updating: db/awards.json (stored 0%)
updating: db/pages.json (deflated 56%)
updating: db/users.json (deflated 34%)
updating: db/flags.json (deflated 82%)
updating: db/challenges.json (deflated 74%)
updating: db/tracking.json (deflated 55%)
updating: db/unlocks.json (stored 0%)
updating: db/dynamic_challenge.json (stored 0%)
updating: db/config.json (deflated 70%)
updating: db/notifications.json (stored 0%)
updating: db/hints.json (stored 0%)
updating: db/teams.json (stored 0%)
updating: uploads/ctf-transfert/dechiffre_02 (deflated 67%)
updating: uploads/ctf-transfert/flag01_enc.bin (deflated 28%)
updating: uploads/ctf-transfert/dechiffre_03 (deflated 67%)
updating: uploads/ctf-net/flag02.gz.pcapng (deflated 69%)
updating: uploads/ctf-net/flag05.gz.pcapng (deflated 73%)
updating: uploads/ctf-net/flag04.gz.pcapng (deflated 59%)
updating: uploads/ctf-net/flag03.gz.pcapng (deflated 71%)
updating: uploads/ctf-net/flag01.gz.pcapng (deflated 69%)
myctf.zip generated for ctfd
```
Si vous avez une erreur de type
```
ERROR: Couldn't connect to Docker daemon at http+docker://localunixsocket - is it running?
```
faites
````
sudo reboot
````
et reconnectez vous.

</br>
Enfin le script clone le git de CTFd, les dockers sont buildés et lancés, puis le fichier de config détaillant les challenges est chargé par CTFd.

```
Building ctfd
Step 1/12 : FROM python:2.7-alpine
 ---> 42fe74abbf15
[...]
Starting challenge_box_provider
Logs in /tmp/challenge_box_provider.log
 * Loaded module, <module 'CTFd.plugins.challenges' from '/opt/CTFd/CTFd/plugins/challenges/__init__.py'>
 * Loaded module, <module 'CTFd.plugins.dynamic_challenges' from '/opt/CTFd/CTFd/plugins/dynamic_challenges/__init__.py'>
 * Loaded module, <module 'CTFd.plugins.flags' from '/opt/CTFd/CTFd/plugins/flags/__init__.py'>

```

</br>


## Première utilisation Admin

Lancer un navigateur sur http://localhost:8000

Clicker sur **[Login]** et se loguer en tant que *admin* avec le mot de passe *CTFPasswordZ*. Pensez à changer le mot de passe...

L'admin peut se créer une équipe, mais ce n'est pas souhaitable. Il faut ignorer cette première page.

Cliquer sur **[Admin]**, puis **[Challenges]**. Il est possible d'éditer les challenges dans l'interface.
</br>
Pour afficher le graphe de progression des équipes, cliquer en haut à gauche sur le logo 'CTFd' puis sur 'Scoreboard'.



</br>

## Première utilisation Participant

Lancer un navigateur sur http://localhost:8000
Si vous êtes loggué en Admin, Cliquer à gauche sur **[CTFd]**, à droite sur **[logout]**.

Cliquer sur **[Register]**.

Créer un user qui sera capitaine...

Puis faire **[Create unofficial team]**
Le capitaine d'équipe créé l'équipe et partage le mot de passe avec ses coéquipiers.

Les coéquipers font **[Join unofficial team]**

</br>

# Monitoring du serveur et des containers

Stats sur les dockers
````
docker stats
````

Log de CTFd
```
cd CTFd
docker-compose logs
````

Monitoring global en interface web sur http://localhost:8888
````
chmod a+x tools/monitor.sh
tools/monitor.sh
````

Enjoy !
