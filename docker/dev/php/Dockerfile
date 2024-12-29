FROM php:8.4-fpm

WORKDIR /app

ARG user
ARG uid

RUN apt-get update

RUN apt-get install -y zlib1g-dev
RUN apt-get install -y libpq-dev
RUN apt-get install -y libpng-dev
RUN apt-get install -y libzip-dev


RUN docker-php-ext-install pdo pdo_pgsql pgsql gd zip exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#RUN composer config --global use-parent-dir true

CMD ["php-fpm"]