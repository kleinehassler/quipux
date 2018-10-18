FROM php:5.6-apache

RUN apt-get update &&\
    apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
    rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug-2.5.5 && docker-php-ext-enable xdebug
RUN echo 'zend_extension="/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so"' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/php.ini


RUN echo 'register_globals = Off' >> /usr/local/etc/php/php.ini
RUN echo 'short_open_tag = On' >> /usr/local/etc/php/php.ini
RUN echo 'display_errors = Off' >> /usr/local/etc/php/php.ini
RUN echo 'upload_max_filesize = 2M' >> /usr/local/etc/php/php.ini