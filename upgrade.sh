#!/usr/bin/env bash
cd /var/www/src/NorthEastEvents

composer config --global github-protocols https
composer update

cd config/propel

# Clear the migrations
rm generated-migrations/*.php

propel config:convert
propel model:build
propel diff
propel migrate

echo "All done!"
sleep 2
