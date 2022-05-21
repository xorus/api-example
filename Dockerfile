FROM php:8.1-cli-buster

RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pdo_pgsql

WORKDIR /app
CMD php -S 0.0.0.0:8840 --docroot public

EXPOSE 8840
