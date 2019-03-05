FROM ctf-sshd:latest

RUN apt-get update && apt-get install -y xinetd telnetd
RUN useradd -m -d /home/luke -s /bin/bash luke
RUN echo 'luke:tatooine' | chpasswd
COPY inetd.conf /etc/inetd.conf 
COPY xinetd.conf /etc/xinetd.conf 
COPY telnet /etc/xinetd.d/telnet
COPY --chown=luke flag.txt /home/luke/flag.txt

EXPOSE 23
CMD /etc/init.d/xinetd start
