#!/bin/bash

if [ ! -f composer.json ]; then
    echo "Please make sure to run this script from the root directory of this repo."
    exit 1
fi

composer install
php artisan migrate

npm install
npm run build

# Pull in repo info
php artisan import:github-repositories
php artisan import:packagist-downloads
php artisan import:npm-downloads

echo 'Update complete. For updated docs, start queue worker and then run: php artisan import:docs --all'
