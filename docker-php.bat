@echo off
SET CURR_DIR=%~dp0
SET PROPERPATH=%1
setlocal enabledelayedexpansion
SET REPLACEMENT=/
set PROPERPATH=%PROPERPATH:\=!REPLACEMENT!%

set after1=

shift

:loop
if "%1" == "" goto end
set after1=%after1% %1
SHIFT
goto loop

:end
"C:\Program Files\Git\usr\bin\bash.exe" --login %CURR_DIR%docker-php.sh %PROPERPATH% %after1%
REM "C:\Program Files (x86)\Git\git-bash.exe" --login docker-php.sh %PROPERPATH% %*
