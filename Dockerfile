FROM php:8.1-fpm

# Install PHP extensions
RUN apt-get update && apt-get install -y \
        unzip libmcrypt-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install exif
RUN docker-php-ext-configure exif \
            --enable-exif

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev
RUN docker-php-ext-install zip

RUN apt-get -y update
RUN apt-get -y upgrade

RUN docker-php-ext-install pdo_mysql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# XDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Set working directory
WORKDIR /var/www

COPY . .

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache

# install supervisor
RUN apt-get install -y supervisor
COPY docker/supervisor.conf supervisor.conf
RUN echo "[supervisorctl]" >> supervisor.conf
RUN echo "serverurl=unix:///var/run/supervisor.sock" >> supervisor.conf
RUN cp supervisor.conf /etc/supervisord.conf

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

EXPOSE 5173
ENTRYPOINT ["/var/www/docker/run.sh"]
