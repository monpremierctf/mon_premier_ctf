# Installation sur un serveur Ubuntu



Testé sur Ubuntu 18.04.01-desktop et Ubuntu 18.04.02-server. 

## Pre-requis

### Pro

```
- Les ports HTTP, HTTPS, SSH sont ouverts.
- L'utilisateur a les droits sudo.
- S'il y a besoin d un proxy, il est configuré pour 'apt-get'.
```


## Télécharger & dézipper le package d'install


Télécharger https://yoloctf.org/yoloctf/iso/mon_premier_ctf_install.zip

Le fichier fait 3,5G. En fonction de votre liaison adsl/fibre ça peut prendre du temps.
Il contient toutes les images docker nécessaires aux challenges.


```
# wget https://yoloctf.org/yoloctf/iso/mon_premier_ctf_install.zip
# sudo apt-get update
# sudo apt-get install unzip
# unzip mon_premier_ctf_install.zip
```


## Installation des outils

```
# cd mon_premier_ctf
# ./ctf_install
```

Si Docker n'était pas installé ou si l'utilisateur n'était pas dans le groupe Docker, il faut <red>REBOOTER</red> le serveur.

Note: on peut ne pas rebooter en utilisant 'newgrp docker', mais c'est plus propre de rebooter...

Si on a rebooté, on relance l'installation
```
# cd mon_premier_ctf
# ./ctf_install
```

Le script vérifie que l'installation des outils est correcte et passe à l'installation du CTF.

## Configurer & Lancer

On retrouve le même état que la VM...

[Suite des manips](install_vm.md#Personnaliser-un-peu-la-config)



