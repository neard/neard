# Changelog

## 1.0.16 (2015/12/13)

You have to download and install the latests [Neard Visual C++ Redistributables Package](https://github.com/crazy-max/neard-misc#visual-c-redistributables-package).

* Add PHP 7 support ([Issue #103](https://github.com/crazy-max/neard/issues/103))
* Manage tsdll since PHP 7
* Manage E_DEPRECATED for PHP 7
* Refresh aliases and vhosts on startup
* Upgrade PHP Markdown
* Replace placeholder with .gitignore files
* Node.js 0.12.9
* Create sub repositories on Github for binaries
* Move binaries from Sourceforge to Github (see README.md)
* Replace SublimeText with Notepad2
* Remove RunFromProcess tool

## 1.0.15 (2015/10/13)

* Error while switching php parameters ([Issue #97](https://github.com/crazy-max/neard/issues/97))
* Zend extension not recognized on PHP 5.2.x ([Issue #96](https://github.com/crazy-max/neard/issues/96))
* Add NodeJS to PATH ([Issue #95](https://github.com/crazy-max/neard/issues/95))
* Increase input length ([Issue #91](https://github.com/crazy-max/neard/issues/91))
* Add Composer in Neard tools ([Issue #80](https://github.com/crazy-max/neard/issues/80))
* Addon PHP : 5.6.14 ([Issue #34](https://github.com/crazy-max/neard/issues/34))

## 1.0.14 (2015/06/09)

* Bug while archiving logs ([Issue #89](https://github.com/crazy-max/neard/issues/89))
* Remove purge logs feature ([Issue #88](https://github.com/crazy-max/neard/issues/88))
* System Idle Process block services ([Issue #87](https://github.com/crazy-max/neard/issues/87))
* Increase timeout SSL certificate ([Issue #86](https://github.com/crazy-max/neard/issues/86))
* Addons Apache : 2.2.29, 2.4.12 ([Issue #34](https://github.com/crazy-max/neard/issues/34))
* Addons PHP : 5.2.17, 5.4.40, 5.5.24, 5.6.8 ([Issue #34](https://github.com/crazy-max/neard/issues/34))
* Addon Filezilla Server : 0.9.52.1 ([Issue #34](https://github.com/crazy-max/neard/issues/34))
* Addons MySQL : 5.5.44, 5.6.25 ([Issue #34](https://github.com/crazy-max/neard/issues/34))
* Addons MariaDB : 5.5.43, 10.0.19 ([Issue #34](https://github.com/crazy-max/neard/issues/34))
* Addons Node.js : 0.10.38, 0.11.16, 0.12.4 ([Issue #34](https://github.com/crazy-max/neard/issues/34))
* Suitable for PHP 5.2

## 1.0.13 (2015/04/20)

* Launch on startup fails sometimes ([Issue #83](https://github.com/crazy-max/neard/issues/83))
* Restarting Neard doesn't work ([Issue #82](https://github.com/crazy-max/neard/issues/82))
* Installing Apache addon need manual configuration ([Issue #81](https://github.com/crazy-max/neard/issues/81))
* Bug switching Online/Offline ([Issue #78](https://github.com/crazy-max/neard/issues/78))
* Add option to scan SVN and Git repos on Startup ([Issue #75](https://github.com/crazy-max/neard/issues/75))
* Review startup screen
* Review process management
* Adding APC configuration
* Bug on local url
* Add log archives

## 1.0.12 (2014/09/28)

* Split bins, tools, apps configuration ([Issue #74](https://github.com/crazy-max/neard/issues/74))
* Adding APC manager page ([Issue #73](https://github.com/crazy-max/neard/issues/73))
* Adding PHP extensions version (if available) on homepage ([Issue #72](https://github.com/crazy-max/neard/issues/72))
* Add logs menu ([Issue #71](https://github.com/crazy-max/neard/issues/71))
* Launch Neard as an explorer.exe child process ([Issue #70](https://github.com/crazy-max/neard/issues/70))
* Bug with APC configuration ([Issue #57](https://github.com/crazy-max/neard/issues/57) and [Issue #67](https://github.com/crazy-max/neard/issues/67))
* Addons : PHP 5.3.29, PHP 5.4.31, PHP 5.5.16, PHP 5.6.0, Filezilla Server 0.9.47 ([Issue #34](https://github.com/crazy-max/neard/issues/34))
* Avoid unlink and fsockopen errors
* Adding default timeout for scripts execution (120s)
* Change check port calls method
* Bug hard link log for filezilla
* Imagick core dll files splitted for each PHP version
* Adding Winbinder specific log file (neard-winbinder.log)
* Remove wget util
* Refresh environment variables only for Environment registry subkey
* Do not check Sublimetext updates
* Use Win32_Process WMI method to get current process
* Upgrade PEAR 1.9.5
* Check SSL port on homepage only if main port available
* Adding Imagick extension for PHP 5.5.x and 5.6.x
* SVN and Git repositories configuration in repos.dat file
* Bug with SVN and Git to seek repositories
* Xdebug 2.2.5
* Remove PHP 5.6.a3 addon
* Add log separator
* Bug timezone

## 1.0.11 (2014/08/14)

* Apache 2.4 not working (SSL issue) ([Issue #66](https://github.com/crazy-max/neard/issues/66))
* Add option to check SSL port ([Issue #65](https://github.com/crazy-max/neard/issues/65))
* Localhost SSL shortcut on menu ([Issue #64](https://github.com/crazy-max/neard/issues/64))
* Change Xlight with Filezilla FTP Server ([Issue #63](https://github.com/crazy-max/neard/issues/63))
* Bug checking latest release from Sourceforge ([Issue #62](https://github.com/crazy-max/neard/issues/62))
* Add item menu to create SSL certificate ([Issue #60](https://github.com/crazy-max/neard/issues/60))
* Error when XLight service start ([Issue #47](https://github.com/crazy-max/neard/issues/47))
* Loading window while checking port
* Filezilla started at launch
* FTPS using Explicit SSL/TLS on Filezilla
* Webgrind conf problem
* Remove localhost SSL certificate from package
* SSL status displayed on home page for Apache and Filezilla

## 1.0.10 (2014/08/08)

* Htaccess bug on homepage ([Issue #59](https://github.com/crazy-max/neard/issues/59))
* Add HTTPS support ([Issue #58](https://github.com/crazy-max/neard/issues/58))
* SSL requests are written in ``*_sslreq.log`` files
* Edit vhost window title bug
* Localhost apache logs are moved in ``apache_*.log`` files

## 1.0.9 (2014/07/14)

* Bug check MySQL and MariaDB ports ([Issue #56](https://github.com/crazy-max/neard/issues/56))
* Add Adminer application ([Issue #55](https://github.com/crazy-max/neard/issues/55))
* Failed to load PHP extensions from config file ([Issue #53](https://github.com/crazy-max/neard/issues/53))
* Restart all services restarts Neard ([Issue #52](https://github.com/crazy-max/neard/issues/52))
* Display www directories on homepage ([Issue #50](https://github.com/crazy-max/neard/issues/50))
* Bug switching php extension or apache module ([Issue #49](https://github.com/crazy-max/neard/issues/49))
* Addons : PHP 5.4.30, PHP 5.5.14 ([Issue #34](https://github.com/crazy-max/neard/issues/34))
* Remove Bitcoin donation
* Remove comments to manage services via Aestan or Win32Service ext

## 1.0.8 (2014/07/06)

* Bug switching php extension or apache module ([Issue #49](https://github.com/crazy-max/neard/issues/49))
* Port other than 80 issue ([Issue #48](https://github.com/crazy-max/neard/issues/48))
* XLight service disable by default

## 1.0.7 (2014/05/20)

* Restart item menu ([Issue #46](https://github.com/crazy-max/neard/issues/46))
* Node.js - npm fail with any command ([Issue #44](https://github.com/crazy-max/neard/issues/44))
* Manage and disable a service ([Issue #43](https://github.com/crazy-max/neard/issues/43)) 

## 1.0.6 (2014/04/18)

* Error window when switching status (online/offline) ([Issue #40](https://github.com/crazy-max/neard/issues/40))
* Refresh aliases and vhosts when switching status ([Issue #39](https://github.com/crazy-max/neard/issues/39))
* Localhost menu item wrong redirect bug ([Issue #38](https://github.com/crazy-max/neard/issues/38))
* Launch on startup item ([Issue #29](https://github.com/crazy-max/neard/issues/29))
* Check version on homepage and startup ([Issue #8](https://github.com/crazy-max/neard/issues/8))
* Clean some var_dump
* Bug with depreciated functions on PHP 5.6 (homepage)
* Bug with Gitlist

## 1.0.5 (2014/04/06)

* Move Apache rewrite logs ([Issue #37](https://github.com/crazy-max/neard/issues/37))
* Move PHP config file to his root folder ([Issue #36](https://github.com/crazy-max/neard/issues/36))
* Path bug in Node.js configuration file ([Issue #35](https://github.com/crazy-max/neard/issues/35))
* Addons : Apache 2.2.27, Apache 2.4.9, PHP 5.3.28, PHP 5.4.26, PHP 5.5.10, PHP 5.6.a3 ([Issue #34](https://github.com/crazy-max/neard/issues/34))
* Error window when refresh Git or SVN repo ([Issue #33](https://github.com/crazy-max/neard/issues/33))
* Port 21 by default for FTP server
* Port 3306 by default for MySQL
* Port 3307 by default for MariaDB
* Add cgi-bin path (to use via http://localhost/cgi-bin)
* Add mysqli.allow_persistent PHP setting
* Review batch exec output redirect
* Check PHP setting exists
* Remove rewrite log when creating virtual host

## 1.0.4 (2014/03/17)

* Add Xlight FTP Server ([Issue #30](https://github.com/crazy-max/neard/issues/30))
* Add Swedish language ([Issue #28](https://github.com/crazy-max/neard/issues/28))
* Create Microsoft Visual C++ Runtime libraries package ([Issue #27](https://github.com/crazy-max/neard/issues/27))
* Add Sublime Text app to display debug infos ([Issue #26](https://github.com/crazy-max/neard/issues/26))
* Add debug settings in MySQL and MariaDB menu ([Issue #25](https://github.com/crazy-max/neard/issues/25))
* If 'port X already used' error occurred, display the process using this port ([Issue #20](https://github.com/crazy-max/neard/issues/20))
* Create patches ([Issue #18](https://github.com/crazy-max/neard/issues/18))
* Review services launch process and increase pending timeout to 10 seconds
* Edit some gitignore
* Add clear temp folder item (right menu)
* Move VBS and Batch processes
* Review loader
* Review switch online/offline
* Add batch and vbs logs files (neard-batch.log and neard-vbs.log)

## 1.0.3 (2014/03/10)

* Add debug settings in Apache menu ([Issue #22](https://github.com/crazy-max/neard/issues/22))
* Create vhosts add and edit item menu ([Issue #21](https://github.com/crazy-max/neard/issues/21))
* Add menu to change logs verbose ([Issue #19](https://github.com/crazy-max/neard/issues/19))
* Apache service error more verbose on startup ([Issue #20](https://github.com/crazy-max/neard/issues/20))
* Fix process status error (299)
* Fix destroy window error
* Adding default virtual host for localhost in httpd.conf

## 1.0.2 (2014/03/01)

* Fix error in Gitlist if no repositories ([Issue #15](https://github.com/crazy-max/neard/issues/15))
* Fix console won't launch ([Issue #14](https://github.com/crazy-max/neard/issues/14))
* Fix wrong version displayed on splash screen ([Issue #13](https://github.com/crazy-max/neard/issues/13))
* Loading window when reload ([Issue #6](https://github.com/crazy-max/neard/issues/6))
* Display hosts on homepage ([Issue #5](https://github.com/crazy-max/neard/issues/5))
* Restart service when change port ([Issue #4](https://github.com/crazy-max/neard/issues/4))
* Fix Git console error ([Issue #3](https://github.com/crazy-max/neard/issues/3))
* Fix errors on Windows XP ([Issue #16](https://github.com/crazy-max/neard/issues/16), [Issue #17](https://github.com/crazy-max/neard/issues/17))

## 1.0.1 (2014/02/26)

* Need SETX command to refresh environment variables ([Issue #12](https://github.com/crazy-max/neard/issues/12))
* Remove ENVDEV
* Adding EXEC action to differ exit/restart ([Issue #11](https://github.com/crazy-max/neard/issues/11))
* Adding hostname config var (auto completed during startup)
* Remove php_zip extension from core (7zip instead)
* Update SVN to 1.7.14
* Change services default port
* Bug when changing port

## 1.0.0 (2014/02/25)

* Initial version
