FROM ubuntu:16.04
RUN apt-get update 
RUN apt-get install -y make
RUN apt-get install -y gcc
RUN apt-get install -y libpam0g-dev

COPY buffer_01.c /tmp/buffer_01.c
COPY flag_01.c /tmp/flag_01.c

COPY buffer_02.c /tmp/buffer_02.c
COPY flag_02.c /tmp/flag_02.c

COPY buffer_03.c /tmp/buffer_03.c
COPY flag_03.c /tmp/flag_03.c

WORKDIR /tmp
RUN gcc  -fno-stack-protector  buffer_01.c -c
RUN gcc  flag_01.c -c
RUN gcc  buffer_01.o flag_01.o -o say_hello

RUN gcc  -fno-stack-protector  buffer_02.c -c
RUN gcc  flag_02.c -c
RUN gcc  buffer_02.o flag_02.o -o say_hello2

RUN gcc  -fno-stack-protector  buffer_03.c -c
RUN gcc  flag_03.c -c
RUN gcc  buffer_03.o flag_03.o -o say_hello3


FROM ctf-sshd:latest
RUN apt-get update 
RUN apt-get install -y python
RUN useradd grandmaster
RUN useradd -d /home/bender -s /bin/bash bender
RUN echo 'bender:leelu' | chpasswd 
COPY --from=0  --chown=grandmaster /tmp/say_hello /home/bender/say_hello
COPY --from=0  --chown=grandmaster /tmp/buffer_01.c /home/bender/buffer_01.c
RUN chmod 555 /home/bender/say_hello
RUN chmod 444 /home/bender/buffer_01.c

RUN useradd -d /home/leela -s /bin/bash leela
RUN echo 'leela:yivo' | chpasswd 
COPY --from=0  --chown=grandmaster /tmp/say_hello2 /home/leela/say_hello2
COPY --from=0  --chown=grandmaster /tmp/buffer_02.c /home/leela/buffer_02.c
RUN chmod 555 /home/leela/say_hello2
RUN chmod 444 /home/leela/buffer_02.c

RUN useradd -d /home/philip -s /bin/bash philip
RUN echo 'philip:elzar' | chpasswd 
COPY --from=0  --chown=grandmaster /tmp/say_hello3 /home/philip/say_hello3
COPY --from=0  --chown=grandmaster /tmp/buffer_03.c /home/philip/buffer_03.c
RUN chmod 555 /home/philip/say_hello3
RUN chmod 444 /home/philip/buffer_03.c

EXPOSE 22 
