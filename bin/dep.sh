#!/bin/bash

# Note: All referenced variables must be defined/exported from the
# Forge site deployment script.

# Note: Data root should already be created from our GitHub action
# runner.

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
echo "----------------------------------------"
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

# Mark deployment as success
echo "$RELEASE" >> "$RELEASE_ROOT/.success"

# Link to the new deployment
echo "Linking new release..."
echo "----------------------"
echo ""

if [ -d "$FORGE_SITE_PATH" ] && [ ! -L "$FORGE_SITE_PATH" ]; then
    echo "First time deployment detected - moving existing directory..."

    # Back the directory up, just in case
    mv "$FORGE_SITE_PATH" "$FORGE_SITE_PATH-backup-$(date +%Y%m%d%H%M%S)"
fi

# Create atomic symlink to new release
ln -sfn "$ROOT/$NEW_RELEASE_ROOT" "$FORGE_SITE_PATH-temp"
mv -Tf "$FORGE_SITE_PATH-temp" "$FORGE_SITE_PATH"

# Remove failed releases
echo "Removing failed releases..."
echo ""
cd "$RELEASE_ROOT"

if grep -qvf .success <(ls -1)
then
    grep -vf .success <(ls -1)
    grep -vf .success <(ls -1) | xargs rm -rf
else
    echo "No failed releases found."
fi

# Remove older releases
echo "Cleaning up old releases..."
echo ""

RELEASES_TO_KEEP=$((RELEASES_TO_KEEP-1))
LINES_STORED_RELEASES_TO_DELETE=$(find . -maxdepth 1 -mindepth 1 -type d ! -name "$RELEASE" -printf '%T@\t%f\n' | head -n -"$RELEASES_TO_KEEP" | wc -l)
if [ "$LINES_STORED_RELEASES_TO_DELETE" != 0 ]; then
    find . -maxdepth 1 -mindepth 1 -type d ! -name "$RELEASE" -printf '%T@\t%f\n' | sort -t $'\t' -g | head -n -"$RELEASES_TO_KEEP" | cut -d $'\t' -f 2-
    find . -maxdepth 1 -mindepth 1 -type d ! -name "$RELEASE" -printf '%T@\t%f\n' | sort -t $'\t' -g | head -n -"$RELEASES_TO_KEEP" | cut -d $'\t' -f 2- | xargs -I {} sed -i -e '/{}/d' .success
    find . -maxdepth 1 -mindepth 1 -type d ! -name "$RELEASE" -printf '%T@\t%f\n' | sort -t $'\t' -g | head -n -"$RELEASES_TO_KEEP" | cut -d $'\t' -f 2- | xargs rm -rf
else
    echo "No old releases were found to delete."
fi

cd "$ROOT"

# Final optimizations
$ARTISAN horizon:terminate

echo "Deployment completed: $ROOT/$NEW_RELEASE_ROOT"
