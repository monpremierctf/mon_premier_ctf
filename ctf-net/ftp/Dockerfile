FROM ubuntu:16.04

RUN apt-get update && apt-get install -y vsftpd
COPY vsftpd /etc/init.d/vsftpd
COPY vsftpd.conf /etc/vsftpd.conf
RUN chmod 755 /etc/init.d/vsftpd
RUN useradd -m -d /home/spock -s /bin/bash spock
RUN echo 'spock:enterprise' | chpasswd
COPY flag.txt /tmp/flag.txt
RUN chmod a+r /tmp/flag.txt
EXPOSE 20
EXPOSE 21
EXPOSE 64000-64321 
CMD ["sleep", "360"]