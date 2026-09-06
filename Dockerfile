# PHP 8.2 ও FPM বেস ইমেজ
FROM php:8.2-fpm-alpine

# প্রয়োজনীয় এক্সটেনশন ইনস্টল
RUN apk add --no-cache nginx mysql-client git zip unzip curl libpng-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd zip bcmath

# Composer ইনস্টল
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# প্রজেক্ট ফাইল কপি
COPY . /var/www

# পারমিশন ফিক্স
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

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

# Composer ডিপেন্ডেন্সি ইনস্টল
RUN composer install --no-dev --optimize-autoloader || true

# পোর্ট ওপেন
EXPOSE 80

# Nginx এবং PHP-FPM একসাথে চালু করার স্ক্রিপ্ট
CMD php-fpm -D && nginx -g "daemon off;"
