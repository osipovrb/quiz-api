FROM php:8.2-fpm-alpine AS quiz_api_backend

WORKDIR /var/www

RUN apk update && apk add \
    build-base \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng \
    libpng-dev \
    libwebp-dev \
    libwebp \
    libzip-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    oniguruma-dev \
    libcurl \
    curl-dev \
    curl \
    postgresql-dev \
    bash

RUN docker-php-ext-install pdo_pgsql mbstring zip exif pcntl intl curl

RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-install gd
RUN sh -c "curl --silent --show-error https://getcomposer.org/installer | php"

RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

COPY --chown=www:www . /var/www
COPY ./docker/start.sh /usr/local/bin/start
RUN ["chmod", "+x", "/usr/local/bin/start"]

USER www

EXPOSE 9000

FROM quiz_api_backend AS quiz_api_roles

CMD ["/usr/local/bin/start"]
