@echo off

REM ### VAGRANT CONFIG ###
REM If Not Exist "putty.exe" (
REM  powershell -Command "(New-Object Net.WebClient).DownloadFile('http://the.earth.li/~sgtatham/putty/latest/x86/putty.exe', 'putty.exe')"
REM )

REM putty.exe -ssh vagrant@localhost -P 2222 -pw vagrant -m upgrade.sh

REM ### DOCKER CONFIG ###
env.bat
docker-compose run --rm php sh /var/www/upgrade.sh
pause
