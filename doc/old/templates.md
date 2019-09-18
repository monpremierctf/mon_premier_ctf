Templates pour générer les fichiers de config ctfd


db/challenges.json
```
{"count": 22, "results": [
    
    
    {
        "id": 1, 
        "name": "LolCatz", 
        "description": "Hello, jeune Padawan !\r\n\r\nLe chemin pour devenir un Jedi est long. Nous allons faire tes premiers pas ensemble...\r\n\r\n```bash\r\n$ ssh luke@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'luke' et le mot de passe 'tatooine'.\r\n\r\n```bash\r\n$ pwd\r\n```\r\nDans quel r\u00e9pertoire es tu connect\u00e9 ?\r\n\r\n\r\n```bash\r\n$ ls\r\n```\r\nQuel sont les fichiers de ce r\u00e9pertoire ?\r\n\r\n\r\n```\r\n$ cat flag1.txt\r\n```\r\nQue contient le fichier flag1.txt ?\r\n\r\nCopie ce Flag de la forme flagxxx{yyy} pour valider ce challenge !", 
        "max_attempts": 0, 
        "value": 1, 
        "category": "Ghost in the Shell", 
        "type": "standard", 
        "state": "visible", 
        "requirements": "null"
    }
], "meta": {}}
```

db/files.json
{"count": 10, "results": [
    {
        "id": 1, 
        "type": "challenge", 
        "location": "70f6212aa60bee3f995159db3c1bdcd4/flag01_enc.bin", 
        "challenge_id": 15, 
        "page_id": null}, 

db/flags.json
{"count": 23, "results": [
    {
        "id": 1, 
        "challenge_id": 1, 
        "type": "static", 
        "content": "flag001{F1rst_FLAG5_L1v3_F0R3v3R}", 
        "data": ""}, 


db/users.json
{"count": 1, "results": [
    {
        "id": 1, 
        "oauth_id": null, 
        "name": "Admin", 
        "password": "$bcrypt-sha256$2b,12$/80Xu..MMjL4JvCS2YhU7O$4bVxbT1oRRJpT9mQYr0bwV7lGmnUF96", 
        "email": "admin@locahost", 
        "type": "admin", 
        "secret": null, 
        "website": null, 
        "affiliation": null, 
        "country": null, 
        "bracket": null, 
        "hidden": 1, 
        "banned": 0, 
        "verified": 0, 
        "team_id": null, 
        "created": "2019-03-02T17:01:21"
    }
], "meta": {}}