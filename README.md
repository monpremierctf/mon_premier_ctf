# Mon premier CTF



Vous désirez organiser un Capture The flag à destination de grands débutants ? Ce repo est pour vous !

Vous trouverez ici, une série de challenges destinés à permettre aux participants de commencer à se constituer la trousse à outil minimale pour participer à un CTF.


</br>
Un jeu de slide à destination des participants est disponible en https://github.com/monpremierctf/mon_premier_ctf/blob/master/doc/Introduction_au_CTF.pdf

</br>

Vous trouverez dans ce document des instructions détaillées sur :

- [Les fonctionnalités de la plateforme](#Fonctionnalités)
- [Installer et lancer le serveur](#Installer-et-démarrer-le-serveur)
- [Se connecter au CTF](#Participer-au-CTF)
- [Administrer le serveur avec l'interface web](#Administrer-le-CTF)
- [Monitorer le serveur dans la console](#Monitorer)
- [Customiser le CTF en choisissant/ajoutant des challenges](#Customisation)



</br>
</br>

# Fonctionnalités

Tous les challenges sont accessibles à travers le navigateur Web.
Il n'y a aucune installation à faire sur le poste des utilisateurs.


## Challenges textuels

![](doc/screenshot/challenge_text.png)


## Utilisation d'un terminal 'Kali like' dans le browser

![](doc/screenshot/challenge_xterm.png)


## Lancement à la demande de serveurs dédiés

![](doc/screenshot/challenge_server.png)


## Serveur de type ssh ou Web

![](doc/screenshot/challenge_sqli.png)


## Editeur et Execution de code python

![](doc/screenshot/challenge_python.png)


</br>
</br>

# Installer et démarrer le serveur

3 types d'installations sont possibles:

- Zéro install: le site http://yoloctf.org
- Installation de la VM pré-configurée
- Installation de zéro sur un serveur ubuntu

</br>

## Methode 1: Zero install  


```
http://yoloctf.org
```

</br>
Un serveur de test est disponible sur http://yoloctf.org. Vous pouvez vous créer un compte avec le code 'yolo'. Il est régulièrement effaçé et réinstallé. La durée de vie d'un profil est de 30h... Idéal pour un atelier de 2h qui déborde un peu. Ensuite le terminal ne se lance plus. Il faudra recréer un profil.


</br>
</br>
</br>

## Methode 2: Installation de la VM YOLO CTF

```
https://yoloctf.org/yoloctf/iso/yoloctf.ova
```

Pour tester tranquillement sur mon PC, ou organiser un CTF sur mon LAN : Je télécharge une VM prète à l'emploi

[==> Instructions pour l'installation de la VM](doc/install_vm.md)


</br>
</br>
</br>

## Methode 3: Installation from scratch sur Ubuntu

```
https://yoloctf.org/yoloctf/iso/mon_premier_ctf_install.zip
```

Pour organiser un CTF sur Internet, je fais une installation sur un Ubuntu Server vierge chez Amazon ou OVH


[==> Instructions pour l'installation sur un serveur ubuntu vierge](doc/install_ubuntu.md)


</br>
</br>
</br>



# Participer au CTF


## Accéder au site Web du CTF

Vous pouvez vous connecter avec votre navigateur Web : 
```
http://IP_DU_SERVEUR/
```

La connection en HTTP (tout les messages sont en clairs) va être redirigée vers une connection en HTTPS (les messages sont chiffrés et le serveur est authentifié).
Vous allez avoir une alerte de sécurité. C'est normal.


<img src="doc/screenshot/site_alerte_firefox.png" width="50%" height="50%">

Le serveur Web a généré ses propres certificats pour utiliser une liaison HTTPS (HTTP Sécurisée). L'alerte vous prévient qu'aucune autorité de certification 'officielle' ne valide les clefs de sécurité de ce site, et qu'il peut donc y a donc un risque.


<img src="doc/screenshot/site_alerte_firefox_accept.png" width="50%" height="50%">

Il faut ajouter une exception  pour accepter le certificat non signé qui est présenté par le site.
Cliquez sur [ Accepter le risque et poursuivre]

Sur un autre navigateur (chrome, explorer..) ou si vous avez un antivirus installé le message peut être différent. Mais le principe reste le même. Il faut accepter de prendre le risque de reconnaitre le certificat autosigné du site.


<img src="doc/screenshot/site_alerte_bitdefender.jpg" width="50%" height="50%">







</br>
</br>
</br>



# Administrer le CTF

## Se logguer avec le compte Admin

![](doc/screenshot/localhost_login_admin.png)

Le mot de passe du compte admin est donné dans le terminal:
- admin
- lvlwxnjygeycmias

</br>

## Changer le mot de passe du compte Admin

Le mot de passe étant prédéfini sur la VM, il est nécessaire de le changer :
![](doc/screenshot/localhost_profile_tab.png)

Allez sur l'onglet 'Mon Compte' dans la table des matières en bas à gauche.
Définissez un nouveau mot de passe et cliquez [Change].

</br>


## Monitoring light

![](doc/screenshot/localhost_admin.png)

L'onglet [Admin] en bas à gauche permet de voir :
- Le nombre de sessions actives
- Le nombre d'utilisateurs
- Les flags soumis par les utilisateurs
- Les containers démarrés par les utilisateurs

</br>


## Reseter les Flags

![](doc/screenshot/localhost_admin.png)

Dès l'instant ou le serveur est démarré, les participants peuvent commencer à valider les flags.
Vous pouvez aussi vouloir tester un peu. Le lien [ClearFlags] en bas de page permet d'effacer tous les flags validés.

</br>


## Consultez les feedbacks

L'onglet Feedback est à utiliser pour obtenir un retour des utilisateurs en fin de CTF, et permet de détailler un problème rencontré sur le site ou un challenge.


</br>
</br>
</br>


# Monitorer

## Monitoring du serveur et de ses containers

Une fois que votre serveur tourne, c'est une bonne idée de surveiller ce qui se passe et vérifier la mémoire libre, la charge du CPU et la place restante sur le disque.

#### Liste des containers

![](doc/screenshot/VM_docker.jpg)

```
docker ps --format '{{.Names}}'
```

#### Consommation CPU et mémoire par les containers

![](doc/screenshot/VM_stats.jpg)

```
docker stats --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}"
```

#### Analyse de logs

```
# docker logs challenge-box-provider
```

#### Monitoring global en interface web sur http://localhost:8888

````
chmod a+x tools/monitor.sh
tools/monitor.sh
````


</br>
</br>

# Customisation

Pour ajouter des challenges jetez un oeil à : [doc/create_new_challenges.md](doc/create_new_challenges.md)





Enjoy !
