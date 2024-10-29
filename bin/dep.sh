#!/bin/bash

# Note: All referenced variables must be defined/exported from the
# Forge site deployment script.

# Delete script once script exits
trap 'rm -- "$0"' EXIT

echo "running deployment"
echo "Site ID: $FORGE_SITE_ID"
