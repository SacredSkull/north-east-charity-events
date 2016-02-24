#!/bin/sh
sudo service nginx restart
sudo service hhvm restart&

cd /vagrant/site/cache

sudo rm -rf *
