## build context is at docker/
FROM php:7.3-cli
WORKDIR /var/www/html

# install dependencies
RUN set -ex; \
    apt-get update && \
    apt-get -y --no-install-recommends install \
    $PHPIZE_DEPS \
	bash \
    git \
    unzip

# use the mlocati/docker-php-extension-installer to install necessary packages and php extensions
# the installer cleans up all dev packages which were only used for compiling
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    apcu \
    bcmath \
    xdebug-^2.9 \
    zip

COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN set -ex; \
    find /usr/local/lib/php/extensions -name "no-debug-non-zts-*" | \
    xargs basename | \
    xargs -I '{}' sed -i 's/{folder_version}/{}/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# install composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

VOLUME /var/www/html
