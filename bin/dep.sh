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
NEW_RELEASE_ROOT="${RELEASE_ROOT}/$RELEASE"

# Directory that has the master copies of files like .env
DATA_ROOT="$ROOT/$TARGET-data"

# stop script on error signal (-e) and undefined variables (-u)
set -eu

restart_php() {
    ( flock -w 10 9 || exit 1
        echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock
}

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
echo "-----------------------"

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
