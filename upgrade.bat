@echo off
REM ### DOCKER CONFIG ###
FOR /f "tokens=*" %%i IN ('docker-machine env') DO %%i
docker-compose up -d
docker exec northeastcharityevents_php_1 sh /var/www/upgrade.sh
pause
