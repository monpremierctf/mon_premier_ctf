FROM ubuntu:16.04
RUN apt-get update 
RUN apt-get install -y make
RUN apt-get install -y gcc gcc-multilib
RUN apt-get install -y clang
RUN apt-get install -y libpam0g-dev

COPY buffer_01.c /tmp/buffer_01.c
COPY flag_01.c /tmp/flag_01.c

COPY buffer_02.c /tmp/buffer_02.c
COPY flag_02.c /tmp/flag_02.c

COPY buffer_03.c /tmp/buffer_03.c
COPY flag_03.c /tmp/flag_03.c

COPY buffer_04.c /tmp/buffer_04.c
COPY flag_04.c /tmp/flag_04.c

COPY buffer_05.c /tmp/buffer_05.c
COPY flag_05.c /tmp/flag_05.c

COPY buffer_rop.c /tmp/buffer_rop.c
COPY flag_rop.c /tmp/flag_rop.c


WORKDIR /tmp
RUN gcc  -g -m32 -fno-stack-protector -z execstack buffer_01.c -c
RUN gcc  -g -m32 -fno-stack-protector -z execstack flag_01.c -c
RUN gcc  -g -m32 -fno-stack-protector -z execstack buffer_01.o flag_01.o -o say_hello

RUN gcc  -g -m32 -fno-stack-protector -z execstack buffer_02.c -c
RUN gcc  -g -m32 -fno-stack-protector -z execstack flag_02.c -c
RUN gcc  -g -m32 -fno-stack-protector -z execstack buffer_02.o flag_02.o -o say_hello2

RUN gcc  -g -m32 -fno-stack-protector -z execstack buffer_03.c -c
RUN gcc  -g -m32 -fno-stack-protector -z execstack flag_03.c -c
RUN gcc  -g -m32 -fno-stack-protector -z execstack buffer_03.o flag_03.o -o say_hello3

RUN gcc  -g -m32 -fno-stack-protector -z execstack buffer_04.c flag_04.c -o say_hello4

RUN gcc  -g -m32 -fno-stack-protector -z execstack buffer_05.c flag_05.c -o say_hello5

RUN clang  -o rop buffer_rop.c -m32 -fno-stack-protector  -Wl,-z,relro,-z,now,-z,noexecstack -static




FROM ctf-sshd:latest
RUN dpkg --add-architecture i386
RUN apt-get update 
RUN apt-get install -y python binutils gdb 
RUN apt-get install -y libc6-dbg libc6-dbg:i386 lib32stdc++6 
RUN apt-get install -y vim strace ltrace

# ROPgadget : https://github.com/JonathanSalwan/ROPgadget
# pwntools  : http://docs.pwntools.com/en/stable/install.html

RUN apt-get install -y python2.7 python-pip python-dev git libssl-dev libffi-dev build-essential
RUN pip install --upgrade pip
RUN pip install capstone 
RUN pip install ropgadget 
RUN pip install --upgrade pwntools

RUN useradd -d /home/grandmaster -s /bin/dash grandmaster

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

RUN useradd -d /home/fry -s /bin/bash fry
RUN echo 'fry:futur' | chpasswd 
COPY --from=0  --chown=root /tmp/say_hello4 /home/fry/say_hello4
COPY --from=0  --chown=grandmaster /tmp/buffer_04.c /home/fry/buffer_04.c
RUN chmod 555 /home/fry/say_hello4
RUN chmod u+s /home/fry/say_hello4
RUN chmod 444 /home/fry/buffer_04.c
COPY --chown=fry profile04 /home/fry/.profile
RUN chmod 644 /home/fry/.profile
COPY --chown=root flag04.txt /home/fry/flag04.txt
RUN chmod 700 /home/fry/flag04.txt
COPY --chown=fry pattern.py /home/fry/pattern.py

RUN useradd -d /home/zapp -s /bin/bash zapp
RUN echo 'zapp:kif' | chpasswd 
COPY --from=0  --chown=grandmaster /tmp/say_hello5 /home/zapp/say_hello5
COPY --from=0  --chown=grandmaster /tmp/buffer_05.c /home/zapp/buffer_05.c
RUN chmod 555 /home/zapp/say_hello5
RUN chmod 444 /home/zapp/buffer_05.c
COPY --chown=zapp pattern.py /home/zapp/pattern.py

COPY --from=0  --chown=grandmaster /tmp/rop /home/zapp/rop
COPY --from=0  --chown=grandmaster /tmp/buffer_rop.c /home/zapp/buffer_rop.c
RUN chmod 555 /home/zapp/rop
RUN chmod 444 /home/zapp/buffer_rop.c

EXPOSE 22 
