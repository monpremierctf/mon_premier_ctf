FROM tool-xterm:latest

USER root
RUN apt-get update && apt-get install -y netcat john nmap vim hydra zip

COPY sqlmap.zip /tmp
RUN unzip /tmp/sqlmap.zip -d /opt
RUN echo 'export SQLMAP_HOME=/opt/sqlmap'>> /home/yolo/.bashrc
RUN echo 'export PATH=$PATH:$SQLMAP_HOME'>> /home/yolo/.bashrc
RUN echo 'alias sqlmap=sqlmap.py'>> /home/yolo/.bashrc
USER yolo
