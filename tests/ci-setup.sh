#!/usr/bin/env bash
if [ "${TRAVIS_SECURE_ENV_VARS}" = "true" ]; then
    if [ ! -d ~/.composer ]; then
        mkdir ~/.composer
    fi
    echo '{ "config": {"github-oauth":{"github.com": ' > ~/.composer/config.json
    echo "\"${GH_AUTH_TOKEN}\"" >> ~/.composer/config.json
    echo '}}}' >> ~/.composer/config.json
fi
