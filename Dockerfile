FROM php:8.2-fpm

# Install system dependencies + intl + zip
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql gd zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . /app

# ✅ Run composer install AFTER intl + zip are installed
RUN composer install --optimize-autoloader --no-dev

# (اختياري لو عندك فيت او مكس)
# RUN npm install && npm run build

CMD ["php-fpm"]
