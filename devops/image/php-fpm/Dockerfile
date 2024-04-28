FROM php:8.2-fpm-alpine

RUN apk update
RUN apk add shadow --no-cache \
    icu-dev \
    libzip libzip-dev \
    git \
    openssh \
    unzip

RUN apk add --no-cache autoconf linux-headers build-base \
    && docker-php-ext-install intl zip \
    && pecl install apcu && docker-php-ext-enable apcu \
    && apk --purge del autoconf linux-headers build-base

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ARG UNAME=developer
ARG UID=1000
ARG GID=1000
RUN groupadd -g $GID -o $UNAME
RUN useradd -m -u $UID -g $GID -o -s /bin/ash $UNAME
USER $UNAME

WORKDIR /var/www/service
