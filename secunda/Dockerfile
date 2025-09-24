FROM php:8.2-fpm
RUN apt-get update && apt-get install -y libonig-dev libxml2-dev zip unzip git curl
RUN docker-php-ext-install pdo pdo_mysql mbstring
WORKDIR /var/www
COPY . /var/www
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install
EXPOSE 9000
CMD ["php-fpm"]
