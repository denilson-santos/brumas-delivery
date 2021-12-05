FROM php:7.2-fpm

RUN apt-get update -y \
  && apt-get install -y \
  git \
  zip \
  nginx \
  python2 \
  && curl -sL https://deb.nodesource.com/setup_13.x | bash - \
  && apt-get install -y \
  nodejs \
  && docker-php-ext-install pdo_mysql \
  && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
  && useradd -s /bin/bash -m php

USER php

WORKDIR /var/www/brumas-delivery