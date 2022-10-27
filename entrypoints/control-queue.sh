#!/bin/sh
while [ 1 ]
do
    /usr/bin/supervisord -n -c /var/www/entrypoints/supervisord-control-queue.conf
done
