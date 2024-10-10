# Basis image met PHP FPM 8.3
FROM php:8.3-fpm

# Installeer essentiële tools en afhankelijkheden
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev make \
    build-essential autoconf pkg-config libssl-dev libsqlite3-dev gnupg2 \
    && apt-get clean

# Installeer Node.js en npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Installeer Xdebug voor debugging en PCOV voor code coverage
RUN pecl install xdebug pcov \
    && docker-php-ext-enable xdebug pcov

# Configureer Xdebug voor debugging
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Installeer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installeer PHP-extensies voor Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Maak een gebruiker en groep aan voor sail
RUN groupadd --force -g 1000 eskapp \
    && useradd -ms /bin/bash --no-user-group -g 1000 -u 1337 eskapp

# Stel de working directory in
WORKDIR /var/www/html

# Expose poorten voor PHP-FPM en Xdebug
EXPOSE 9000 9003

# Gebruik de standaard www-data gebruiker
USER eskapp

CMD ["php-fpm"]
