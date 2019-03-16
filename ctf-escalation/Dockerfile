FROM ctf-sshd:latest

## find sticky bit : bash 
RUN useradd -m -d /home/theprofessor -s /bin/bash  theprofessor &&\
    echo 'theprofessor:darko' | chpasswd &&\
    useradd -m -d /home/tokio -s /bin/bash  tokio &&\
    echo 'tokio:esther' | chpasswd 
COPY --chown=tokio flag01.txt /home/tokio/flag01.txt
RUN chmod 400 /home/tokio/flag01.txt &&\
    cp /bin/bash /home/tokio/bash &&\
    chown tokio /home/tokio/bash &&\
    chmod a+x /home/tokio/bash &&\
    chmod +s /home/tokio/bash

## sticky bit :less
RUN apt-get update && apt-get install -y less
RUN useradd -m -d /home/moscow -s /bin/bash  moscow
RUN echo 'moscow:alvaro' | chpasswd
COPY --chown=moscow flag02.txt /home/moscow/flag02.txt
RUN chmod 400 /home/moscow/flag02.txt
RUN cp /usr/bin/less /home/moscow/less
RUN chown moscow /home/moscow/less
RUN chmod a+x /home/moscow/less
RUN chmod +s /home/moscow/less

## sticky bit :find
RUN useradd -m -d /home/rio -s /bin/bash  rio
RUN echo 'rio:paco' | chpasswd
COPY --chown=rio flag03.txt /home/rio/flag03.txt
RUN chmod 400 /home/rio/flag03.txt
RUN cp /usr/bin/find /home/rio/find
RUN chown rio /home/rio/find
RUN chmod a+x /home/rio/find
RUN chmod +s /home/rio/find

## sticky bit :awk
RUN useradd -m -d /home/berlin -s /bin/bash  berlin
RUN echo 'berlin:ursula' | chpasswd
COPY --chown=berlin flag04.txt /home/berlin/flag04.txt
RUN chmod 400 /home/berlin/flag04.txt
RUN cp /usr/bin/awk /home/berlin/awk
RUN chown berlin /home/berlin/awk
RUN chmod a+x /home/berlin/awk
RUN chmod +s /home/berlin/awk

## sticky bit :vim
RUN useradd -m -d /home/nairobi -s /bin/bash  nairobi
RUN echo 'nairobi:enrique' | chpasswd
COPY --chown=nairobi flag05.txt /home/nairobi/flag05.txt
RUN chmod 400 /home/nairobi/flag05.txt


## mysql running as root
RUN useradd -m -d /home/monica -s /bin/bash  monica
RUN echo 'monica:jaime' | chpasswd
COPY --chown=monica flag06.txt /home/monica/flag06.txt
RUN chmod 400 /home/monica/flag06.txt

