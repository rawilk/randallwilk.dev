#!/bin/bash

# This script is mainly for updating deps from dependabot PR's.

if [ ! -f composer.json ]; then
    echo "Please make sure to run this script from the root directory of this repo."
    exit 1
fi

composer install --no-interaction --prefer-dist --optimize-autoloader
npm install
npm run build

echo ""
echo "Dependency update complete."
