server {
listen 80;

    index index.php index.html;
    root /var/www/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}



version: '3.8'

services:
app:
build:
context: .
dockerfile: Dockerfile
container_name: laravel-app
restart: always
volumes:
- .:/var/www
depends_on:
- db

webserver:
image: nginx:alpine
container_name: nginx-server
restart: always
ports:
- "8000:80"
volumes:
- .:/var/www
- ./nginx/conf.d/:/etc/nginx/conf.d
depends_on:
- app

db:
image: mysql:8.0
container_name: mysql-db
restart: always
environment:
MYSQL_DATABASE: jobportal
MYSQL_ROOT_PASSWORD: root
ports:
- "3307:3306"

FROM php:8.3-fpm

# Install PHP extensions and required system libraries
RUN apt-get update \
&& apt-get install -y libpng-dev libjpeg-dev \
libfreetype6-dev libzip-dev zip unzip \
&& docker-php-ext-configure gd --with-freetype \
--with-jpeg \
&& docker-php-ext-install gd pdo pdo_mysql zip \
&& rm -rf /var/lib/apt/lists/*

ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy project
WORKDIR /var/www
COPY . .

# Install composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/storage \
/var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
