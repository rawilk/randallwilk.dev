#!/bin/bash

if [ ! -f composer.json ]; then
    echo "Please make sure you run this script from the root directory of this repo."
    exit 1
fi

composer install
cp .env.example .env
php artisan key:generate

read -p "Decrypt .env.local? [y/n] " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    read -p "Enter encryption key: " ENCRYPTION_KEY
    read -p "Overwrite .env file? [y/n] " -n 1 -r
    echo

    php artisan env:decrypt --key=$ENCRYPTION_KEY --env=local --force
    echo "Decrypted to .env.local"

    if [[ $REPLY =~ ^[Yy]$ ]]
    then
        php artisan env:decrypt --key=$ENCRYPTION_KEY --env=local --filename=.env --force
        echo "Overwrite decrypted environment to .env"
    fi
fi

# Create database if not exists...
if ! mysql -u root -e "use randallwilk"; then
    echo "Creating randallwilk database"
    mysql -u root -e "create database randallwilk"
fi

# Create test database if not exists...
if ! mysql -u root -e "use randallwilk_test"; then
    echo "Creating randallwilk_test database"
    mysql -u root -e "create database randallwilk_test"
fi

php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan ide-helper:meta
php artisan ide-helper:generate

npm install
npm run build
