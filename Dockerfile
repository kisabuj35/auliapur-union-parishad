FROM php:8.2-cli-alpine

# প্রয়োজনীয় ডাটাবেজ ও সিস্টেম টুলস
RUN apk add --no-cache mysql-client git zip unzip curl libpng-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd zip bcmath

# Composer ইনস্টল
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www

# প্রজেক্ট কপি
COPY . /var/www

# ক্যাশ ও স্টোরেজ তৈরি
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 10000

# বিল্ট-ইন পিএইচপি সার্ভার দিয়ে সরাসরি রেন্ডার পোর্টে চালানো (যা কখনো ক্র্যাশ করবে না)
CMD php -S 0.0.0.0:${PORT:-10000} -t public
