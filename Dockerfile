FROM webdevops/php-nginx:7.4-alpine

WORKDIR /var/www/app

COPY /src/composer.json composer.json
COPY /src/composer.lock composer.lock

RUN composer i  --ignore-platform-reqs

COPY /src .