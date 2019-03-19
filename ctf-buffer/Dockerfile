FROM ubuntu:16.04
RUN apt-get update 
RUN apt-get install -y make
RUN apt-get install -y gcc
RUN apt-get install -y libpam0g-dev
COPY buffer_01.c /tmp/buffer_01.c
WORKDIR /tmp
RUN gcc buffer_01.c -o buffer_01


FROM ctf-sshd:latest
RUN apt-get update 
RUN apt-get install -y python
RUN useradd -d /home/user01 -s /bin/bash user01
RUN echo 'user01:yolo' | chpasswd 
COPY --from=0  --chown=user01 /tmp/buffer_01 /home/user01/buffer_01
RUN chmod 555 /home/user01/buffer_01
EXPOSE 22 
