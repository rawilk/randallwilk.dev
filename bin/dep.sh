#!/bin/bash

# Delete script once script exits
trap 'rm -- "$0"' EXIT

echo "running deployment"
echo "Site ID: $FORGE_SITE_ID"
