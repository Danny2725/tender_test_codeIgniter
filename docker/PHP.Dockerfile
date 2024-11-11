FROM php:8.2-fpm 
 
# Install dependencies and intl extension 
RUN apt-get update && apt-get install -y \ 
    libicu-dev \ 
    && docker-php-ext-configure intl \ 
    && docker-php-ext-install intl 
 
RUN docker-php-ext-install pdo pdo_mysql 
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli 
 
## gd 
# RUN apt-get update && apt-get install -y \ 
#         libfreetype6-dev \ 
#         libjpeg62-turbo-dev \ 
#         libpng-dev \ 
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \ 
#     && docker-php-ext-install -j$(nproc) gd 
 
# Clean up 
RUN apt-get clean && rm -rf /var/lib/apt/lists/* 
 
# Install the latest Xdebug version compatible with PHP 8.3 
# RUN pecl install xdebug && docker-php-ext-enable xdebug
