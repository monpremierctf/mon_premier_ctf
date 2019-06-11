FROM ctf-sshd:latest


## cat 
RUN useradd -m -d /home/luke -s /bin/bash  luke
RUN echo 'luke:tatooine' | chpasswd
COPY --chown=luke flag01.txt /home/luke/flag01.txt
COPY --chown=luke profile /home/luke/.profile
RUN chmod 400 /home/luke/flag01.txt

## la -al 
RUN useradd -m -d /home/yoda -s /bin/bash  yoda
RUN echo 'yoda:naboo' | chpasswd
COPY --chown=yoda flag02.txt /home/yoda/.flag02.txt
RUN chmod 400 /home/yoda/.flag02.txt

## cd /home/* 
RUN useradd -m -d /home/obiwan -s /bin/bash  obiwan
RUN echo 'obiwan:hoth' | chpasswd
RUN useradd -m -d /home/padme -s /bin/bash  padme
RUN echo 'padme:coruscant' | chpasswd
RUN chmod a+r /home/padme
COPY --chown=obiwan flag03.txt /home/padme/flag03.txt
RUN chmod 400 /home/padme/flag03.txt

## find / readable
RUN useradd -m -d /home/dooku -s /bin/bash  dooku
RUN echo 'dooku:dagobah' | chpasswd
RUN mkdir -p /var/tmp
RUN chmod 777 /var/tmp
COPY --chown=dooku flag04.txt /tmp/flag04.txt
RUN chmod 400 /tmp/flag04.txt
COPY --chown=dooku flag05.txt /var/tmp/flag05.txt
RUN chmod 400 /var/tmp/flag05.txt


## Find other users home in /etc/passwd
RUN useradd -m -d /home/jarjar -s /bin/bash  jarjar
RUN echo 'jarjar:shili' | chpasswd
RUN useradd -m -d /var/mace -s /bin/bash  mace
RUN echo 'mace:alderaan' | chpasswd
COPY --chown=jarjar flag06.txt /var/mace/flag06.txt
RUN chmod 400 /var/mace/flag06.txt


## strings
RUN apt-get update && apt-get install -y binutils
RUN useradd -m -d /home/quigong -s /bin/bash  quigong
RUN echo 'quigong:bespin' | chpasswd
COPY --chown=quigong welcome_07 /home/quigong/welcome_07
RUN chmod 500 /home/quigong/welcome_07

## su user
RUN useradd -m -d /home/grievous -s /bin/bash  grievous
RUN echo 'grievous:yavin' | chpasswd
COPY --chown=grievous readme08.txt /home/grievous/readme08.txt
RUN chmod 400 /home/grievous/readme08.txt
RUN useradd -m -d /home/leia -s /bin/bash  leia
RUN echo 'leia:kashyyyk' | chpasswd
COPY --chown=leia flag09.txt /home/leia/flag09.txt
RUN chmod 400 /home/leia/flag09.txt

## grep
RUN useradd -m -d /home/han -s /bin/bash  han
RUN echo 'han:ando' | chpasswd
COPY --chown=han liste10.txt /home/han/liste10.txt
RUN chmod 400 /home/han/liste10.txt

## unzip
RUN useradd -m -d /home/c3po -s /bin/bash  c3po
RUN echo 'c3po:corellia' | chpasswd
COPY --chown=c3po flag11.zip /home/c3po/flag11.zip
RUN chmod 400 /home/c3po/flag11.zip
RUN apt-get update && apt-get install -y unzip

## untar
RUN useradd -m -d /home/finn -s /bin/bash  finn
RUN echo 'finn:yavin' | chpasswd
COPY --chown=finn flag12.tar /home/finn/flag12.tar
RUN chmod 400 /home/finn/flag12.tar
RUN apt-get update && apt-get install -y vim

## Remove Message of the day
RUN chmod -x /etc/update-motd.d/*
RUN echo "" > /etc/motd