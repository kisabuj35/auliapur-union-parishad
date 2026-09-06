FROM php:8.2-fpm-alpine

# প্রয়োজনীয় এক্সটেনশন
RUN apk add --no-cache nginx git zip unzip curl libpng-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd zip bcmath

# Composer ইনস্টল
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# রুট পারমিশনে কম্পোজার চালানোর অনুমতি
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www

# সম্পূর্ণ প্রজেক্ট কপি
COPY . /var/www

# লারাভেলের ক্যাশ ফোল্ডার তৈরি
RUN mkdir -p /var/www/storage/framework/cache \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/bootstrap/cache

# Nginx কনফিগারেশন
RUN printf 'server {\n\
    listen 80;\n\
    server_name _;\n\
    root /var/www/public;\n\
    index index.php index.html;\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    location ~ \.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
}\n' > /etc/nginx/http.d/default.conf

# কম্পোজার ইনস্টল (মেমোরি ও স্ক্রিপ্ট ইগনোর সহ)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts || true

# পারমিশন ফিক্স
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

CMD php-fpm -D && nginx -g "daemon off;"
