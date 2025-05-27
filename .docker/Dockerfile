FROM composer:2.8.9 AS composer
FROM php:8.4-cli-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache postgresql-dev && \
    docker-php-ext-install pdo_pgsql

WORKDIR /usr/src/app/

ENTRYPOINT ["sleep", "infinity"]
