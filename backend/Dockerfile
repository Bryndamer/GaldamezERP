# ─── Stage 1: Compilar assets Vite ──────────────────────────────────────────
FROM node:20-alpine AS node_builder
WORKDIR /build
COPY package.json yarn.lock* ./
RUN yarn install
COPY vite.config.js ./
COPY resources/ resources/
RUN yarn build

# ─── Stage 2: PHP runtime ────────────────────────────────────────────────────
FROM php:8.2-fpm-alpine

# ─── Dependencias del sistema ────────────────────────────────────────────────
RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    curl \
    unzip \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev

# ─── Extensiones PHP ─────────────────────────────────────────────────────────
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
        pdo_mysql \
        gd \
        zip \
        bcmath \
        opcache \
        mbstring

# ─── Composer ────────────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ─── Directorio de trabajo ───────────────────────────────────────────────────
WORKDIR /var/www

# ─── Dependencias PHP (capa cacheada) ────────────────────────────────────────
COPY composer.json composer.lock ./
RUN composer update --no-dev --optimize-autoloader --no-scripts --no-interaction

# ─── Código de la aplicación ─────────────────────────────────────────────────
COPY . .

# ─── Assets compilados por Stage 1 ───────────────────────────────────────────
COPY --from=node_builder /build/public/build /var/www/public/build

# ─── Post-install de Composer ────────────────────────────────────────────────
RUN composer run-script post-autoload-dump --no-interaction 2>/dev/null || true

# ─── Permisos ────────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# ─── Configuración Nginx ─────────────────────────────────────────────────────
COPY docker/nginx.conf /etc/nginx/nginx.conf

# ─── Configuración Supervisor ────────────────────────────────────────────────
COPY docker/supervisord.conf /etc/supervisord.conf

# ─── Entrypoint ──────────────────────────────────────────────────────────────
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/entrypoint.sh"]
