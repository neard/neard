# Changelog

## 1.0.21 (2016/10/22)

* Replace launchStartup with enable for binaries (Issue #153)
* Better way to manage bundles (Issue #153)
* Check if PHP Apache module is loaded (phpMyAdmin and phpPgAdmin aliases)
* Add memcached extension to php lib core (check service)
* Bug with LaunchStartupService option
* Bug on install service item menu (Issue #152)
* Redundant check service installed
* libpq required for php lib core (PostgreSQL)
* Update Neard Visual C++ Redistributables Package (PostgreSQL)
* Add npmrc item in Node.js menu
* Add phpPgAdmin app
* Update about dialog
* Misspelled on WebSVN class
* Add PostgreSQL binary (Issue #143)
* Update Adminer conf for PostgreSQL
* Change service displayName, description and startType after creation
* Do not remove service if not installed on exit
* Implement launch on startup for Memcached
* Launch on startup option for Mailhog not implemented (Issue #149)
* Add phpMemAdmin application (Issue #145)
* Add MailHog log menu item
* Add Memcached binary (Issue #145)
* Bug with ASCII char on PHP 5.2
* sprintf bug on PHP 5.2
* E_DEPRECATED not defined on PHP 5.2
* Start and stop services icons reversed (Issue #141)

### Upgrade from previous release

* **required** : Download and install the latests [Neard Visual C++ Redistributables Package](https://github.com/crazy-max/neard-misc#visual-c-redistributables-package)
* **required** : Remove then replace folder `apps\adminer`
* **required** : Copy folder `apps\phpmemadmin`
* **required** : Copy folder `apps\phppgadmin`
* **required** : Copy file `alias\phpmemadmin.conf`
* **required** : Copy file `alias\phppgadmin.conf`
* **required** : Copy file `alias\phpmyadmin.conf`
* **required** : Copy folder `bin\memcached`
* **required** : Copy folder `bin\postgresql`
* **required** : Remove then replace folder `core`
* **required** : Replace `apacheLaunchStartup` with `apacheEnable` in `neard.conf`
* **required** : Replace `mysqlLaunchStartup` with `mysqlEnable` in `neard.conf`
* **required** : Replace `mariadbLaunchStartup` with `mariadbEnable` in `neard.conf`
* **required** : Replace `filezillaLaunchStartup` with `filezillaEnable` in `neard.conf`
* **required** : Replace `mailhogLaunchStartup` with `mailhogEnable` in `neard.conf`
* **required** : Add line `postgresqlVersion = "9.4.8"` after `mariadbEnable` in `neard.conf`
* **required** : Add line `postgresqlEnable = "1"` after `postgresqlVersion` in `neard.conf`
* **required** : Add line `memcachedVersion = "1.4.5"` after `mailhogEnable` in `neard.conf`
* **required** : Add line `memcachedEnable = "1"` after `memcachedVersion` in `neard.conf`
* **required** : Add line `phpmemadminVersion = "0.3.1"` after `gitlistVersion` in `neard.conf`
* **required** : Add line `phppgadminVersion = "5.2"` after `phpmyadminVersion` in `neard.conf`
* **required** : Remove then replace file `sprites.dat`

## 1.0.20 (2016/06/23)

* Bug with homepage queries ([Issue #139](https://github.com/crazy-max/neard/issues/139))
* Add Administration button for FileZilla Server Interface ([Issue #138](https://github.com/crazy-max/neard/issues/138))
* Add download links for binaries / tools / apps on homepage
* Migrate from Notepad2 to Notepad2-mod
* Update Composer to 1.1.2
* Update Git to 2.9.0
* Update ImageMagick to 6.9.3-10
* Update PHPUnit to 4.8.26
* Update Adminer to 4.2.5
* Update phpMyAdmin to 4p3
* Update Webgrind to 1.3.1

### Upgrade from previous release

* **required** : Remove then replace folder `bin\filezilla` (or download a compatible version of your choice)
* **required** : Remove line starting with `notepad2Version =` in `neard.conf`
* **required** : Add line `notepad2modVersion = "4.2.25.980"` after `imagemagickVersion` in `neard.conf`
* **required** : Copy folder `tools\notepad2mod`
* **required** : Remove folder `tools\notepad2`
* **required** : Remove then replace folder `core`
* **required** : Remove then replace file `sprites.dat`
* **optional** : Change `composerVersion` value to `1.1.2` in `neard.conf` and copy folder `tools\composer\composer1.1.2`
* **optional** : Change `gitVersion` value to `2.9.0` in `neard.conf` and copy folder `tools\git\git2.9.0`
* **optional** : Change `imagemagickVersion` value to `6.9.3-10` in `neard.conf` and copy folder `tools\imagemagick\imagemagick6.9.3-10`
* **optional** : Change `phpunitVersion` value to `4.8.26` in `neard.conf` and copy folder `tools\phpunit\phpunit4.8.26`
* **optional** : Change `adminerVersion` value to `4.2.5` in `neard.conf` and copy folder `apps\adminer\adminer4.2.5`
* **optional** : Change `phpmyadminVersion` value to `4p3` in `neard.conf` and copy folder `apps\phpmyadmin\phpmyadmin4p3`
* **optional** : Change `webgrindVersion` value to `1.3.1` in `neard.conf` and copy folder `apps\webgrind\webgrind1.3.1`

## 1.0.19 (2016/05/05)

* Bug while loading latest changelog from homepage ([Issue #135](https://github.com/crazy-max/neard/issues/135))
* Add MailHog binary : [0.2.0-r1](https://github.com/crazy-max/neard-bin-mailhog/releases/tag/r1) ([Issue #32](https://github.com/crazy-max/neard/issues/32), [Issue #119](https://github.com/crazy-max/neard/issues/119))
* Add menu item to change MySQL and MariaDB root password ([Issue #134](https://github.com/crazy-max/neard/issues/134))
* Add PhpMetrics tool : [1.0.1-r1, 1.1.1-r1, 1.9.2-r1](https://github.com/crazy-max/neard-tool-phpmetrics/releases/tag/r1) ([Issue #121](https://github.com/crazy-max/neard/issues/121))
* Add WP-CLI tool : [0.12.1-r1, 0.13.0-r1, 0.14.1-r1, 0.15.1-r1, 0.16.0-r1, 0.17.2-r1, 0.18.1-r1, 0.19.3-r1, 0.20.4-r1, 0.21.1-r1, 0.22.0-r1, 0.23.1-r1](https://github.com/crazy-max/neard-tool-wpcli/releases/tag/r1) ([Issue #121](https://github.com/crazy-max/neard/issues/121))
* Add Drush tool : [5.9.0-r1, 6.7.0-r1, 7.3.0-r1, 8.1.0-r1](https://github.com/crazy-max/neard-tool-drush/releases/tag/r1) ([Issue #121](https://github.com/crazy-max/neard/issues/121))
* Add PHPUnit tool : [4.8.24-r1, 5.3.2-r1](https://github.com/crazy-max/neard-tool-phpunit/releases/tag/r1) ([Issue #121](https://github.com/crazy-max/neard/issues/121))
* Create Wiki documentation ([Issue #31](https://github.com/crazy-max/neard/issues/31))
* Wrong RewriteBase on GitList 0.5.0-r2
* Upgrade PHP Markdown
* Add NSSM lib required for MailHog
* Add reference to IMDisplay.exe in ImageMagick neard.conf
* Upgrade ImageMagick to release 3
* Move Console icons to core resources
* Add [ANSICON](https://github.com/adoxa/ansicon) to resolve ANSI escape sequences in Console
* Upgrade Console tool to pack 2 release 3
* No return value while switching version
* Retrieve latest version from wiki page `latestVersion.md` instead of `CHANGELOG.md`
* New menu organization
* Change binaries summary order on homepage
* Add log trace type
* Improve startup performances
* Review config update procs
* Remove useless constants
* Bug while changing port (service not restarted)

### Upgrade from previous release

* Add line `mailhogVersion = "0.2.0"` after `filezillaLaunchStartup` in `neard.conf`
* Add line `mailhogLaunchStartup = "1"` after `mailhogVersion` in `neard.conf`
* Add line `drushVersion = "7.3.0"` after `consoleVersion` in `neard.conf`
* Add line `phpunitVersion = "4.8.24"` after `notepad2Version` in `neard.conf`
* Add line `phpmetricsVersion = "1.0.1"` after `phpunitVersion` in `neard.conf`
* Add line `wpcliVersion = "0.20.4"` after `svnVersion` in `neard.conf`
* Remove then replace folder `apps\adminer`
* Remove then replace folder `apps\gitlist`
* Remove then replace folder `apps\phpmyadmin`
* Copy folder `bin\mailhog`
* Add line `mariadbRootUser = "root"` after `mariadbPort` in `bin\mariadb\mariadbx.x.x\neard.conf`
* Add line `mariadbRootPwd = ""` after `mariadbRootUser` in `bin\mariadb\mariadbx.x.x\neard.conf`
* Add line `mysqlRootUser = "root"` after `mysqlPort` in `bin\mysql\mysqlx.x.x\neard.conf`
* Add line `mysqlRootPwd = ""` after `mysqlRootUser` in `bin\mysql\mysqlx.x.x\neard.conf`
* Remove then replace folder `core`
* Remove then replace folder `tools\console`
* Copy folder `tools\drush`
* Remove then replace folder `tools\imagemagick`
* Copy folder `tools\phpunit`
* Copy folder `tools\phpmetrics`
* Copy folder `tools\wpcli`
* Copy folder `tmp\drush`
* Copy folder `tmp\mailhog`
* Copy folder `tmp\wp-cli`
* Remove then replace file `sprites.dat`

## 1.0.18 (2016/04/21)

* Bug while checking port on MariaDB / MySQL ([Issue #130](https://github.com/crazy-max/neard/issues/130))
* Add HostsEditor tool for editing windows Hosts file ([Issue #129](https://github.com/crazy-max/neard/issues/129))
* Load homepage with ajax requests ([Issue #128](https://github.com/crazy-max/neard/issues/128))
* Generate self-signed certificates with SHA256 digest ([Issue #127](https://github.com/crazy-max/neard/issues/127))
* Move SetEnv tool to core ([Issue #126](https://github.com/crazy-max/neard/issues/126))
* Change Notepad2 temporary folder location ([Issue #125](https://github.com/crazy-max/neard/issues/125))
* Update SVN Apache module path on fly ([Issue #124](https://github.com/crazy-max/neard/issues/124))
* Update config files on startup (apps, bins, tools) ([Issue #123](https://github.com/crazy-max/neard/issues/123))
* Skip npm-cache clear on startup ([Issue #122](https://github.com/crazy-max/neard/issues/122))
* Always need a restart to run Neard ([Issue #118](https://github.com/crazy-max/neard/issues/118))
* Check valid domain name when adding vhost ([Issue #117](https://github.com/crazy-max/neard/issues/117))
* Add aliases doesn't work with quote or accented letters or special letters ([Issue #116](https://github.com/crazy-max/neard/issues/116))
* Add Hungarian language ([Issue #112](https://github.com/crazy-max/neard/issues/112))
* Some chars are not handled by Aestan Tray Menu ([Issue #112](https://github.com/crazy-max/neard/issues/112))
* New Apache binary bundle : [2.4.20-r2](https://github.com/crazy-max/neard-bin-apache/releases/tag/r2) ([Issue #119](https://github.com/crazy-max/neard/issues/119))
* New Filezilla Server binary bundle : [0.9.56.1-r2](https://github.com/crazy-max/neard-bin-filezilla/releases/tag/r2) ([Issue #119](https://github.com/crazy-max/neard/issues/119))
* New MariaDB binary bundle : [5.5.48-r2, 10.0.24-r2, 10.1.13-r2](https://github.com/crazy-max/neard-bin-mariadb/releases/tag/r2) ([Issue #119](https://github.com/crazy-max/neard/issues/119))
* New MySQL binary bundle : [5.5.49-r2, 5.6.30-r2](https://github.com/crazy-max/neard-bin-mysql/releases/tag/r2) ([Issue #119] (https://github.com/crazy-max/neard/issues/119))
* New Node.js binary bundle : [0.12.13-r3, 4.4.3-r3, 5.10.1-r3](https://github.com/crazy-max/neard-bin-nodejs/releases/tag/r3) ([Issue #119](https://github.com/crazy-max/neard/issues/119))
* New PHP binary bundle : [5.5.34-r6, 5.6.20-36, 7.0.5-r6](https://github.com/crazy-max/neard-bin-php/releases/tag/r6) ([Issue #119](https://github.com/crazy-max/neard/issues/119))
* Add Ant build ([Issue #54](https://github.com/crazy-max/neard/issues/54))
* Upgrade Composer tool to version 1.0.0 (2016/04/05)
* Upgrade Console tool to pack 2 (Console 2.00.148 and TCC/LE 14.0.0.9)
* Upgrade Git tool to version 2.8.1
* Upgrade SVN tool to version 1.7.22
* Upgrade XDebugClient tool to version 1.0b5
* Upgrade Adminer app to version 4.2.4
* Upgrade Gitlist app to version 0.5.0
* Upgrade phpMyAdmin app to pack 2 (4.0.10.15, 4.4.15.5, 4.6.0)
* Upgrade Webgrind app to version 1.1
* Review phpMyAdmin implementation
* Display changelog link in new release dialog
* Create sub repositories on Github for apps and tools (see 'Download' section in README.md)
* Neard is now compressed with 7z format
* Use resources url instead of base64 data
* Remove internal hosts management
* HTTP headers now retrieved via cURL if available
* Disable auto CRLF on Git and add LF eol in global config

### Upgrade from previous release

* Change `consoleVersion` value to `p2` in `neard.conf`
* Change `gitVersion` value to `2.8.1` in `neard.conf`
* Add line `hostseditorVersion = "1.1.0.0"` after `gitVersion` in `neard.conf`
* Change `imagemagickVersion` value to `6.9.3-8` in `neard.conf`
* Change `svnVersion` value to `1.7.22` in `neard.conf`
* Change `xdcVersion` value to `1.0b5` in `neard.conf`
* Change `adminerVersion` value to `4.2.4` in `neard.conf`
* Change `gitlistVersion` value to `0.5.0` in `neard.conf`
* Change `phpmyadminVersion` value to `4p2` in `neard.conf`
* Change `webgrindVersion` value to `1.1` in `neard.conf`
* Remove `setenvVersion` key in `neard.conf`
* Remove then replace file `alias/adminer.conf`
* Remove then replace file `alias/gitlist.conf`
* Remove then replace file `alias/phpmyadmin.conf`
* Remove then replace file `alias/webgrind.conf`
* Remove then replace folder `apps`
* Remove then replace folder `core`
* Remove then replace folder `tools`
* Remove then replace file `sprites.dat`

## 1.0.17 (2016/01/26)

* Retrieve latest version from CHANGELOG.md
* Restart Neard when Node.js has been switched (environment variables)
* Missplaced ActionReload
* Upgrade phpMyAdmin to 4.0.10.12 / 4.4.15.2 / 4.5.3.1 ([Issue #107](https://github.com/crazy-max/neard/issues/107))
* Add manifest inside neard.exe

### Upgrade from previous release

* Remove `tccleVersion` key in `neard.conf`
* Change `phpmyadminVersion` value to `4` in `neard.conf`
* Remove then replace file `alias/phpmyadmin.conf`
* Remove then replace folder `apps/phpmyadmin`
* Remove then replace folder `core`
* Remove then replace folder `tools/console`
* Remove folder `tools/tccle`
* Remove file `neard.exe.manifest`
* Remove file `neard.exe.rc`

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
* SSL requests are written in `*_sslreq.log` files
* Edit vhost window title bug
* Localhost apache logs are moved in `apache_*.log` files

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
