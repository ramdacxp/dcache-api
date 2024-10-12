@echo off
setlocal
set ROOT=%~dp0
set BIN=%ROOT%bin\
set PHP=%BIN%php\php.exe -d extension_dir=ext -d extension=openssl

%PHP% %BIN%composer\composer.phar %*
endlocal
