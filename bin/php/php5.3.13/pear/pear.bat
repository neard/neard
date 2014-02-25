@ECHO OFF

REM ----------------------------------------------------------------------
REM PHP version 5
REM ----------------------------------------------------------------------
REM Copyright (c) 1997-2010 The Authors
REM ----------------------------------------------------------------------
REM http://opensource.org/licenses/bsd-license.php New BSD License
REM ----------------------------------------------------------------------
REM  Authors:     Alexander Merz (alexmerz@php.net)
REM ----------------------------------------------------------------------
REM
REM  Last updated 12/29/2004 ($Id$ is not replaced if the file is binary)

REM change this lines to match the paths of your system
REM -------------------

REM Get parent path (Neard)
FOR %%i IN ("%~dp0..") DO SET "NEARD_PHP_PATH=%%~fi"

REM Force system tmp dir (Neard)
SET "TMP=%NEARD_PHP_PATH%\pear\tmp"

REM Overwrite pear.ini (Neard)
ECHO #PEAR_Config 0.9>%NEARD_PHP_PATH%\pear\pear.ini
ECHO a:12:{s:15:"preferred_state";s:6:"stable";s:8:"temp_dir";s:45:"%NEARD_PHP_PATH%\pear\tmp";s:12:"download_dir";s:45:"%NEARD_PHP_PATH%\pear\tmp";s:7:"bin_dir";s:41:"%NEARD_PHP_PATH%\pear";s:7:"php_dir";s:46:"%NEARD_PHP_PATH%\pear\pear";s:7:"doc_dir";s:46:"%NEARD_PHP_PATH%\pear\docs";s:8:"data_dir";s:46:"%NEARD_PHP_PATH%\pear\data";s:7:"cfg_dir";s:45:"%NEARD_PHP_PATH%\pear\cfg";s:7:"www_dir";s:45:"%NEARD_PHP_PATH%\pear\www";s:8:"test_dir";s:47:"%NEARD_PHP_PATH%\pear\tests";s:7:"php_bin";s:45:"%NEARD_PHP_PATH%\php.exe";s:10:"__channels";a:3:{s:5:"__uri";a:0:{}s:11:"doc.php.net";a:0:{}s:12:"pecl.php.net";a:0:{}}}>>%NEARD_PHP_PATH%\pear\pear.ini

REM Test to see if this is a raw pear.bat (uninstalled version)
SET TMPTMPTMPTMPT=@includ
SET PMTPMTPMT=%TMPTMPTMPTMPT%e_path@
FOR %%x IN ("%NEARD_PHP_PATH%\pear\pear") DO (if %%x=="%PMTPMTPMT%" GOTO :NOTINSTALLED)

REM Set PEAR global ENV (Neard)
SET "PHP_PEAR_INSTALL_DIR=%NEARD_PHP_PATH%\pear\pear"
SET "PHP_PEAR_BIN_DIR=%NEARD_PHP_PATH%\pear"
SET "PHP_PEAR_PHP_BIN=%NEARD_PHP_PATH%\php.exe"

GOTO :INSTALLED

:NOTINSTALLED
ECHO WARNING: This is a raw, uninstalled pear.bat

