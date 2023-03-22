#!/bin/bash

# Set the version number
version=$(grep "^Version:" openai-moderation.php | awk '{print $2}')

# Create the plugin package
zip -r openai-moderation-$version.zip . -x "*.git*" -x "*.DS_Store" -x "*build.sh*" -x "*dist*"

# Move the plugin package to the dist directory
mv openai-moderation-$version.zip dist/
