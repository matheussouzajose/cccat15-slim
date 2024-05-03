FROM alpine:3.18.4

ARG APP_NAME=api
ARG APP_TIMEZONE=America/Sao_Paulo
ENV APP_NAME=${APP_NAME}

WORKDIR /application

COPY . /application

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    tzdata \
    php82 \
    php82-common \
    php82-fpm \
    php82-opcache \
    php82-phar \
    php82-dom \
    php82-cli \
    php82-curl \
    php82-mongodb \
    php82-openssl \
    php82-posix \
    php82-mbstring \
    php82-tokenizer \
    php82-pdo \
    php82-pdo_mysql \
    php82-pcntl \
    php82-pecl-redis \
    php82-pecl-apcu \
    php82-xml \
    php82-xmlwriter \
    php82-fileinfo \
    php82-xdebug \
    php82-pecl-xdebug \
    php82-ctype \
    php82-simplexml --repository=http://dl-cdn.alpinelinux.org/alpine/edge/testing/ \
    && cp /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime \
    && echo "$APP_TIMEZONE" > /etc/timezone \
    && ln -fs /usr/bin/php82 /usr/bin/php \
    && rm -rf /var/cache/apk/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && composer self-update \
    && composer install --prefer-dist --ignore-platform-reqs --no-dev --no-progress --no-interaction --classmap-authoritative

COPY /.docker/php-fpm/ /etc/php82/
COPY /.docker/supervisor/ /etc/supervisor/
COPY ./.docker/nginx/ /etc/nginx/

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
