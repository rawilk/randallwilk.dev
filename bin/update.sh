#!/bin/bash

if [ ! -f composer.json ]; then
    echo "Please make sure to run this script from the root directory of this repo."
    exit 1
fi

composer install
php artisan migrate

# Pull in repo info and docs
php artisan import:github-repositories
php artisan import:github-issues
php artisan import:packagist-downloads
php artisan import:npm-downloads
source "${dirname "$0"}/checkout_latest_docs.sh"

npm install
npx mix
