FROM php:7.1-apache
COPY php.ini /usr/local/etc/php/
ADD vhost.conf /etc/apache2/sites-available/000-default.conf
RUN apt-get update \
  && docker-php-ext-install pdo_mysql mysqli && docker-php-ext-enable pdo_mysql mysqli && a2enmod rewrite && apachectl restart