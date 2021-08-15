FROM webdevops/php-nginx:7.4-alpine

WORKDIR /var/www/app

COPY /src .

RUN chmod 777 -R public/wp-content/uploads
RUN chmod 777 -R public/wp-content/cache

COPY /docker/bootstrap.sh /opt/docker/provision/entrypoint.d/bootstrap.sh