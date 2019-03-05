FROM ctf-sshd:latest

RUN apt-get update && apt-get install -y mailutils 
RUN useradd -m -d /home/luke -s /bin/bash luke
RUN echo 'luke:tatooine' | chpasswd
COPY main.cf /etc/postfix/main.cf 
COPY mailname /etc/mailname 
COPY --chown=luke flag.txt /home/luke/flag.txt
CMD service postfix restart && /bin/bash
EXPOSE 25
