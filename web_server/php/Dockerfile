FROM php:7-fpm

RUN docker-php-ext-install mysqli
COPY www.conf /usr/local/etc/php-fpm.d/www.conf