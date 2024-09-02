FROM php:8.3-apache

RUN a2enmod rewrite

RUN apt-get update \
  && apt-get install -y \
    libzip-dev \
    git \
    wget \
    sqlite3 \
    build-essential \
    libxml2-dev \
    autoconf \
    pkg-config \
    libsqlite3-dev \
    zlib1g-dev \
    libtool \
    --no-install-recommends

RUN docker-php-source extract
RUN docker-php-ext-install pdo
RUN pecl install zip \
    && docker-php-ext-enable zip

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN wget https://getcomposer.org/download/latest-stable/composer.phar \
    && mv composer.phar /usr/bin/composer \
    && chmod +x /usr/bin/composer

COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf
COPY docker/entrypoint.sh /entrypoint.sh
COPY . /var/www
COPY docker/.env.local /var/www/.env.local

WORKDIR /var/www

RUN composer install --optimize-autoloader \
    && php bin/console doctrine:database:create \
    && php bin/console doctrine:schema:update --force \
    && chown -R www-data:www-data var var


RUN mkdir -p public/uploads/1 \
    && mkdir -p public/uploads/2 \
    && mkdir -p public/uploads/3 \
    && mkdir -p public/uploads/4 \
    && mkdir -p public/uploads/5 \
    && chown -R www-data:www-data public/uploads

RUN chmod +x /entrypoint.sh

CMD ["apache2-foreground"]

ENTRYPOINT ["/entrypoint.sh"]
