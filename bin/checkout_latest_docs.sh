#!/bin/bash

if [ ! -f composer.json ]; then
    echo "Please make sure you run this script from the root directory of this repo."
    exit 1
fi

echo 'Updating docs...'

php artisan import:docs --all
php artisan queue:work
