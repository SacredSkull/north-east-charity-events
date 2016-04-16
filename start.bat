@echo off
REM ### DOCKER CONFIG ###
docker-machine start
FOR /f "tokens=*" %%i IN ('docker-machine env') DO %%i
docker-compose up -d
echo.
echo.
echo.
echo.
echo.
echo Your web server IP is %DOCKER_HOST%. Ignore the first tcp:// and replace :2376 with :8080
echo.
echo.
echo.
pause
