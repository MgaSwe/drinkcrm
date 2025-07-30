FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
      git libpng-dev libzip-dev oniguruma-dev icu-dev postgresql-dev \
      linux-headers \
      $PHPIZE_DEPS \
 && docker-php-ext-install pdo pdo_pgsql intl mbstring gd zip bcmath \
 && pecl install redis xdebug \
 && docker-php-ext-enable redis xdebug


RUN apk add --no-cache git openssh-client libpng-dev libzip-dev oniguruma-dev icu-dev postgresql-dev $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_pgsql intl mbstring gd zip bcmath \
    && pecl install redis xdebug \
    && docker-php-ext-enable redis xdebug


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

RUN { \
  echo "xdebug.mode=develop,debug"; \
  echo "xdebug.start_with_request=yes"; \
  echo "xdebug.client_host=host.docker.internal"; \
  echo "xdebug.client_port=9003"; \
} > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
