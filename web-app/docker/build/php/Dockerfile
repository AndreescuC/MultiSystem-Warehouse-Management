# docker/build/php/Dockerfile

FROM php:7.2-apache

COPY --chown=www-data . /var/www/html/

RUN apt-get update && apt-get install -y vim