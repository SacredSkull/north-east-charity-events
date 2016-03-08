@echo off

If Not Exist "putty.exe" (
  powershell -Command "(New-Object Net.WebClient).DownloadFile('http://the.earth.li/~sgtatham/putty/latest/x86/putty.exe', 'putty.exe')"
)

putty.exe -ssh vagrant@localhost -P 2222 -pw vagrant -m upgrade.sh
pause