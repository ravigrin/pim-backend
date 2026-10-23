### main state php ###
FROM php:8.3-cli-alpine as backend

RUN --mount=type=bind,from=mlocati/php-extension-installer:2.1.68,source=/usr/bin/install-php-extensions,target=/usr/bin/install-php-extensions \
    install-php-extensions \
      pdo_sqlsrv sqlsrv redis ldap session opcache \
      zip xsl dom exif intl pcntl bcmath sockets gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /usr/bin/rr

ENV COMPOSER_ALLOW_SUPERUSER 1

### install supervisord
RUN apk add --update supervisor && rm -rf /tmp/* /var/cache/apk/*

### dev state php ###
FROM backend as php_dev

COPY docker/php8/php.dev.ini /usr/local/etc/php/conf.d/php.ini
CMD ["php", "-S", "0.0.0.0:80", "public/index.php"]

### stage state php ###
FROM backend as php_stage

COPY docker/php8/php.prod.ini /usr/local/etc/php/conf.d/php.ini

COPY . /usr/app

WORKDIR /usr/app

RUN rm .env && mv env/.env.stage .env

RUN set -eux; \
	composer install --no-dev --no-scripts --prefer-dist --no-progress --no-interaction; \
	composer dump-autoload --optimize; \
    php bin/console cache:clear; \
    php bin/console doctrine:database:create --if-not-exists; \
    php bin/console doctrine:migrations:migrate -n;

### setup supervisord
COPY docker/supervisord/stage.supervisord.conf /etc/supervisor/supervisord.conf

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]

### prod state php ###
FROM backend as php_prod

COPY docker/php8/php.prod.ini /usr/local/etc/php/conf.d/php.ini

### setup cron
COPY docker/cron/jobs /etc/cron.d/jobs
RUN chmod 0644 /etc/cron.d/jobs && crontab /etc/cron.d/jobs

### setup supervisord
COPY docker/supervisord/prod.supervisord.conf /etc/supervisor/supervisord.conf

### load source to container
COPY . /usr/app

WORKDIR /usr/app

RUN rm .env && mv env/.env.prod .env

RUN set -eux; \
	composer install --no-dev --no-scripts --prefer-dist --no-progress --no-interaction; \
	composer dump-autoload --optimize; \
    php bin/console cache:clear; \
    php bin/console doctrine:database:create --if-not-exists; \
    php bin/console doctrine:migrations:migrate -n;

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
