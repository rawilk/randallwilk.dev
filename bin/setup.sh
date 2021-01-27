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
    # Database does not exist, create it now...
    mysql -u root -e "create database randallwilk"
fi

php artisan migrate

# Pull in repo info and docs
php artisan import:github-repositories
php artisan import:github-issues
php artisan import:packagist-downloads
php artisan import:npm-downloads
source "${dirname "$0"}/checkout_latest_docs.sh"

npm install
npx mix
