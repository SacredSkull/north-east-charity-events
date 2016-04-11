#!/bin/sh
sudo service nginx restart
sudo service hhvm restart&

cd /var/www/src/NorthEastEvents/cache

sudo rm -rf *
