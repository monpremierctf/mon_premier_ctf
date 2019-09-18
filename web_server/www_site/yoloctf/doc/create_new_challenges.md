# Choisir les catégories de challenges à inclure dans le ctf

Le fichier 'challenges_list.cfg' liste les répertoires qui seront analysés lors de la génération des pages du site web.
```
[IPSERVER]
ctf-flag
ctf-xterm
ctf-shell
ctf-passwd
#ctf-escalation
#ctf-net
ctf-sqli
ctf-buffer
ctf-decode
ctf-transfert
ctf-exploit
ctf-python
```
Vous pouvez ajouter des répertoires, ou les commenter avec un #



# Ajouter une catégorie de challenges

Les catégories de challenges correspondent aux entrées dans le menu de gauche du CTF.

Créer un nouveau répertoire : ctf-xxxxx
````
mkdir ctf-test
````

Dans ce répertoire créer un fichier texte : challenges.cfg
Ce fichier doit contenir [Intro] qui est la partie de présentation en haut de page.
La description est rédigée en Markdown et doit commencer par deux espace.

```
[Intro]
category: Test   (Le nom de la catégorie qui sera reprise dans chaque description de challenge)
label: Le titre de la page de test dans la table des matières 
description:    
    ## Titre de la page
    .
    . Une ligne avec un point seul, est une ligne vide.
    La description peut tenir sur une ou plusieurs lignes.
    [espace !!] Les lignes de la description doivent commencer par un ESPACE ou une TABULATION
    Dès qu'une ligne recommence à la première colonne, on a terminé la description
    Cette description est collée dans du HTML après un passage dans un léger parser Markdown
    Mettre les morçeaux de code ou commande entre deux lignes de &#96;&#96;&#96;
    &#96;&#96;&#96;
    $ commande shell, ou bout de code
    &#96;&#96;&#96;
```


# Ajouter un challenge texte 


Dans le fichier challenge.cfg ajouter la description du challenge.

```
[XXX]  Label du challenge, doit être unique, sans contrainte particulière
name:  Nom du challenge tel qu'il apparait 
value: Nombre de points marqués pour la résolution du challenge
category:  La catégorie qui permet de regrouper des challenges
flag:  format libre
flag2: (optionnel) : un second flag pour le challenge
file: (optionnel) : le nom d'un fichier qui sera téléchargeable par les participants.
description: 
    La description peut tenir sur une ou plusieurs lignes.
    [espace !!] Les lignes de la description doivent commencer par un ESPACE ou une TABULATION
    Dès qu'une ligne recommence à la première colonne, on a terminé la description
    Cette description est collée dans du HTML après un passage dans un léger parser Markdown
    Mettre les morçeaux de code ou commande entre deux lignes de &#96;&#96;&#96;
    &#96;&#96;&#96;
    $ commande shell, ou bout de code
    &#96;&#96;&#96;
```

ex:
````
[Challenge_1]
name:  Une affaire de famille
value: 1
category:  Password
flag:  martinique
description: 
    Jean Bon vient de monter sa startup spécialisée dans la vente de sabres lasers.
    Il a passé la soirée à consolider ses commandes, en pensant à ses dernières vacances en Martinique.
    Avant de rentrer, regarder le dernier épisode de Game of Throne, il sauvegarde ses documents dans un zip chiffré sur une clef usb.
    Quand winzip lui demande un mot de passe, sans contrainte particulière, il a un souvenir d'une vague formation en sécurité, et il entre rapidement un mot de passe de plus de 8 lettres facile à retrouver.
    .
    Le flag est le mot de passe qu'il va utiliser. 
````
    




# Créer un challenge avec un serveur dans un docker


Placer un fichier 'docker-compose.yml' dans le répertoire. Celui ci sera build automatiquement lors du './go_first_install_xxx' avec une commande 'docker-compose build'.

Si ce docker est valable pour tous les challenges, le déclarer dans la partie [intro], 
````
[Intro]
category: Privilege Escalation
label: Privilege Escalation
docker: ctf-escalation
````

Si ce docker n'est utilisé que par un challenge, le déclarer dans le challenge:
````
[Challenge_nc]
name:  Netcat
value: 20
category:  Network protocol
flag:   flag_m01_c4_va
docker: ctf-tcpserver
````

Le docker sera automatiquement créé sur le réseau personnel du participant.


Déclarer ce docker dans le fichier de config 'tools/challenge-box-provider/challenge-box-provider.cfg'.
````
{ "id":"1",  "image":"ctf-tool-xterm", "port": "3000", "traefikport": "3000",  "duration": "108000" }
{ "id":"ctf-shell",  "image":"ctf-shell", "port": "22", "traefikport": "",  "duration": "900" }
{ "id":"ctf-sqli",  "image":"ctf-sqli", "port": "80", "traefikport": "",  "duration": "900" }
{ "id":"ctf-escalation",  "image":"ctf-escalation", "port": "80", "traefikport": "",  "duration": "900" }
{ "id":"ctf-buffer",  "image":"ctf-buffer", "port": "22", "traefikport": "",  "duration": "90000" }
{ "id":"ctf-transfert",  "image":"ctf-transfert", "port": "22", "traefikport": "",  "duration": "900" }
{ "id":"ctf-exploit",  "image":"ctf-exploit", "port": "22", "traefikport": "",  "duration": "900" }
{ "id":"ctf-tcpserver",  "image":"ctf-tcpserver", "port": "22", "traefikport": "",  "duration": "900" }
{ "id":"ctf-telnet",  "image":"ctf-telnet", "port": "22", "traefikport": "",  "duration": "900" }
{ "id":"ctf-ftp",  "image":"ctf-ftp", "port": "22", "traefikport": "",  "duration": "900" }
{ "id":"ctf-smtp",  "image":"ctf-smtp", "port": "22", "traefikport": "",  "duration": "900" }
{ "id":"ctf-python",  "image":"ctf-python", "port": "8080", "traefikport": "8080",  "duration": "900" }
````
- "id": identificant du docker utilisé dans challenge.cfg
- "image": image docker crée par docker-compose build
- "port": port à mapper sur le serveur Host. Une fonction permet de récupérer le port mappé. Permet de tester depuis le serveur.
- "traefikport": port qui sera utilisé par Traefic pour un accès en HTTPS à partir d'internet
- "duration": durée de vie du serveur en secondes. Il sera ensuite détruit.
    - 60 : 1 min
    - 900 : 15 min
    - 108000 : 30 heures









