## Shell


</br>
Vous venez de pénétrer sur un serveur en devinant le login/password d'un utilisateur. Que faites vous maintenant ?

Les administrateurs et les utilisateurs font souvent de petites entorses à la sécurité pour gagner du temps.
Droits en execution ou lecture de fichiers ouverts à tous.
Fichier temporaires qui trainent...

Nos outils de base pour partir à la chasse aux Flags cachés sur le système sont:
- ls -al pour trouver les fichiers en .xxx
- /home, /etc/passwd, /var/tmp, pour les fichiers temporaires
- find pour lister les fichiers en lectures et les sticky bits
- les sticky bits sur less, nmap, 
- ps pour identifier les process qui tournent et exploiter des races conditions
- strings pour extraire les chaines de caractère d'un binaire
- grep pour extraire les 'flags_{ de fichiers textes


Demain, laisserez trainer un zip dans /tmp  ?




#### Lancez votre serveur dédié


        