# Mon premier CTF



Si vous désirez organiser un Capture The flag à destination de grands débutants, ce repo est pour vous.
Vous trouverez ici, une série de challenges destinés à permettre aux participants de commencer à se constituer la trousse à outil minimale pour participer à un CTF.

</br>
Un jeu de slide à destination des participants est disponible en https://github.com/monpremierctf/mon_premier_ctf/blob/master/doc/Introduction_au_CTF.pdf
</br>

## Prerequis

Prévoir une VM avec 3G de mémoire.</br>
Testé sur Ubuntu 18.04.01-desktop  avec un utilisateur ayant les droits sudo et appartenant au groupe docker.</br>
Un accès internet est indispensable pour télécharger les images docker de référence.</br>
</br>
Les droits sudo ne servent que pour l'installation et le démarrage du service docker.</br>
L'installation, la configuration et le lancement des services du CTF se fait avec un compte utilisateur sans utiliser de sudo, sous réserve que le compte fasse parti du groupe 'docker'.  </br>
```bash
sudo gpasswd -a $USER docker
```
Après cet ajout, il faut déconnecter/reconnecter l'utilisateur. Idéalement rebooter le serveur...</br>
</br>
Installer docker et docker-compose
```bash
$ sudo apt-get update
$ sudo apt-get install docker-compose
```

Lancer le service docker si ce n'est pas déjà fait
```bash
$ sudo service docker start
```

</br>

## Démarrage rapide 


Cloner mon_premier_ctf sur github.
```bash
$ git clone https://github.com/monpremierctf/mon_premier_ctf.git
$ cd mon_premier_ctf
```

Par défaut les challenges affichent une IP locale : 127.0.0.1</br>
Editer ./challenges_list.cfg pour remplacer par l'IP du serveur: [12.0.0.10]
```
$ vi ./challenges_list.cfg
$ ./go_first_install_run 
```
La configuration est générée, les dockers buildés et lancés.


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


Enjoy !
