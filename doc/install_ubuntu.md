# Installation sur un serveur Ubuntu



Testé sur Ubuntu 18.04.01-desktop et Ubuntu 18.04.02-server. 

## Pre-requis

- Les ports HTTP, HTTPS, SSH sont ouverts.
- L'utilisateur a les droits sudo.
- S'il y a besoin d un proxy, il est configuré pour 'apt-get'.


## Télécharger & dézipper le package d'install


Télécharger https://drive.google.com/file/d/1LvcRQ8aUUXzW4xIUsc8UmsR_kseuti4i/view?usp=sharing

````
wget https://drive.google.com/file/d/1LvcRQ8aUUXzW4xIUsc8UmsR_kseuti4i/view?usp=sharing
unzip monpremierctf_install.zip
````
Le fichier fait 3,5G. En fonction de votre liaison adsl/fibre ça peut prendre du temps.
Il contient toutes les images docker nécessaires aux challenges.



## Installation des outils

```
# cd mon_premier_ctf
# ./go_first_install_webserver_run
```

Si Docker n'était pas installé ou si l'utilisateur n'était pas dans le groupe Docker, il faut suivre les instructions et <red>REBOOTER</red> le serveur.

Note: on peut ne pas rebooter en utilisant 'newgrp docker', et réouvrant un terminal, mais c'est plus propre de rebooter...

Si on a rebooté, on relance l'installation
```
# cd monpremierctf
# ./go_first_install_webserver_run
```

Le script vérifie que l'installation des outils est correcte et passe à l'installation du CTF.

## Configurer & Lancer

On retrouve le même état que la VM...

[Suite des manips](install_vm.md#Personnaliser-un-peu-la-config)



