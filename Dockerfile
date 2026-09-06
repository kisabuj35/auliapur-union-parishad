# অফিসিয়াল প্রি-কনফিগার্ড ফুল PHP + Nginx এনভায়রনমেন্ট
FROM richarvey/nginx-php-fpm:latest

# মেমোরি লিমিট বাড়ানো
ENV PHP_MEM_LIMIT=512M
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

WORKDIR /var/www/html

# আমাদের প্রজেক্ট কপি করা
COPY . /var/www/html

# লারাভেলের প্রয়োজনীয় রুট ও ক্যাশ ডিরেক্টরি সেটআপ
RUN mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Nginx ওয়েব রুট সেট করা (লারাভেলের public ফোল্ডার)
ENV WEBROOT=/var/www/html/public

EXPOSE 80
