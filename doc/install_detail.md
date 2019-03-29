# Détail de ce qui se passe

Doc moyennement à jour...
On n'utilise plus de sudo.
Tout est automatisé dans le script go_first_install.


## Récupérer le dépôt et démarrer CTFd


```bash
$ git clone https://github.com/monpremierctf/mon_premier_ctf.git
$ cd mon_premier_ctf
$ cd CTFd
$ sudo docker-compose up
Creating network "ctfd_default" with the default driver
Creating network "ctfd_internal" with the default driver
Pulling cache (redis:4)...
4: Pulling from library/redis
f7e2b70d04ae: Pull complete
421427137c28: Pull complete
[..]
db_1     | 2019-03-10 19:43:12 0 [Warning] 'proxies_priv' entry '@% root@4f47270c6554' ignored in --skip-name-resolve mode.
db_1     | 2019-03-10 19:43:12 0 [Note] mysqld: ready for connections.
db_1     | Version: '10.4.3-MariaDB-1:10.4.3+maria~bionic'  socket: '/var/run/mysqld/mysqld.sock'  port: 3306  mariadb.org binary distribution
ctfd_1   | ..................db is ready
ctfd_1   |  * Loaded module, <module 'CTFd.plugins.challenges' from '/opt/CTFd/CTFd/plugins/challenges/__init__.py'>
ctfd_1   |  * Loaded module, <module 'CTFd.plugins.dynamic_challenges' from '/opt/CTFd/CTFd/plugins/dynamic_challenges/__init__.py'>
ctfd_1   |  * Loaded module, <module 'CTFd.plugins.flags' from '/opt/CTFd/CTFd/plugins/flags/__init__.py'>
ctfd_1   | Starting CTFd
ctfd_1   | [2019-03-10 19:43:17 +0000] [1] [INFO] Starting gunicorn 19.9.0
ctfd_1   | [2019-03-10 19:43:17 +0000] [1] [INFO] Listening at: http://0.0.0.0:8000 (1)
ctfd_1   | [2019-03-10 19:43:17 +0000] [1] [INFO] Using worker: geventwebsocket.gunicorn.workers.GeventWebSocketWorker
ctfd_1   | [2019-03-10 19:43:17 +0000] [74] [INFO] Booting worker with pid: 74
ctfd_1   |  * Loaded module, <module 'CTFd.plugins.challenges' from '/opt/CTFd/CTFd/plugins/challenges/__init__.py'>
```
On se prépare un café le temps que tout s'installe.
La commande $ sudo docker-compose up va occuper le terminal, vous ferez Ctrl-C pour terminer CTFd. Ouvrez un autre terminal pour charger la config.
La première fois, il n'est pas possible de lancer docker-compose avec l'option -d.
CTFd est fonctionnel mais vide. Il est possible de lancer un navigateur sur  http://localhost:8000 et créer un ctf de zéro à la main. 


</br>


## Générer le fichier de config du CTF


Les challenges et leurs flags sont décrits dans des répertoires autonomes.
La liste des répertoires est dans *mon_premier_ctf/challenges_list.cfg*

```bash
$ cat challenges_list.cfg 
#
#
# Un répertoire contenant des challenges par ligne
# Commentaires avec #

ctf-shell
ctf-escalation
ctf-transfert
ctf-net-pcap
ctf-sqli
```

Dans Chaque répertoire le fichier challenges.cfg détaille les challenges.

