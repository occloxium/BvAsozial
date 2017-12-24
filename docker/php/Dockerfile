FROM php:7.1-apache
COPY php.ini /usr/local/etc/php/
RUN apt-get update \
  && docker-php-ext-install pdo_mysql mysqli && docker-php-ext-enable pdo_mysql mysqli && apachectl restart