FROM php:8.0-cli-alpine3.13

RUN apk add --no-cache git libxml2-dev openssh-client \
  && apk add --no-cache --virtual .build-deps autoconf g++ make \
  # xdebug
  && pecl install xdebug-3.0.1 \
  && docker-php-ext-install pdo_mysql mysqli \
  && docker-php-ext-enable xdebug pdo_mysql mysqli \
  # composer
  && curl --output composer-setup.php https://getcomposer.org/installer \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && rm composer-setup.php \
  # xdebug command
  && curl --location --output /usr/local/bin/xdebug https://github.com/julienfalque/xdebug/releases/download/v2.0.0/xdebug \
  && chmod +x /usr/local/bin/xdebug \
  # clean up
  && apk del .build-deps

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini