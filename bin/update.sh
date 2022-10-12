#!/bin/bash

if [ ! -f composer.json ]; then
    echo "Please make sure to run this script from the root directory of this repo."
    exit 1
fi

composer install
php artisan migrate

npm install
npm run build

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

# Pull in repo info
php artisan import:github-repositories
php artisan import:packagist-downloads
php artisan import:npm-downloads

echo 'Update complete. For updated docs, start queue worker and then run: php artisan import:docs --all'
