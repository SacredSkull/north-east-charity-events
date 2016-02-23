#!/bin/sh
cd /vagrant/site

composer install

php vendor/propel/propel/bin/propel.php config:convert
php vendor/propel/propel/bin/propel.php model:build
php vendor/propel/propel/bin/propel.php diff
php vendor/propel/propel/bin/propel.php migrate

echo "All done!"
sleep 2
