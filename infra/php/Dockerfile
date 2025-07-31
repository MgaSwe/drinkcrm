FROM php:8.3-fpm-alpine

# Systempakete & Build-Deps (linux-headers hilft beim Xdebug-Build auf Alpine)
RUN set -eux; \
    apk add --no-cache \
      git openssh-client curl jq \
      libpng-dev libzip-dev oniguruma-dev icu-dev postgresql-dev \
      linux-headers \
      $PHPIZE_DEPS \
 && docker-php-ext-install pdo pdo_pgsql intl mbstring gd zip bcmath \
 && pecl install redis xdebug \
 && docker-php-ext-enable redis xdebug \
 # optional: Build-Dependencies wieder entfernen, um das Image zu verkleinern
 && apk del --no-network $PHPIZE_DEPS linux-headers || true

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Xdebug-Konfiguration (das Enable erledigt Zeile oben)
RUN { \
  echo "xdebug.mode=develop,debug"; \
  echo "xdebug.start_with_request=yes"; \
  echo "xdebug.client_host=host.docker.internal"; \
  echo "xdebug.client_port=9003"; \
} > /usr/local/etc/php/conf.d/zz-xdebug.ini
