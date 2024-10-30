#!/bin/bash

# Prompt for source branch with default value
read -r -p "Enter source branch [develop]: " source
source=${source:-develop}

# Prompt for target branch with default value
read -r -p "Enter target branch [stage]: " target
target=${target:-stage}

# Execute git commands
git checkout "$source"
git pull origin "$source"
git checkout "$target"
git merge "$source"
git push origin "$target"
git checkout develop

echo "Merged $source into $target."
