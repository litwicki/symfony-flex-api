FROM alpine:3.6
LABEL Maintainer="Jake Litwicki <jake.litwicki@gmail.com>" \
      Description="LEMP w/ Nginx 1.12 & PHP-FPM 7.1 based on Alpine."

# Install packages
RUN apk --no-cache add php7 php7-fpm php7-mysqli php7-json php7-openssl php7-curl \
    php7-zlib php7-xml php7-phar php7-intl php7-dom php7-xmlreader php7-ctype \
    php7-mbstring php7-gd nginx supervisor curl

# Configure nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY docker/fpm-pool.conf /etc/php7/php-fpm.d/tavro.conf
COPY docker/php.ini /etc/php7/conf.d/tavro.ini

# Configure supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

## Add application
#RUN mkdir -p /var/www/html
#WORKDIR /var/www/html
#COPY tavro /var/www/html/

EXPOSE 80 443
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]