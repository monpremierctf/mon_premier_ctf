FROM ctf-sshd:latest

RUN apt-get update && apt-get install -y vsftpd
RUN useradd -m -d /home/luke -s /bin/bash luke
RUN echo 'luke:tatooine' | chpasswd
COPY --chown=luke flag.txt /home/luke/flag.txt
EXPOSE 20
CMD service vsftpd start 
