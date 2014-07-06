# Changelog

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
