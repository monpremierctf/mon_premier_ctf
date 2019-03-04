# First CTF



## Start an empty CTFd

```bash
git clone https://github.com/CTFd/CTFd
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