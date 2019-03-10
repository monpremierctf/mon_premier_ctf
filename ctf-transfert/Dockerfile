FROM ctf-sshd:latest

## ssh file transfert & base64
RUN useradd -m -d /home/user1 -s /bin/bash  user1
RUN echo 'user1:password' | chpasswd
COPY --chown=user1 dechiffre_01 /home/user1/dechiffre_01
RUN chmod a+x /home/user1/dechiffre_01

## scp
RUN useradd -m -d /home/user2 -s /bin/bash  user2
RUN echo 'user2:password' | chpasswd
COPY --chown=user2 flag02_enc.bin /home/user2/flag02_enc.bin
RUN chmod 400 /home/user2/flag02_enc.bin


## wget
RUN apt-get update && apt-get install -y wget
RUN useradd -m -d /home/user3 -s /bin/bash  user3
RUN echo 'user3:password' | chpasswd
COPY --chown=user3 flag03_enc.bin /home/user3/flag03_enc.bin
RUN chmod 400 /home/user3/flag03_enc.bin

