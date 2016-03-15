#!/bin/sh
cd /vagrant/src/

composer install

cd config/propel

php /vagrant/src/vendor/propel/propel/bin/propel.php config:convert
php /vagrant/src/vendor/propel/propel/bin/propel.php model:build
php /vagrant/src/vendor/propel/propel/bin/propel.php diff
php /vagrant/src/vendor/propel/propel/bin/propel.php migrate

echo "All done!"
sleep 2
