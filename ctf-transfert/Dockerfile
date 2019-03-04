FROM ctf-sshd:latest

## ssh file transfert & base64
RUN useradd -m -d /home/user1 -s /bin/bash  user1
RUN echo 'user1:password' | chpasswd
COPY --chown=user1 dechiffre_01 /home/user1/dechiffre_01
RUN chmod a+x /home/user1/dechiffre_01

## scp
COPY --chown=user1 flag02_enc.bin /home/user1/flag02_enc.bin
RUN chmod 400 /home/user1/flag02_enc.bin


## wget
RUN apt-get update && apt-get install -y wget
COPY --chown=user1 flag03_enc.bin /home/user1/flag03_enc.bin
RUN chmod 400 /home/user1/flag03_enc.bin

