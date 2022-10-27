FROM hub2.t1-group.ru/rnis/php:8.1

WORKDIR /var/www

ADD database /var/www/database

ADD composer.json /var/www/
ADD composer.lock /var/www/

ADD app /var/www/app
ADD bootstrap /var/www/bootstrap
ADD config /var/www/config
ADD storage /var/www/storage
ADD tests /var/www/tests
ADD resources /var/www/resources
ADD artisan /var/www/

ENV NUMPROCS_SERVICE 1
ENV NUMPROCS_QUEUE 1
ENV NUMPROCS_QUEUE_EXPORT 2

ADD entrypoints/entrypoint.sh /var/www/entrypoint.sh
ADD entrypoints /var/www/entrypoints/

RUN chmod -R +x /var/www/entrypoints/

RUN composer install --optimize-autoloader --no-dev --no-plugins --no-scripts && composer clear-cache

ENTRYPOINT ["/var/www/entrypoint.sh"]

