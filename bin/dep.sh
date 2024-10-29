#!/bin/bash

# Note: All referenced variables must be defined/exported from the
# Forge site deployment script.

# Note: Data dir should already be created from our GitHub action
# runner.

# Delete script once script exits
trap 'rm -- "$0"' EXIT

# Extract the domain name (everything after last /)
TARGET=${FORGE_SITE_PATH##*/}

# Extract the root path (everything before last /)
ROOT=${FORGE_SITE_PATH%/*}

# Directory to store each release in.
RELEASE_ROOT="$TARGET-releases"

echo "Deploying site: $TARGET"

# Ensure we're in our root directory.
cd "$ROOT"

# Create a releases directory if it doesn't exist.
if [ ! -d "$RELEASE_ROOT" ]; then
    echo "Creating releases directory..."
    echo ""

    mkdir "$RELEASE_ROOT"
fi

# Clone the repository into a new release
echo "Creating new release..."
echo "-----------------------"
