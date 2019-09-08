## C'est quoi un CTF ?


</br>
Un CTF est une compétition, basée sur des failles de sécurité réelles.
C'est l'idéal pour apprendre, dans une ambiance fun, avec les mains sur le clavier.

#### Pour qui ?

Les CTF s'adressent aux pro, mais aussi et surtout aux amateurs, étudiants, passionnés, curieux...
Cette plateforme est destinée à toute personne ayant un vernis de Linux (cd, ls,...).

#### Des challenges ?

Vous avez à résoudre des challenges de difficulté croissantes en temps limité.
Chaque challenge rapporte des points en fonction de sa difficulté, et le classement est en temps réel.

#### Jeopardy vs Attack-Defense ?

Il existe deux types de CTF, le 'Jeopardy', où tout le monde résout des challenges, et l' 'Attack-Défense' qui demande un niveau certain. Dans ce dernier mode, vous devez patcher/défendre votre serveur tout en allant prendre les flags chez vos concurrents. Ce CTF est un Jeopardy.



#### Des thématiques variées

Tout ce qui peut être mal configuré ou présenter une vulnérabilité exploitable est candidat pour un challenge.
- Chiffrement de données avec des algorithmes 'faibles'
- Reverse sur des fichier executables
- Analyse de code source 
- Web dans le navigateur
- Web sur le serveur
- Protocole réseau
- IoT: caméras, tv, voiture,...

Vous trouverez des challenges sur [root-me.org](https://www.root-me.org), et sur [hackthebox.eu](https://www.hackthebox.eu/).

Des VMs scénarisées contenant une suite de vulnérabilité à exploiter sont disponibles sur ces mêmes sites ainsi que sur [vulnhub.com](https://www.vulnhub.com/).

#### C'est quoi un flag ?

Un flag est un code délivré lors de la résolution d'un challenge et qui rapporte des points.
Chaque CTF a son format de flag.
Ce peut être:
- 373c51258167377b8a81168f11aea626
- $flag$373c51258167377b8a81168f11aba626
- Flag_{Un_message_marrant_ou_pas}
- ...

#### Une équipe pour quoi faire ?

Un CTF c'est sympa seul, mais c'est encore plus fun avec des potes.
Du coup, on s'authentifie souvent deux fois, comme participant puis comme équipe.
Il existe des classements mondiaux, et les grands CTFs rapportent des points pour ce classement.
La liste des CTFs à venir et les classements sont sur [ctftime.org](https://ctftime.org/).



#### C'est compliqué ? Je dois installer une kali ?

Pour s'amuser il faut un minimum de connaissances sur les vulnérabilités, et des outils...
L'ambition de cette plateforme est de vous faire gagner du temps, en vous présentant les cas types et quelques  outils simples.
Tout est pré-configuré, et à portée de main. Vous n'avez besoin que d'un navigateur web.

Nous avons fait un trade-off entre une plateforme robuste et sécurisée, incassable, mais lourde à utiliser et une plate-forme mutualisée débutant friendly sans authentification forte, facile à déployer pour un atelier de formation.
Quand vous aurez testé les VM publiques qui sont resetées toutes les 5 minutes, les shells dans lesquels vous ne pouvez pas écrire, et les VMs qui passent en état instable et refusent votre exploit pourtant bien écrit, ou les collègues farceurs qui changent les mots de passe vous comprendrez...

Vous avez pour chaque challenge :
- un environnement d'execution du challenge qui vous est réservé dynamiquement.
- un serveur kali-like perso pré-configuré avec les outils nécessaires.
Il suffit de cliquer dans le menu en bas à gauche pour ouvrir un shell sur votre serveur perso...



#### On ne casse pas le matériel SVP...

Normalement vous ne pouvez pas casser la plateforme en manipant.
Par contre, elle n'est pas difficile à mettre HS si c'est votre objectif.
Si vous trouvez (quand vous trouverez) une faille, prévenez-nous, et vous entrerez dans le Hall of Fame des bienfaiteurs :)

Lors d'un CTF il y a une règle de base :
- L'infra du CTF est hors scope des recherches de vulnerabilités. 
- Les machines des concurents sont hors scope.

Ceci dit, ce n'est pas forcément l'idée du siècle de venir à un CTF avec un PC contenant vos scan de passeport et la photo de votre zigounette sur le disque.


#### Mains sur le clavier

Vous êtes encore en train de lire ???
Il est temps de s'y mettre. Vous pouvez prendre les thèmes dans l'ordre qui vous plait dans le menu à gauche, mais le mieux est de les prendre dans l'ordre, en commançant par [Premier Flag], puis les rappels sur l'utilisation d'un shell linux.

Enjoy !




















