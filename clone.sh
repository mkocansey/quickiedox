#!/bin/bash

# update this to your own documentation repo url
# GitHub blocked passwords in terminals so you will not be able to use the https://url
GIT_REPO_URL="git@github.com:mkocansey/quickiedox-mds.git"

# update this to the directory you prefer to clone your docs into
DOCS_DIRECTORY="markdown"

# note the versions are not strings
# each version needs to be defined on its own line without quotes
DOC_VERSIONS=(
  main
  #master
  #2.x
  #1.x
)

# ensure user is running the script from the root of the proeject
if [ ! -f composer.json ]; then
    echo "Please make sure to run this script from the root directory of this repo."
    exit 1
else 
    composer install
fi

# rename .env-example to .env if no .env file is found
if [ ! -f .env ]; then
    mv .env-example .env
fi

# loop over DOC_VERSIONS and clone or pull
for v in "${DOC_VERSIONS[@]}"; do
    if [ -d "$DOCS_DIRECTORY/$v" ]; then
        echo "Updating $v..."
        (cd $DOCS_DIRECTORY/$v && git pull)
    else
        echo "Cloning $v..."
        git clone --branch $v --single-branch $GIT_REPO_URL "$DOCS_DIRECTORY/$v"
    fi;
done
