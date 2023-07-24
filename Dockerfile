FROM php:8.1

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --chown=www-data:www-data . .

RUN composer install
RUN composer dumpautoload

RUN ./vendor/bin/phpunit

CMD ["php", "index.php"]
