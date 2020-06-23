FROM alpine

RUN apk update --no-cache

# fix work iconv library with alphine
RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN apk add --no-cache \
    php7 \
    php7-json \
    php7-phar \
    php7-openssl \
    php7-iconv \
    php7-fpm \
    php7-xml \
    php7-tokenizer \
    php7-ctype \
    php7-curl \
    php7-dom \
    curl \
    nginx \
    php7-session \
    supervisor

RUN mkdir -p /run/nginx

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

ADD supervisord.conf /etc/supervisord.conf
ADD webapp.conf /etc/nginx/conf.d/default.conf
ADD my-php-fpm.conf /etc/php7/my-php-fpm.conf

WORKDIR /var/www
EXPOSE 80
CMD [ "/usr/bin/supervisord", "-c", "/etc/supervisord.conf" ]