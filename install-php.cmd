@echo off
setlocal
set ROOT=%~dp0
set BIN=%ROOT%bin\
rem set URL=https://windows.php.net/downloads/releases/archives/php-7.4.32-Win32-vc15-x64.zip
set URL=https://windows.php.net/downloads/releases/archives/php-8.3.11-Win32-vs16-x64.zip
set ZIP=%BIN%\php.zip

echo Root: %ROOT%
IF NOT EXIST %BIN% mkdir %BIN%

if "%1" == "fast" goto :fast

REM Download
echo ### DOWNLOAD
del /f /q %ZIP% 2>NUL
curl -o %ZIP% %URL%

:fast

REM Cleanup db folders
echo ### CLEANUP
rmdir /s /q %BIN%\php 2>NUL

REM Download & Unzip
echo ### UNZIP
call npx qzip unzip %ZIP% %BIN%\php

endlocal
