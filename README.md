# Mon premier CTF



Si vous désirez organiser un Capture The flag à destination de grands débutants, ce repo est pour vous.
Vous trouverez ici, une série de challenges destinés à permettre aux participants de commencer à se constituer la trousse à outil minimale pour participer à un CTF.


## Start an empty CTFd

```bash
cd ctfd
docker-compose up -d
```

Browse localhost:8000 
Create admin account
import martine-ctf.zip  <== Once build :)


## Buils challenges VMs and martine-ctf.zip

```bash
sudo docker-compose down
sudo docker rmi xxx
```

```bash
sudo docker build -t ctf-sshd ./ctf-sshd
sudo docker build -t ctf-shell ./ctf-shell
sudo docker run -d -P -p 22:22 --name ctf-shell ctf-shell
```
