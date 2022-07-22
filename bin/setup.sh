#!/bin/bash

if [ ! -f composer.json ]; then
    echo "Please make sure you run this script from the root directory of this repo."
    exit 1
fi

composer install
cp .env.example .env
php artisan key:generate

# Create database if not exists...
if ! mysql -u root -e "use randallwilk"; then
    echo "Creating randallwilk database"
    mysql -u root -e "create database randallwilk"
fi

php artisan migrate

npm install
npm run build
