FROM php:8.2.9-fpm-alpine3.18

ARG TIME_ZONE
RUN apk add --no-cache --virtual .build-deps  \
        $PHPIZE_DEPS \
        libjpeg-turbo-dev \
        libxml2-dev \
        bzip2-dev \
        autoconf \
        g++ \
    && apk add --no-cache --virtual .php-deps \
        make \
    && apk add --update --no-cache \
        mysql-client \
        linux-headers \
        tzdata \
        icu-dev \
    && NPROC=$(getconf _NPROCESSORS_ONLN) \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j${NPROC} \
        intl \
        mysqli \
        pdo_mysql \
        bcmath \
        exif \
    && docker-php-ext-enable intl \
    && apk del -f .build-deps \
    && rm -rf /var/cache/apk/*

RUN ln -snf /usr/share/zoneinfo/${TIME_ZONE} /etc/localtime \
    && echo ${TIME_ZONE} > /etc/timezone