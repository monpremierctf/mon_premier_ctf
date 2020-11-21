# Installation sur un serveur Ubuntu



Testé sur Ubuntu 18.04.01-desktop et Ubuntu 18.04.02-server. 

## Pre-requis

- Les ports HTTP, HTTPS, SSH sont ouverts.
- L'utilisateur a les droits sudo.
- S'il y a besoin d un proxy, il est configuré pour 'apt-get'.


## Télécharger & dézipper le package d'install


Télécharger https://drive.google.com/file/d/1LvcRQ8aUUXzW4xIUsc8UmsR_kseuti4i/view?usp=sharing

Le téléchargement se fait à partir d'un navigateur web ou d'un script.
````
wget https://raw.githubusercontent.com/monpremierctf/mon_premier_ctf/master/downld_from_googledrive_zippackage.sh
chmod a+x downld_from_googledrive_zippackage.sh
./downld_from_googledrive_zippackage.sh
unzip mon_premier_ctf_install.zip
````
Le fichier fait 3,5G. En fonction de votre liaison adsl/fibre ça peut prendre du temps.
Il contient toutes les images docker nécessaires aux challenges.



## Installation des outils

```
# cd mon_premier_ctf
# ./ctf_install
```

Si Docker n'était pas installé ou si l'utilisateur n'était pas dans le groupe Docker, il faut suivre les instructions:
- Si vous êtes sur le serveur en direct, il faut le <red>REBOOTER</red>.
- Si vous êtes en ssh, il faut simplement vous déconnecter et vous reconnecter pour que votre environnement et vos nouveaux groupes soient pris en compte.

Note: on peut ne pas rebooter en utilisant 'newgrp docker', et réouvrant un terminal, mais c'est plus propre de rebooter...

Si on a rebooté, on relance l'installation
```
# cd mon_premier_ctf
# ./ctf_install
```

Le script vérifie que l'installation des outils est correcte et passe à l'installation du CTF.

## Configurer & Lancer

On retrouve le même état que la VM...

[Suite des manips](install_vm.md#Personnaliser-un-peu-la-config)



