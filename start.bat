@echo off
REM ### VAGRANT CONFIG ###
REM vagrant up
REM If Not Exist "putty.exe" (
REM  powershell -Command "(New-Object Net.WebClient).DownloadFile('http://the.earth.li/~sgtatham/putty/latest/x86/putty.exe', 'putty.exe')"
REM )

REM putty.exe -ssh vagrant@localhost -P 2222 -pw vagrant -m reset.sh
REM start putty.exe -ssh vagrant@localhost -P 2222 -pw vagrant

REM ### DOCKER CONFIG ###
docker-machine start
env.bat
call docker-compose up -d
pause
