FROM alpine:3.6
LABEL Maintainer="Jake Litwicki <jake.litwicki@gmail.com>" \
      Description="LEMP w/ Nginx 1.12 & PHP-FPM 7.1 based on Alpine."

RUN apk --no-cache add --update -y \
    php7-fpm \
    php7-common \
    php7-pear \
    php7-dev \
    php7-xmlrpc \
    php7-session \
    php7-gettext \
    php7-json \
    php7-xdebug \
    php7-bcmath \
    php7-simplexml \
    php7-dom \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-xmlreader \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    nginx \
    supervisor \
    make \
    curl \
    openssl

# Configure nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY docker/fpm-pool.conf /etc/php7/php-fpm.d/tavro.conf
COPY docker/php.ini /etc/php7/conf.d/tavro.ini

# Configure supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80 443

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]