#!/bin/bash

if [ ! -f composer.json ]; then
    echo "Please make sure you run this script from the root directory of this repo."
    exit 1
fi

composer install
cp .env.example .env
php artisan key:generate

# Database name
DB_NAME="randallwilk"

# Create the local db if it does not exist
if ! psql -U postgres -lqt | cut -d \| -f 1 | grep -qw $DB_NAME; then
    echo "Creating '$DB_NAME' database"
    psql -U postgres -c "CREATE DATABASE $DB_NAME"
fi

php artisan migrate
php artisan db:seed
php artisan storage:link

npm install
npm run build

echo "Install complete. Be sure to fill out GitHub credentials so docs can be imported."
