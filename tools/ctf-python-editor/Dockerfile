FROM ctf-php-nginx

USER root
RUN apk --no-cache add python  sudo

COPY --chown=nobody www_site/ /var/www/html/

RUN adduser -h /home/yolo -s /bin/bash yolo --uid 3022 -D
RUN echo "nobody ALL = (yolo) NOPASSWD: /usr/bin/python" >>/etc/sudoers

USER nobody
