#!/bin/bash 

rm -rf releases
mkdir -p releases

# Remove old packages
rm -rf ./releases/gutenberg-chatgpt ./releases/gutenberg-chatgpt.zip

# Create an optimized build of the JS app
npm run build:prod > /dev/null

# Copy current dir to tmp
rsync \
    -ua \
    --exclude='vendor/*' \
    --exclude='node_modules/*' \
    --exclude='releases/*' \
    . ./releases/gutenberg-chatgpt/

# Remove current node_modules folder (if any) 
# and install the dependencies without dev packages.
cd ./releases/gutenberg-chatgpt || exit
rm -rf ./node_modules/
composer install -o --no-dev

# Remove unneeded files in a WordPress plugin
rm -rf ./.git ./package.sh ./gulpfile.js ./assets/src/ \
    ./.vscode ./workspace.code-workspace ./bitbucket-pipelines.yml \
    ./.phplint-cache ./.phpunit.result.cache ./.editorconfig ./.eslintignore \
    ./.eslintrc.json ./.gitignore ./phpunit.xml.dist ./psalm.xml ./releases

cd ../

# Create a zip file from the optimized plugin folder
zip -rq gutenberg-chatgpt.zip ./gutenberg-chatgpt
rm -rf ./gutenberg-chatgpt

echo "Zip completed @ $(pwd)/gutenberg-chatgpt.zip"
