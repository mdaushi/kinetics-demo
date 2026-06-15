FROM dunglas/frankenphp:php8.4-alpine

# Install Composer binary dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

RUN apk add --no-cache \
    git \
    unzip \
    curl \
    nodejs \
    npm

RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    gd \
    intl \
    zip \
    opcache \
    bcmath \
    exif

# Copy file composer
COPY composer.json composer.lock ./

# Copy package files untuk frontend dependencies
COPY package.json package-lock.json ./

# Jalankan composer install
# --no-dev untuk production, abaikan jika masih development
RUN composer install --no-dev --no-scripts --no-autoloader --ansi --no-interaction

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
# Copy seluruh file aplikasi
COPY . /app

# Selesaikan autoload agar artisan commands tersedia untuk build
RUN composer dump-autoload --optimize

# Install npm dependencies dan build frontend assets
RUN npm install
RUN npm ci && npm run build

# Set permission sebelum entrypoint jalan
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]