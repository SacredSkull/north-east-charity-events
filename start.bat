@echo off
REM ### DOCKER CONFIG ###
docker-machine start
FOR /f "tokens=*" %%i IN ('docker-machine env') DO %%i
docker-compose up -d
pause
