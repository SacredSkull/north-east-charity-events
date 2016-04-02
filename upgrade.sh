#!/bin/sh
cd /var/www/src

composer install

cd config/propel

# Clear the migrations
rm generated-migrations/*.php

php /var/www/src/vendor/propel/propel/bin/propel.php config:convert
php /var/www/src/vendor/propel/propel/bin/propel.php model:build
php /var/www/src/vendor/propel/propel/bin/propel.php diff
php /var/www/src/vendor/propel/propel/bin/propel.php migrate

echo "All done!"
sleep 2
