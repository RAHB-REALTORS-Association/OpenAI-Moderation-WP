#!/bin/bash

# Set the version number
version=$(grep "^Version:" openai-moderation.php | awk '{print $2}')

# Create the plugin package
cd ..
zip -r openai-moderation-$version.zip openai-moderation -x "*.git*" -x "*.DS_Store" -x "*build.sh*"

# Move the plugin package to the dist directory
mv openai-moderation-$version.zip openai-moderation/dist/