ex: [ctf-net-pcap//challenges.cfg](ctf-net-pcap/challenges.cfg)


On utilise *go_gen_conf  [IP du serveur à insérer dans les descriptions]* pour générer le fichier de config.
Il faut peut-être faire un sudo suivant le répertoire d'installation.
```bash
$ cd ~/mon_premier_ctf
$ ./go_gen_conf  12.0.0.12
Extracting default config
Generating config
Enter [ctf-shell]
- Processing Challenge_1
- Processing Challenge_2
[...]
myconfig.zip generated for ctfd
```

On copie ensuite ce fichier myconfig.zip dans le répertoire CTFd. Et on le charge.
```bash
$ cp myconfig.zip CTFd/
$ cd CTFd/
$ sudo docker exec ctfd_ctfd_1 python import.py myconfig.zip
 * Loaded module, <module 'CTFd.plugins.challenges' from '/opt/CTFd/CTFd/plugins/challenges/__init__.py'>
 * Loaded module, <module 'CTFd.plugins.dynamic_challenges' from '/opt/CTFd/CTFd/plugins/dynamic_challenges/__init__.py'>
 * Loaded module, <module 'CTFd.plugins.flags' from '/opt/CTFd/CTFd/plugins/flags/__init__.py'>
backup.namelist()=
['db/tags.json', 'db/awards.json', 'db/submissions.json', 'db/flags.json', 'db/users.json', 'db/config.json', 'db/pages.json', 'db/challenges.json', 'db/teams.json', 'db/alembic_version.json', 'db/hints.json', 'db/files.json', 'db/tracking.json', 'db/solves.json', 'db/dynamic_challenge.json', 'db/unlocks.json', 'db/notifications.json', 'uploads/ctf-transfert/dechiffre_02', 'uploads/ctf-transfert/flag01_enc.bin', 'uploads/ctf-transfert/dechiffre_03']
Extracting :uploads/ctf-transfert/dechiffre_02
FilesystemUploader:copyfileobj(fileobj, /var/uploads/ctf-transfert/dechiffre_02, 16384)
Extracting :uploads/ctf-transfert/flag01_enc.bin
FilesystemUploader:copyfileobj(fileobj, /var/uploads/ctf-transfert/flag01_enc.bin, 16384)
Extracting :uploads/ctf-transfert/dechiffre_03
FilesystemUploader:copyfileobj(fileobj, /var/uploads/ctf-transfert/dechiffre_03, 16384)
$ 
```

</br>

### Pousser une config de démo du ctf

Si la génération de la config n'a pas fonctionné. Il est possible d'utiliser une config de démo.

```bash
$ cp ../ctfd_config/myconfig.zip .
$ sudo docker exec ctfd_ctfd_1 python import.py myconfig.zip
```
Cette configuration continent des adresses IP statiques.


</br>


## Lancement des Dockers et VMs des challenges

ctf-shell, ctf-escalation et ctf-transfert ont besoin de l'image ctf-sshd
```bash
$ cd ~/mon_premier_ctf
sudo docker build -t ctf-sshd ./ctf-sshd
```

Pour l'instant les dockers se lancent manuellement dans chaque répertoire.
!! Seuls quelques répertoires sont bien configurés. !!
- ctf-shell
- ctf-escalation
- ctf-transfert
- ctf-sqli


Pour l'instant chaque docher-compose est bloquant et lancé dans son shell propre.
Lançer ctf-shell
```bash
$ cd ~/mon_premier_ctf
$ cd ctf-shell
$ sudo docker-compose up
```

Dans un autre shell, lancer ctf-escalation
```bash
$ cd ~/mon_premier_ctf
$ cd ctf-escalation
$ sudo docker-compose up
```

Dans un autre shell, lancer ctf-transfert
```bash
$ cd ~/mon_premier_ctf
$ cd ctf-transfert
$ sudo docker-compose up
```

Dans un autre shell, lancer le docker ctf-sqli:
```bash
$ cd ctf-sqli/
$ sudo docker-compose up
Starting ctfsqli_mysql_1 ... 
Starting ctfsqli_mysql_1 ... done
Starting ctfsqli_php_1 ... 
Starting ctfsqli_php_1 ... done
Starting ctfsqli_web_1 ... 
Starting ctfsqli_web_1 ... done
Attaching to ctfsqli_mysql_1, ctfsqli_php_1, ctfsqli_web_1
mysql_1  | 2019-03-09 07:55:05 0 [Warning] TIMESTAMP with implicit DEFAULT value is deprecated. Please use --explicit_defaults_for_timestamp server option (see documentation for more details).
[...]
web_1    | 2019/03/09 07:55:58 [error] 6#6: *1 open() "/www_site/favicon.ico" failed (2: No such file or directory), client: 172.17.0.1, server: localhost, request: "GET /favicon.ico HTTP/1.1", host: "localhost:8080"[...]
web_1    | 172.17.0.1 - - [09/Mar/2019:07:56:05 +0000] "GET /beware_cat05.png HTTP/1.1" 200 58097 "http://localhost:8080/login.php" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:61.0) Gecko/20100101 Firefox/61.0" "-"
```
Cette command est bloquante, et permet de voir les logs des requêtes au serveur s'afficher.



</br>

Cette doc est temporaire, on fera quelque-chose de mieux packagé rapidement.
