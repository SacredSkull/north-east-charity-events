@echo off
docker-machine rm default
docker-machine create --driver virtualbox default

upgrade.bat
