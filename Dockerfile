# PHP 8.2 ও FPM বেস ইমেজ
FROM php:8.2-fpm-alpine

# প্রয়োজনীয় সিস্টেম প্যাকেজ ও PHP এক্সটেনশন ইনস্টল
RUN apk add --no-cache nginx git zip unzip curl libpng-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd zip bcmath

# Composer ইনস্টল
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# প্রজেক্ট ফাইল কপি
COPY . /var/www

# লারাভেলের ক্যাশ ফোল্ডার তৈরি ও পারমিশন নিশ্চিত করা
RUN mkdir -p /var/www/storage/framework/cache \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Nginx কনফিগারেশন তৈরি
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

# Composer ডিপেন্ডেন্সি নিশ্চিতভাবে ইনস্টল করা
RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 80

# Nginx ও PHP-FPM চালুকরণ
CMD php-fpm -D && nginx -g "daemon off;"