REM Check to see if we can grab the directory of this file (Windows NT+)
IF %~n0 == pear (
FOR %%x IN (cli\php.exe php.exe) DO (if "%%~$PATH:x" NEQ "" (
SET "PHP_PEAR_PHP_BIN=%%~$PATH:x"
echo Using PHP Executable "%PHP_PEAR_PHP_BIN%"
"%PHP_PEAR_PHP_BIN%" -v
GOTO :NEXTTEST
))
GOTO :FAILAUTODETECT
:NEXTTEST
IF "%PHP_PEAR_PHP_BIN%" NEQ "" (

REM We can use this PHP to run a temporary php file to get the dirname of pear

echo ^<?php $s=getcwd^(^);chdir^($a=dirname^(__FILE__^).'\\'^);if^(stristr^($a,'\\scripts'^)^)$a=dirname^(dirname^($a^)^).'\\';$f=fopen^($s.'\\~a.a','wb'^);echo$s.'\\~a.a';fwrite^($f,$a^);fclose^($f^);chdir^($s^);?^> > ~~getloc.php
"%PHP_PEAR_PHP_BIN%" ~~getloc.php
set /p PHP_PEAR_BIN_DIR=fakeprompt < ~a.a
DEL ~a.a
DEL ~~getloc.php
set "PHP_PEAR_INSTALL_DIR=%PHP_PEAR_BIN_DIR%pear"

REM Make sure there is a pearcmd.php at our disposal

IF NOT EXIST %PHP_PEAR_INSTALL_DIR%\pearcmd.php (
IF EXIST %PHP_PEAR_INSTALL_DIR%\scripts\pearcmd.php COPY %PHP_PEAR_INSTALL_DIR%\scripts\pearcmd.php %PHP_PEAR_INSTALL_DIR%\pearcmd.php
IF EXIST pearcmd.php COPY pearcmd.php %PHP_PEAR_INSTALL_DIR%\pearcmd.php
IF EXIST %~dp0\scripts\pearcmd.php COPY %~dp0\scripts\pearcmd.php %PHP_PEAR_INSTALL_DIR%\pearcmd.php
)
)
GOTO :INSTALLED
) ELSE (
REM Windows Me/98 cannot succeed, so allow the batch to fail
)
:FAILAUTODETECT
echo WARNING: failed to auto-detect pear information
:INSTALLED

REM Check Folders and files
IF NOT EXIST "%PHP_PEAR_INSTALL_DIR%" GOTO PEAR_INSTALL_ERROR
IF NOT EXIST "%PHP_PEAR_INSTALL_DIR%\pearcmd.php" GOTO PEAR_INSTALL_ERROR2
IF NOT EXIST "%PHP_PEAR_BIN_DIR%" GOTO PEAR_BIN_ERROR
IF NOT EXIST "%PHP_PEAR_PHP_BIN%" GOTO PEAR_PHPBIN_ERROR

REM launch pearcmd
GOTO RUN
:PEAR_INSTALL_ERROR
ECHO PHP_PEAR_INSTALL_DIR is not set correctly.
ECHO Please fix it using your environment variable or modify
ECHO the default value in pear.bat
ECHO The current value is:
ECHO %PHP_PEAR_INSTALL_DIR%
GOTO END
:PEAR_INSTALL_ERROR2
ECHO PHP_PEAR_INSTALL_DIR is not set correctly.
ECHO pearcmd.php could not be found there.
ECHO Please fix it using your environment variable or modify
ECHO the default value in pear.bat
ECHO The current value is:
ECHO %PHP_PEAR_INSTALL_DIR%
GOTO END
:PEAR_BIN_ERROR
ECHO PHP_PEAR_BIN_DIR is not set correctly.
ECHO Please fix it using your environment variable or modify
ECHO the default value in pear.bat
ECHO The current value is:
ECHO %PHP_PEAR_BIN_DIR%
GOTO END
:PEAR_PHPBIN_ERROR
ECHO PHP_PEAR_PHP_BIN is not set correctly.
ECHO Please fix it using your environment variable or modify
ECHO the default value in pear.bat
ECHO The current value is:
ECHO %PHP_PEAR_PHP_BIN%
GOTO END
:RUN
"%PHP_PEAR_PHP_BIN%" -C -d date.timezone=UTC -d output_buffering=1 -d safe_mode=0 -d open_basedir="" -d auto_prepend_file="" -d auto_append_file="" -d variables_order=EGPCS -d register_argc_argv="On" -d "include_path='%PHP_PEAR_INSTALL_DIR%'" -f "%PHP_PEAR_INSTALL_DIR%\pearcmd.php" -- %1 %2 %3 %4 %5 %6 %7 %8 %9
:END
@ECHO ON