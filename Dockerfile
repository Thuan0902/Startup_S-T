FROM node:18 AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --silent
COPY . .
RUN npm run build || true

FROM composer:2 AS composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

FROM php:8.1-fpm
WORKDIR /srv/app

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip git curl nginx && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy composer artifacts and project files
COPY --from=composer /app /srv/app
COPY . /srv/app

# Install composer dependencies (safe fallback)
RUN if [ -f composer.lock ]; then composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader; else composer install --no-interaction --optimize-autoloader; fi

# Copy built frontend assets from node stage (if present)
COPY --from=node-builder /app/public/build /srv/app/public/build

RUN chown -R www-data:www-data /srv/app/storage /srv/app/bootstrap/cache

# nginx config
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 8080

CMD ["/bin/sh", "-lc", "php artisan migrate --force || true; php-fpm -D; nginx -g 'daemon off;'"]
