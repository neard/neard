# Changelog

## 1.8.2 (2020/12/13)

* Fix Apache PHP 8 module
* Fix build base exclusions

## 1.8.1 (2020/12/13)

* Switch to GitHub Actions (#470)
* Add support for PHP 8 (#469)
* Yarn 1.22.4
* Memcached 1.6.6
* Webgrind 1.7.0

## 1.8.0 (2020/04/19)

* Provide basic and light releases (#136)
* Remove Drush, PhpMetrics, PHPUnit and WP-CLI modules (#453)

## 1.7.2 (2020/04/19)

* Repairing symbolic links when Neard changes path (#452)
* Use symlink path for PostgreSQL service
* Git 2.26.1
* Coding style

## 1.7.1 (2020/04/07)

* Reload module configuration after switching version (#448)
* Stop service before switching module version
* Set standard service name
* Log module reload
* Clean deprecated code
* Update Hungarian (#112)

## 1.7 (2020/04/05)

* Fix SVN check port
* Fix PostgreSQL console error
* Fix MongoDB service startup
* Use MongoDB driver to check connection status (#372)
* Replace Console with ConsoleZ (#406)
* It is no longer necessary to restart Neard when changing module versions
* Use symlinks to handle current path of active module version
* Fix MariaDB folder scan
* Log system info
* Disable deprecated/notice warnings for apps (#442)
* Drop Windows XP support
* Increase service startup timeout (#438)
* Apache 2.4.41
* Composer 1.9.1
* Git 2.26.0
* MariaDB 10.4.12
* MongoDB 4.2.5
* Node.js 13.12.0
* PHP 7.4.4
* phpMyAdmin 4p9
* Yarn 1.21.1

> :warning: Drop Windows XP support.

## 1.6 (2019/05/25)

* Switch to TravisCI
* Fix update URL
* Check Apache HTTP headers insensitive
* Add homepage logging in `neard-homepage.log`
* Typo lang
* Increase MySQL initialization timeout
* Apache 2.4.39
* MongoDB 3.4.20
* MySQL 8.0.16
* NodeJS 10.15.3 (LTS)
* PHP 7.3.5
* PostgreSQL 11.3
* Composer 1.8.5
* Git 2.20.1
* Perl 5.30.0.1
* PHPUnit 7.5.9
* Ruby 2.6.3-1
* Yarn 1.15.2

## 1.5 (2019/01/04)

* Mariadb 10.3.8
* MySQL 8.0.12
* NodeJS 11.4.0
* PHP 7.2.13
* Adminer 4.6.1
* phpMyAdmin 4p7
* Git 2.19.1
* Python 3.6.6.2
* Ruby 2.4.4
* Yarn 1.12.3

## 1.4 (2017/12/30)

* Manage Yarn cache and global path (#354)
* Yarn config not updated (#353)
* Put Composer cache-dir to neard/tmp/composer (neard/neard#346)
* Add global composer vendor/bin in PATH (#344)
* Move neard repositories to its own organization (#339)
* Add ngrok (#334)
* Remove HostsEditor module and add in core instead (#329)
* Create separated logs (stdout / stderr) for NSSM services (#326)
* ImageMagick `convert.exe` is overridden by `convert.exe` from Windows (#323)
* Put NEARD_BINS at the beginning of PATH (#323)
* Remove ImageMagick module (#322)
* Add a deps folder to load additional dependencies for PHP (#321)
* Fix error on homepage
* Increase MySQL initialization timeout
* Do not clear composer cache on startup
* Fix "Incorrect argument " error while retrieving list of processes
* Find Git repositories with a max depth of 2
* Update about dialog
* Upgrade to Markdown lib 1.7.0
* Wrong file size for version check on homepage
* Exclude .editorconfig from core
* Neard settings configurable through build.properties
* Apache 2.4.29
* Composer 1.5.6
* Console 2.00.148.4
* Drush 8.1.15
* Ghostscript 9.22
* Git 2.15.1.2
* Gitlist 0.6.0
* Node.js 8.9.3
* PHP 7.1.12
* PHPUnit 6.5.5
* Yarn 1.3.2

## 1.3 (2017/09/01)

* Debug variables empty for MySQL and MariaDB (#315)
* Remove Notepad2-mod (#314)
* Update modules links (#313)
* Add PHP 7.2 compatibility (#311)
* Upgrade to OpenSSL 1.1.0f (#309)
* Firefox & Chrome require the subjectAltName (SAN) X.509 extension for certificates (#308)
* Paths are not updated while switching version (#301)
* Exclude php_xdebug from PHP extensions (#298)
* Check Console shell (#291)
* Error while launching PostgreSQL console (#290)
* Error while launching SVN console (#289)
* New Console with Clink and GnuWin32 CoreUtils (Issues #287 #288)
* Update modules to stable releases (#264)
* Wrong file size on version check window (#251)
* Add Ghostscript (#220)
* Add Yarn (#157)
* Add Perl (#155)
* Exclude OPCache from extensions list
* Error while refreshing Apache SSL conf
* Missing return statement
* Unused local variables
* Change some access modifiers
* 7z release format only

> :warning: Modules have been updated to the latest stable release (#264). For Windows XP users, please read the [Windows XP limitation](https://neard.io/doc/faq/#windows-xp-limitation) section in the FAQ to download the right release.

## 1.2 (2017/04/20)

* Review versioning style (#247)
* Blank page for phpMyAdmin and Adminer if MariaDB or MySQL disabled (#243)
* Services cannot start / paused on Windows 10 Creators Update (#242)
* Display module release in logs (#209)
* Add ability to customize the env. PATH variable for NSSM services (#233)
* Improve phpinfo output (#230)
* Improve files scan on startup (#229)
* Missing check enable for SVN (#219)
* Service uninstallation error (#218)
* Create checksum for downloads (#211)
* MySQL/MariaDB console don't ask for password if not empty (#206)
* Localhost menu shortcuts don't reflect Apache port change (#205)
* Put online / offline does not work for virtual hosts and aliases (#202)
* Add MongoDB (#166)
* Update Hungarian language (#112)
* Switch to phpMemAdmin 0.1.0.41 as default version
* Use AppVeyor instead of Travis

## 1.0.22 (2016/12/18)

* Cannot switch to trace verbosity (#198)
* Apache PATH env mixed with LocalSystem env (#193)
* Dissociate Apache and SVN (#168)
* Use latest OpenSSL release to generate SSL certificate (#167)
* Forgetting Memcached switch version impl (#164)
* Port undefined error for MailHog service (#163)
* 30% CPU usage for 2 minutes while generating SSL certificate (#161)
* Can't switch PostgreSQL version (#160)
* Bug while enable binary (#156)
* Add Ruby tool (#154)
* Add Python tool (#98)
* Add Perl, Vbs and Python CGI scripts examples
* Bug while setting version in neard.conf
* Downgrade default Filezilla release to 0.9.42 (XP compatibility)
* Implement enable on tools and apps
* Integration of Codacy and Travis

## 1.0.21 (2016/10/23)

* Replace launchStartup with enable for binaries (#153)
* Better way to manage modules (#153)
* Check if PHP Apache module is loaded (phpMyAdmin and phpPgAdmin aliases)
* Add memcached extension to php lib core (check service)
* Bug with LaunchStartupService option
* Bug on install service item menu (#152)
* Redundant check service installed
* libpq required for php lib core (PostgreSQL)
* Update Neard Visual C++ Redistributables Package (PostgreSQL)
* Add npmrc item in Node.js menu
* Add phpPgAdmin app
* Update about dialog
* Misspelled on WebSVN class
* Add PostgreSQL binary (#143)
* Update Adminer conf for PostgreSQL
* Change service displayName, description and startType after creation
* Do not remove service if not installed on exit
* Implement launch on startup for Memcached
* Launch on startup option for Mailhog not implemented (#149)
* Add phpMemAdmin application (#145)
* Add MailHog log menu item
* Add Memcached binary (#145)
* Bug with ASCII char on PHP 5.2
* sprintf bug on PHP 5.2
* E_DEPRECATED not defined on PHP 5.2
* Start and stop services icons reversed (#141)

## 1.0.20 (2016/06/23)

* Bug with homepage queries (#139)
* Add Administration button for FileZilla Server Interface (#138)
* Add download links for binaries / tools / apps on homepage
* Migrate from Notepad2 to Notepad2-mod
* Composer 1.1.2
* Git 2.9.0
* ImageMagick 6.9.3-10
* PHPUnit 4.8.26
* Adminer 4.2.5
* phpMyAdmin 4p3
* Webgrind 1.3.1

## 1.0.19 (2016/05/05)

* Bug while loading latest changelog from homepage (#135)
* Add MailHog binary : 0.2.0-r1 (#32, #119)
* Add menu item to change MySQL and MariaDB root password (#134)
* PhpMetrics 1.0.1-r1, 1.1.1-r1, 1.9.2-r1 (#121)
* WP-CLI 0.12.1-r1, 0.13.0-r1, 0.14.1-r1, 0.15.1-r1, 0.16.0-r1, 0.17.2-r1, 0.18.1-r1, 0.19.3-r1, 0.20.4-r1, 0.21.1-r1, 0.22.0-r1, 0.23.1-r1 (#121)
* Drush 5.9.0-r1, 6.7.0-r1, 7.3.0-r1, 8.1.0-r1 (#121)
* PHPUnit 4.8.24-r1, 5.3.2-r1 (#121)
* Create Wiki documentation (#31)
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

## 1.0.18 (2016/04/21)

* Bug while checking port on MariaDB / MySQL (#130)
* Add HostsEditor tool for editing windows Hosts file (#129)
* Load homepage with ajax requests (#128)
* Generate self-signed certificates with SHA256 digest (#127)
* Move SetEnv tool to core (#126)
* Change Notepad2 temporary folder location (#125)
* Update SVN Apache module path on fly (#124)
* Update config files on startup (apps, bins, tools) (#123)
* Skip npm-cache clear on startup (#122)
* Always need a restart to run Neard (#118)
* Check valid domain name when adding vhost (#117)
* Add aliases doesn't work with quote or accented letters or special letters (#116)
* Add Hungarian language (#112)
* Some chars are not handled by Aestan Tray Menu (#112)
* Apache 2.4.20-r2 (#119)
* Filezilla Server 0.9.56.1-r2 (#119)
* MariaDB 5.5.48-r2, 10.0.24-r2, 10.1.13-r2 (#119)
* MySQL 5.5.49-r2, 5.6.30-r2 (#119)
* Node.js 0.12.13-r3, 4.4.3-r3, 5.10.1-r3 (#119)
* PHP 5.5.34-r6, 5.6.20-36, 7.0.5-r6 (#119)
* Add Ant build (#54)
* Composer 1.0.0 (2016/04/05)
* Console pack 2 (Console 2.00.148 and TCC/LE 14.0.0.9)
* Git 2.8.1
* SVN 1.7.22
* XDebugClient 1.0b5
* Adminer 4.2.4
* Gitlist 0.5.0
* phpMyAdmin pack 2 (4.0.10.15, 4.4.15.5, 4.6.0)
* Webgrind 1.1
* Review phpMyAdmin module implementation
* Display changelog link in new release dialog
* Create sub repositories on Github for apps and tools (see 'Download' section in README.md)
* Neard is now compressed with 7z format
* Use resources url instead of base64 data
* Remove internal hosts management
* HTTP headers now retrieved via cURL if available
* Disable auto CRLF on Git and add LF eol in global config

## 1.0.17 (2016/01/26)

* Retrieve latest version from CHANGELOG.md
* Restart Neard when Node.js has been switched (environment variables)
* Missplaced ActionReload
* phpMyAdmin 4.0.10.12 / 4.4.15.2 / 4.5.3.1 (#107)
* Add manifest inside neard.exe

## 1.0.16 (2015/12/13)

* Add PHP 7 support (#103)
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

* Error while switching php parameters (#97)
* Zend extension not recognized on PHP 5.2.x (#96)
* Add NodeJS to PATH (#95)
* Increase input length (#91)
* Add Composer in Neard tools (#80)
* PHP 5.6.14 (#34)

## 1.0.14 (2015/06/09)

* Bug while archiving logs (#89)
* Remove purge logs feature (#88)
* System Idle Process block services (#87)
* Increase timeout SSL certificate (#86)
* Apache 2.2.29, 2.4.12 (#34)
* PHP 5.2.17, 5.4.40, 5.5.24, 5.6.8 (#34)
* Filezilla Server 0.9.52.1 (#34)
* MySQL 5.5.44, 5.6.25 (#34)
* MariaDB 5.5.43, 10.0.19 (#34)
* Node.js 0.10.38, 0.11.16, 0.12.4 (#34)
* Suitable for PHP 5.2

## 1.0.13 (2015/04/20)

* Launch on startup fails sometimes (#83)
* Restarting Neard doesn't work (#82)
* Installing Apache addon need manual configuration (#81)
* Bug switching Online/Offline (#78)
* Add option to scan SVN and Git repos on Startup (#75)
* Review startup screen
* Review process management
* Adding APC configuration
* Bug on local url
* Add log archives

## 1.0.12 (2014/09/28)

* Split bins, tools, apps configuration (#74)
* Adding APC manager page (#73)
* Adding PHP extensions version (if available) on homepage (#72)
* Add logs menu (#71)
* Launch Neard as an explorer.exe child process (#70)
* Bug with APC configuration (#57, #67)
* PHP 5.3.29, PHP 5.4.31, PHP 5.5.16, PHP 5.6.0, Filezilla Server 0.9.47 (#34)
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
* PEAR 1.9.5
* Check SSL port on homepage only if main port available
* Adding Imagick extension for PHP 5.5.x and 5.6.x
* SVN and Git repositories configuration in repos.dat file
* Bug with SVN and Git to seek repositories
* Xdebug 2.2.5
* Remove PHP 5.6.a3
* Add log separator
* Bug timezone

## 1.0.11 (2014/08/14)

* Apache 2.4 not working (SSL issue) (#66)
* Add option to check SSL port (#65)
* Localhost SSL shortcut on menu (#64)
* Change Xlight with Filezilla FTP Server (#63)
* Bug checking latest release from Sourceforge (#62)
* Add item menu to create SSL certificate (#60)
* Error when XLight service start (#47)
* Loading window while checking port
* Filezilla started at launch
* FTPS using Explicit SSL/TLS on Filezilla
* Webgrind conf problem
* Remove localhost SSL certificate from package
* SSL status displayed on home page for Apache and Filezilla

## 1.0.10 (2014/08/08)

* Htaccess bug on homepage (#59)
* Add HTTPS support (#58)
* SSL requests are written in `*_sslreq.log` files
* Edit vhost window title bug
* Localhost apache logs are moved in `apache_*.log` files

## 1.0.9 (2014/07/14)

* Bug check MySQL and MariaDB ports (#56)
* Add Adminer application (#55)
* Failed to load PHP extensions from config file (#53)
* Restart all services restarts Neard (#52)
* Display www directories on homepage (#50)
* Bug switching php extension or apache module (#49)
* PHP 5.4.30, PHP 5.5.14 (#34)
* Remove Bitcoin donation
* Remove comments to manage services via Aestan or Win32Service ext

## 1.0.8 (2014/07/06)

* Bug switching php extension or apache module (#49)
* Port other than 80 (#48)
* XLight service disable by default

## 1.0.7 (2014/05/20)

* Restart item menu (#46)
* Node.js - npm fail with any command (#44)
* Manage and disable a service (#43)

## 1.0.6 (2014/04/18)

* Error window when switching status (online/offline) (#40)
* Refresh aliases and vhosts when switching status (#39)
* Localhost menu item wrong redirect bug (#38)
* Launch on startup item (#29)
* Check version on homepage and startup (#8)
* Clean some var_dump
* Bug with depreciated functions on PHP 5.6 (homepage)
* Bug with Gitlist

## 1.0.5 (2014/04/06)

* Move Apache rewrite logs (#37)
* Move PHP config file to his root folder (#36)
* Path bug in Node.js configuration file (#35)
* Apache 2.2.27, Apache 2.4.9, PHP 5.3.28, PHP 5.4.26, PHP 5.5.10, PHP 5.6.a3 (#34)
* Error window when refresh Git or SVN repo (#33)
* Port 21 by default for FTP server
* Port 3306 by default for MySQL
* Port 3307 by default for MariaDB
* Add cgi-bin path (to use via http://localhost/cgi-bin)
* Add mysqli.allow_persistent PHP setting
* Review batch exec output redirect
* Check PHP setting exists
* Remove rewrite log when creating virtual host

## 1.0.4 (2014/03/17)

* Add Xlight FTP Server (#30)
* Add Swedish language (#28)
* Create Microsoft Visual C++ Runtime libraries package (#27)
* Add Sublime Text app to display debug infos (#26)
* Add debug settings in MySQL and MariaDB menu (#25)
* If 'port X already used' error occurred, display the process using this port (#20)
* Create patches (#18)
* Review services launch process and increase pending timeout to 10 seconds
* Edit some gitignore
* Add clear temp folder item (right menu)
* Move VBS and Batch processes
* Review loader
* Review switch online/offline
* Add batch and vbs logs files (neard-batch.log and neard-vbs.log)

## 1.0.3 (2014/03/10)

* Add debug settings in Apache menu (#22)
* Create vhosts add and edit item menu (#21)
* Add menu to change logs verbose (#19)
* Apache service error more verbose on startup (#20)
* Fix process status error (299)
* Fix destroy window error
* Adding default virtual host for localhost in httpd.conf

## 1.0.2 (2014/03/01)

* Fix error in Gitlist if no repositories (#15)
* Fix console won't launch (#14)
* Fix wrong version displayed on splash screen (#13)
* Loading window when reload (#6)
* Display hosts on homepage (#5)
* Restart service when change port (#4)
* Fix Git console error (#3)
* Fix errors on Windows XP (#16, #17)

## 1.0.1 (2014/02/26)

* Need SETX command to refresh environment variables (#12)
* Remove ENVDEV
* Adding EXEC action to differ exit/restart (#11)
* Adding hostname config var (auto completed during startup)
* Remove php_zip extension from core (7zip instead)
* SVN 1.7.14
* Change services default port
* Bug when changing port

## 1.0.0 (2014/02/25)

* Initial version
