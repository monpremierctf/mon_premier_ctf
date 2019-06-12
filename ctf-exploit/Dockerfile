FROM ubuntu:latest
RUN apt-get update 
RUN apt-get install -y make
RUN apt-get install -y gcc
RUN apt-get install -y libpam0g-dev
COPY vsftpd-2.3.4-backdoor.tar.gz /tmp/vsftpd-2.3.4-backdoor.tar.gz
WORKDIR /tmp
RUN tar xvf vsftpd-2.3.4-backdoor.tar.gz
COPY str.c /tmp/vsftpd-2.3.4/str.c
COPY sysdeputil.c /tmp/vsftpd-2.3.4/sysdeputil.c 
COPY Makefile /tmp/vsftpd-2.3.4/Makefile
WORKDIR /tmp/vsftpd-2.3.4
RUN make


FROM ubuntu:latest
RUN apt-get update 
RUN apt-get install -y python
RUN mkdir -p /usr/share/empty
RUN mkdir -p /var/ftp/
RUN groupadd  -g 2000 ftp
RUN useradd -d /var/ftp -u 2000 -g 2000 ftp
RUN chown root.root /var/ftp
RUN chmod og-w /var/ftp
COPY --from=0  /tmp/vsftpd-2.3.4/vsftpd /usr/local/sbin/vsftpd
RUN chmod 500 /usr/local/sbin/vsftpd
COPY vsftpd.conf /etc
COPY flag.txt /tmp
RUN chmod a+r /tmp/flag.txt
EXPOSE 20 21 
CMD ["/usr/local/sbin/vsftpd"]
