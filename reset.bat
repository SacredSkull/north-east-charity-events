@echo off
REM vagrant halt
REM vagrant up
REM vagrant provision

REM If Not Exist "putty.exe" (
REM   powershell -Command "(New-Object Net.WebClient).DownloadFile('http://the.earth.li/~sgtatham/putty/latest/x86/putty.exe', 'putty.exe')"
REM )

REM putty.exe -ssh vagrant@localhost -P 2222 -pw vagrant -m reset.sh

docker-machine rm default
docker-machine create --driver virtualbox default

upgrade.bat
