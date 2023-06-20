FROM php:8.1.0-apache

WORKDIR /var/www/html/ticketing-backend

#MOD rewrite
RUN a2enmod rewrite

RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    unzip zip \
    libfreetype6-dev

#Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install gettext intl pdo_mysql gd

RUN docker-php-ext-configure gd --enable-gd --with-freetype \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install gettext intl pdo_mysql gd

RUN composer self-update

COPY composer.json .

RUN set -ex ; \
    apt-get update ; \
    apt-get install -y git zip ; \
    composer -n validate --strict ; \
    composer -n install --no-scripts --ignore-platform-reqs

# RUN composer update

COPY . .

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
