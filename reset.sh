#!/bin/sh
sudo service nginx restart
sudo service hhvm restart&

cd /vagrant/src/cache

sudo rm -rf *
