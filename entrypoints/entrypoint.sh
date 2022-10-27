#!/bin/sh
export JOB_NAME=$1

cleanup ()
{
	echo "trapped!"
	kill -s SIGTERM $!
	exit 0
}

trap cleanup SIGINT SIGTERM

while [ 1 ]
do
    /var/www/entrypoints/${1}.sh &
    wait $!
done
