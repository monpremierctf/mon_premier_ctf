# Mon premier CTF



Si vous désirez organiser un Capture The flag à destination de grands débutants, ce repo est pour vous.
Vous trouverez ici, une série de challenges destinés à permettre aux participants de commencer à se constituer la trousse à outil minimale pour participer à un CTF.

</br>

## Prerequis

Testé sur Ubuntu 18.04.01-desktop  avec un utilisateur ayant les droits sudo.

```bash
$ sudo apt-get update
$ sudo apt-get install docker-compose
```
Installer docker et docker-compose


</br>

## Récupérer le dépôt et démarrer CTFd


```bash
$ git clone https://github.com/monpremierctf/mon_premier_ctf.git
$ cd mon_premier_ctf
$ cd CTFd
$ sudo docker-compose up
```
On se prépare un café le temps que tout s'installe.
La commande $ sudo docker-compose up va occuper le terminal, vous ferez Ctrl-C pour terminer CTFd. Ouvrez un autre terminal pour charger la config.
La première fois, il n'est pas possible de lancer docker-compose avec l'option -d.
CTFd est fonctionnel mais vide. Il est possible de lancer un navigateur sur  http://localhost:8000 et créer un ctf de zéro à la main. 


</br>

## Pousser la config de démo du ctf

```bash
$ cp ../ctfd_config/FRTW.zip .
$ sudo docker exec ctfd_ctfd_1 python import.py FRTW.zip
```
Cette configuration continent des adresses IP statiques.

Lancer un navigateur sur http://localhost:8000

Clicker sur Login et se loguer en tant que 'admin' avec le mot de passe 'CTFPasswordZ'.

L'admin peut se créer une équipe, mais ce n'est pas souhaitable.
Cliquer sur Admin, puis Challenges. Il est possible d'éditer les challenges dans l'interface.

Cliquer à gauche sur CTFd, à droite sur logout, puis register.
Créer un user qui sera capitaine...
Create unofficial team
Le capitaine d'équipe créé l'équipe et partage le mot de passe avec ses coéquipiers.
Les coéquipers font Join unofficial team



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
```

Dans Chaque répertoire le fichier challenges.cfg détaille les challenges.

ex: [ctf-pcap//challenges.cfg](ctf-pcap/challenges.cfg)


On utilise *go_gen_conf [template de conf] [IP du serveur à insérer dans les descriptions]* pour générer le fichier de config.
```bash
$ cd ~/mon_premier_ctf
$ ./go_gen_conf empty_conf.zip 12.0.0.12
Extracting default config
Generating config
Enter [ctf-shell]
- Processing Challenge_1
- Processing Challenge_2
Enter [ctf-escalation]
- Processing Challenge_1
- Processing Challenge_2
- Processing Challenge_3
Enter [ctf-transfert]
- Processing Challenge_1
- Copy [ctf-transfert/flag01_enc.bin]  => ctfd_config/tmp/uploads/ctf-transfert/flag01_enc.bin
- Processing Challenge_2
- Copy [ctf-transfert/dechiffre_02]  => ctfd_config/tmp/uploads/ctf-transfert/dechiffre_02
- Processing Challenge_3
- Copy [ctf-transfert/dechiffre_03]  => ctfd_config/tmp/uploads/ctf-transfert/dechiffre_03
Replacing IPSERVER by 12.0.0.12
  adding: db/tags.json (stored 0%)
  adding: db/awards.json (stored 0%)
  adding: db/submissions.json (stored 0%)
  adding: db/flags.json (deflated 76%)
  adding: db/users.json (deflated 34%)
  adding: db/config.json (deflated 70%)
  adding: db/pages.json (deflated 56%)
  adding: db/challenges.json (deflated 72%)
  adding: db/teams.json (stored 0%)
  adding: db/alembic_version.json (deflated 4%)
  adding: db/hints.json (stored 0%)
  adding: db/files.json (deflated 69%)
  adding: db/tracking.json (deflated 55%)
  adding: db/solves.json (stored 0%)
  adding: db/dynamic_challenge.json (stored 0%)
  adding: db/unlocks.json (stored 0%)
  adding: db/notifications.json (stored 0%)
  adding: uploads/ctf-transfert/dechiffre_02 (deflated 67%)
  adding: uploads/ctf-transfert/flag01_enc.bin (deflated 28%)
  adding: uploads/ctf-transfert/dechiffre_03 (deflated 67%)
/home/yop/tttt/mon_premier_ctf
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


Cette doc est temporaire, on fera quelque-chose de mieux packagé rapidement.