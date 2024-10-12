@echo off
setlocal
set ROOT=%~dp0
set BIN=%ROOT%bin\
set COMPOSERDIR=%BIN%composer\
set PHP=%BIN%php\php.exe -d extension_dir=ext -d extension=openssl

rem https://getcomposer.org/download/
rem https://github.com/composer/getcomposer.org/tree/11cb825ad3d659a4f63fe591226b8f3545897914/web/download

set URL=https://raw.githubusercontent.com/composer/getcomposer.org/11cb825ad3d659a4f63fe591226b8f3545897914/web/download/2.8.1/composer.phar
set URL=https://raw.githubusercontent.com/composer/getcomposer.org/11cb825ad3d659a4f63fe591226b8f3545897914/web/installer
set SETUP=%COMPOSERDIR%composer-setup.php

echo Root: %ROOT%
IF NOT EXIST %COMPOSERDIR% mkdir %COMPOSERDIR%

if "%1" == "fast" goto :fast

REM Download
echo ### DOWNLOAD
del /f /q %SETUP% 2>NUL
curl -o %SETUP% %URL%

:fast

REM Install
echo ### INSTALL
pushd %COMPOSERDIR%
%PHP% composer-setup.php
popd
endlocal
