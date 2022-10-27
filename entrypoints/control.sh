#!/bin/sh
while true
do
   /usr/bin/supervisord -n -c /var/www/entrypoints/supervisord-control.conf
done
