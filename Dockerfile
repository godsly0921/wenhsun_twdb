FROM php:7.3-apache
RUN apt-get update
RUN docker-php-ext-install mbstring mysqli pdo_mysql
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip
RUN a2enmod rewrite
RUN echo 'output_buffering = 16384' >> /usr/local/etc/php/conf.d/docker-php-output_buffering.ini