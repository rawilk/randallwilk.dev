#!/bin/bash

# Note: All referenced variables must be defined/exported from the
# Forge site deployment script.

# Note: Data root should already be created from our GitHub action
# runner.

# Delete script once script exits
trap 'rm -- "$0"' EXIT

# Extract the domain name (everything after last /)
TARGET=${FORGE_SITE_PATH##*/}

# Extract the root path (everything before last /)
ROOT=${FORGE_SITE_PATH%/*}

# Directory to store each release in.
RELEASE_ROOT="$TARGET-releases"

# Name the new release (with the current date/time)
RELEASE=$(date +"%Y%m%d%H%M%S")
NEW_RELEASE_ROOT="$RELEASE_ROOT/$RELEASE"

# Directory that has the master copies of files like .env
DATA_ROOT="$ROOT/$TARGET-data"

# Our artisan php file for the new release
ARTISAN="$FORGE_PHP $ROOT/$NEW_RELEASE_ROOT/artisan"

# stop script on error signal (-e) and undefined variables (-u)
set -eu

echo "Deploying site: $TARGET"
echo ""

# Ensure we're in our root directory.
cd "$ROOT"

# Create a releases directory if it doesn't exist.
if [ ! -d "$RELEASE_ROOT" ]; then
    echo "Creating releases directory..."
    echo ""

    mkdir "$RELEASE_ROOT"
fi

# Clone the repository into a new release
echo "Creating new release ($RELEASE)..."
echo "---------------------------------------"
echo ""

git clone -b "$FORGE_SITE_BRANCH" --depth 1 "$GIT_REPOSITORY" "$NEW_RELEASE_ROOT"

echo ""

# Install Composer Dependencies
$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev --working-dir "$NEW_RELEASE_ROOT"

# Restart php
( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

# Symlink master copies of persistent data to the new release.
echo "Symlinking site files..."
echo "------------------------"

ln -sfn "$DATA_ROOT/.env" "$NEW_RELEASE_ROOT/.env"
rm -rf "$NEW_RELEASE_ROOT/storage"
ln -sfn "$DATA_ROOT/storage" "$NEW_RELEASE_ROOT/storage"

# Symlink sitemaps
ln -sfn "$DATA_ROOT/public/sitemap.xml" "$NEW_RELEASE_ROOT/public/sitemap.xml"
ln -sfn "$DATA_ROOT/public/sitemap_docs.xml" "$NEW_RELEASE_ROOT/public/sitemap_docs.xml"
ln -sfn "$DATA_ROOT/public/sitemap_pages.xml" "$NEW_RELEASE_ROOT/public/sitemap_pages.xml"

# Symlink Doc Files
ln -sfn "$DATA_ROOT/public/doc-files" "$NEW_RELEASE_ROOT/public/doc-files"

# Install Node Dependencies
npm install --no-audit --prefix "$NEW_RELEASE_ROOT"

# Run Migrations
$ARTISAN migrate --force

# Optimize Site
$ARTISAN optimize
$ARTISAN filament:optimize
$ARTISAN storage:link

# Build front-end assets
echo "Compiling front-end assets..."
echo "-----------------------------"
echo ""

npm run build --prefix "$NEW_RELEASE_ROOT"

# Cleanup node_modules
echo "Cleaning up node_modules..."
echo "---------------------------"
echo ""

rm -rf "$NEW_RELEASE_ROOT/node_modules"
