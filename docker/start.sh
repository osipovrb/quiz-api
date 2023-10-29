#!/usr/bin/env bash
 
set -e
 
role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}
 
if [ "$role" = "migrate" ]; then
    echo "Running migrate..."
    php /var/www/artisan migrate
    exit 0
elif [ "$role" = "websockets" ]; then
    echo "Running websockets..."
    php /var/www/artisan websockets:serve
elif [ "$role" = "queue" ]; then
    echo "Running queue..."
    php /var/www/artisan queue:work --verbose --tries=3 --timeout=90
elif [ "$role" = "scheduler" ]; then
    echo "Running scheduler..."
    while [ true ]
    do
      php /var/www/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done
else
    echo "Could not match the container role \"$role\""
    exit 1
fi
