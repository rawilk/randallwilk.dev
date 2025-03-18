#!/bin/bash

# Note: All referenced variables must be defined/exported from the
# Forge site deployment script.

# Note: Data (shared) root should already be created from
# our GitHub action runner.

# Extract the domain name (everything after last /)
TARGET=${FORGE_SITE_PATH##*/}

# Directory to store each release in.
RELEASE_ROOT="$FORGE_SITE_PATH/releases"

# Name the new release (with the current date/time)
RELEASE=$(date +"%Y%m%d%H%M%S")
NEW_RELEASE_ROOT="$RELEASE_ROOT/$RELEASE"

# Directory that has the master copies of files like .env
SHARED_ROOT="$FORGE_SITE_PATH/shared"

# Directory that holds the "current" release
CURRENT_ROOT="$FORGE_SITE_PATH/current"

# Our artisan php file for the new release
ARTISAN="$FORGE_PHP $NEW_RELEASE_ROOT/artisan"

# stop script on error signal (-e) and undefined variables (-u)
set -eu

echo "Deploying site: $TARGET"

# Ensure we're in our root site directory.
cd "$FORGE_SITE_PATH"

# Make sure git uses the deploy key if it is configured
SSH_COMMAND=$(git config --get core.sshcommand)
if [ "$SSH_COMMAND" ]; then
    echo "Using SSH command for git: $SSH_COMMAND"
    export GIT_SSH_COMMAND="$SSH_COMMAND"
fi

# Create a releases directory if it doesn't exist.
if [ ! -d "$RELEASE_ROOT" ]; then
    echo "Creating releases directory..."
    echo ""

    mkdir "$RELEASE_ROOT"
fi

# Clone the repository into a new release
echo "Release name: $RELEASE"

git clone -b "$FORGE_SITE_BRANCH" --depth 1 "$GIT_REPOSITORY" "$NEW_RELEASE_ROOT"

echo ""

# Install Composer Dependencies
$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev --working-dir "$NEW_RELEASE_ROOT"

echo ""

# Restart php
( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service "$FORGE_PHP_FPM" reload ) 9>/tmp/fpmlock

# Symlink master copies of persistent data to the new release.
echo "Symlinking site files..."

ln -sfn "$SHARED_ROOT/.env" "$NEW_RELEASE_ROOT/.env"
rm -rf "$NEW_RELEASE_ROOT/storage"
ln -sfn "$SHARED_ROOT/storage" "$NEW_RELEASE_ROOT/storage"

# Symlink the .env file to the site root, so forge can access it as well when needed.
if [ -f "$FORGE_SITE_PATH/.env" ]; then
    rm "$FORGE_SITE_PATH/.env"
fi

ln -sfn "$SHARED_ROOT/.env" "$FORGE_SITE_PATH/.env"

# Symlink sitemaps
ln -sfn "$SHARED_ROOT/public/sitemap.xml" "$NEW_RELEASE_ROOT/public/sitemap.xml"
ln -sfn "$SHARED_ROOT/public/sitemap_docs.xml" "$NEW_RELEASE_ROOT/public/sitemap_docs.xml"
ln -sfn "$SHARED_ROOT/public/sitemap_pages.xml" "$NEW_RELEASE_ROOT/public/sitemap_pages.xml"

# Symlink Doc Files
ln -sfn "$SHARED_ROOT/public/doc-files" "$NEW_RELEASE_ROOT/public/doc-files"

# Symlink database snapshots
rm -rf "$NEW_RELEASE_ROOT/database/snapshots"
ln -sfn "$SHARED_ROOT/database/snapshots" "$NEW_RELEASE_ROOT/database/snapshots"

# Symlink git configuration
rm -rf "$NEW_RELEASE_ROOT/.git"
ln -sfn "$FORGE_SITE_PATH/.git" "$NEW_RELEASE_ROOT/.git"

# Install Node Dependencies
echo "Installing npm and node dependencies..."
npm install --no-audit --prefix "$NEW_RELEASE_ROOT"

# Optimize Site
$ARTISAN optimize
$ARTISAN filament:optimize
$ARTISAN storage:link
$ARTISAN reverb:restart

# Build front-end assets
echo "Compiling front-end assets..."

npm run build --prefix "$NEW_RELEASE_ROOT"

# Cleanup node_modules
echo ""
echo "Cleaning up node_modules..."

rm -rf "$NEW_RELEASE_ROOT/node_modules"

# Mark deployment as success
echo "$RELEASE" >> "$RELEASE_ROOT/.success"

# Link to the new deployment
echo "Linking new release..."

# Create atomic symlink to new release
ln -sfn "$NEW_RELEASE_ROOT" "$CURRENT_ROOT-temp"
mv -Tf "$CURRENT_ROOT-temp" "$CURRENT_ROOT"

# Symlink artisan file to the project root, so commands can be run through forge.
if [ -f "$FORGE_SITE_PATH/artisan" ]; then
    rm "$FORGE_SITE_PATH/artisan"
fi
ln -sfn "$NEW_RELEASE_ROOT/artisan" "$FORGE_SITE_PATH/artisan"

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
echo ""
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

# Run Migrations
$ARTISAN migrate --force

# Final optimizations
$ARTISAN horizon:terminate

echo "Deployment completed: $NEW_RELEASE_ROOT"
