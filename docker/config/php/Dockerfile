FROM php:8.1-fpm

ARG USERNAME=${USER}
ARG USER_UID=${UID}
ARG USER_GID=${GID}

USER root

RUN addgroup $USERNAME --gid $USER_GID

RUN useradd -rm -d /home/$USERNAME -s /bin/bash -g $USERNAME -G sudo -u $USER_UID $USERNAME

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    g++ \
    gifsicle \
    git \
    jpegoptim \
    libfontconfig1 \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
    libxrender1 \
    libzip-dev \
    locales \
    optipng \
    pngquant \
    unzip \
    vim \
    zip \
    zlib1g-dev

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# configure php extensions
RUN docker-php-ext-configure intl &&  \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg

# install php extensions
RUN docker-php-ext-install \
    exif \
    gd \
    intl \
    pcntl \
    pdo \
    pgsql \
    pdo_pgsql \
    zip

# Install XDebug
RUN pecl install xdebug-3.2.0  && docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug-dev.ini

COPY memory-limit-php.ini /usr/local/etc/php/conf.d/memory-limit-php.ini

USER $USERNAME

WORKDIR /var/www

EXPOSE 9000
EXPOSE 9003

CMD ["php-fpm"]
