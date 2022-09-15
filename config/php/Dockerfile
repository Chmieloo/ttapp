FROM php:8.1-fpm

RUN apt-get update \
  && docker-php-ext-install pdo_mysql mysqli

WORKDIR /var/www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN apt-get update \
  && apt-get install -y git