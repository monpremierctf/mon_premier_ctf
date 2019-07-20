FROM php:7-fpm

RUN apt-get update && apt-get install -y python && apt-get install sudo

RUN useradd -d /home/yolo -s /bin/bash yolo --uid 3022 
RUN echo "www-data ALL = (yolo) NOPASSWD: /usr/bin/python" >>/etc/sudoers
USER www-data