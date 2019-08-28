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



# Créer une catégorie de challenges

Créer un nouveau répertoire : ctf-xxxxx
mkdir ctf-test

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
    [XXX] Label du challenge, doit être unique, sans contrainte particulière
    name: Home Sweet Home : Nom du challenge tel qu'il apparait sur les pages HTLM
    value: 10 : Nombre de points marqués pour la résolution du challenge
    category: Ghost in the Shell : Catégorie regroupant plusieurs challenges
    flag: flag_{m0n_pr3m13r_fl4g} : format libre
    file: (optionnel) : le nom d'un fichier qui sera téléchargeable par les participants.
    description: 

    La description peut tenir sur une ou plusieurs lignes.
    [espace !!] Les lignes de la description doivent commencer par un ESPACE ou une TABULATION
    Dès qu'une ligne recommence à la première colonne, on a terminé la description
    Cette description est collée dans du HTML après un passage dans un léger parser Markdown
    Utiliser la balise </br> pour chaque retour à la ligne
    Mettre les morçeaux de code ou commande entre deux lignes de &#96;&#96;&#96;
    &#96;&#96;&#96;
    $ commande shell, ou bout de code
    &#96;&#96;&#96;
```


# Créer un challenge texte 


Dans le fichier challenge.cfg ajouter la description du challenge

```
[XXX]  Label du challenge, doit être unique, sans contrainte particulière
name:  Home Sweet Home : Nom du challenge tel qu'il apparait 
value: 10 : Nombre de points marqués pour la résolution du challenge
category:  La catégorie qui permet de regrouper des challenges
flag:  martinique : format libre
flag2:  Martinique : (optionnel) : un second flag pour le challenge
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





# Créer un challenge avec une VM







