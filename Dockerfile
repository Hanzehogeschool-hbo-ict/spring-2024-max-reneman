# syntax=docker/dockerfile:1
FROM php:8.3-cli

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install -y git && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install mysqli

WORKDIR /app

COPY composer.* .

RUN composer install --no-scripts

COPY . .

RUN composer dump-autoload --optimize

CMD php -S 0.0.0.0:8000 -t public/

EXPOSE 8000

