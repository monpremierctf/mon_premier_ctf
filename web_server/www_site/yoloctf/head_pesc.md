## Escalade de privilège


</br>
Vous êtes connecté à un serveur en tant qu'utilisateur sans privilège particulier. 

Vous allez utiliser quelques méthodes classique pour changer de compte et essayer de devenir administrateur.

Vous allez chercher les fichiers qui disposent d'un Sticky bit et permettent d'executer des commandes shell.


find / -perm -4000 -print 2>/dev/null

Des programmes comme nmap, vim, find, more, less, nano, awk... ou des interpreteurs comme sh, bash, python, perl, ruby permettent d'il disposent du sticky bit d'obtenir un shell de leur propriétaire.







