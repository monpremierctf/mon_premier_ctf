# First CTF

## Startinf CTFd

```bash
sudo docker-compose -d up
```


```bash
sudo docker-compose down
sudo docker rmi xxx
```

```bash
sudo docker build -t ctf-sshd ./ctf-sshd
sudo docker build -t ctf-shell ./ctf-shell
sudo docker run -d -P -p 22:22 --name ctf-shell ctf-shell
```