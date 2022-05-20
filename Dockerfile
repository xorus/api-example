FROM php:8.1-cli-buster

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt-get update && apt-get install -y libpq-dev symfony-cli
RUN docker-php-ext-install pdo_pgsql

WORKDIR /app
CMD symfony server:start --port=8840

EXPOSE 8840
