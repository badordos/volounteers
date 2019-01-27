#!/bin/sh

source /var/www/.env

/usr/bin/caddy -conf /etc/caddy/Caddyfile_${APP_ENV} -agree

while inotifywait -e modify /etc/caddy; do
	pkill caddy
done

