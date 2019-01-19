FROM php:7.2-fpm

RUN apt-get update \
  && docker-php-ext-install pdo_mysql mysqli

RUN apt-get update \
  && apt-get install -y libmemcached-dev zlib1g-dev \
  && pecl install memcached-3.0.3 \
  && docker-php-ext-enable memcached opcache