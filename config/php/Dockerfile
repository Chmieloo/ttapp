FROM php:7.2-fpm

RUN apt-get update \
  && docker-php-ext-install pdo_mysql mysqli

RUN apt-get update \
  && apt-get install -y libmemcached-dev zlib1g-dev \
  && pecl install memcached-3.0.3 \
  && docker-php-ext-enable memcached opcache

WORKDIR /var/www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN apt-get update \
  && apt-get install -y git