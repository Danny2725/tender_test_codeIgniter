FROM php:8.2-fpm

# Cài đặt các extension và phụ thuộc cần thiết
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl zip \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-enable mysqli

# Dọn dẹp
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Sao chép composer.json và composer.lock
COPY composer.json composer.lock ./

# Chạy lệnh composer install (bao gồm dev dependencies)
RUN composer install --no-interaction --optimize-autoloader

# Sao chép phần còn lại của mã nguồn
COPY . ./

# Cấu hình quyền (nếu cần)
# RUN chown -R www-data:www-data /var/www/html

# Chạy PHP-FPM
CMD ["php-fpm"]

