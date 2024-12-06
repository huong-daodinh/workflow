# FROM php:8.3-fpm
FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Install Additional dependencies
# Install Additional dependencies
RUN apk update && apk add --no-cache \
  curl \
  bash \
  # nodejs \
  # npm \
  supervisor

# Install Yarn
# RUN npm install -g yarn

# Add and Enable PHP-PDO Extenstions for PHP connect Mysql
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

# This extension required for Laravel Horizon
# RUN docker-php-ext-install pcntl

# Remove Cache
RUN rm -rf /var/cache/apk/*

COPY .docker/supervisord.conf /etc/supervisord.conf
COPY .docker/supervisor.d /etc/supervisor.d

# Use my configuration for PHP-FPM ($PHP_INI_DIR variable already set by the default image)
COPY .docker/config/php.ini "$PHP_INI_DIR/php.ini"


# Add UID '1000' to www-data
# RUN usermod -u 1000 www-data

# Chang app directory permission
# RUN chown -R www-data:www-data .

# COPY . .

ENV ENABLE_CRONTAB 1
ENV ENABLE_HORIZON 0
ENV ENABLE_WORKER 1


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


ENTRYPOINT ["sh", "/var/www/html/.docker/docker-entrypoint.sh"]

CMD supervisord -n -c /etc/supervisord.conf

