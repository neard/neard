# Changelog

## 1.4 (2017/12/30)

* Manage Yarn cache and global path (Issue #354)
* Yarn config not updated (Issue #353)
* Put Composer cache-dir to neard/tmp/composer (Issue neard/neard#346)
* Add global composer vendor/bin in PATH (Issue #344)
* Move neard repositories to its own organization (Issue #339)
* Add ngrok (Issue #334)
* Remove HostsEditor module and add in core instead (Issue #329)
* Create separated logs (stdout / stderr) for NSSM services (Issue #326)
* ImageMagick `convert.exe` is overridden by `convert.exe` from Windows (Issue #323)
* Put NEARD_BINS at the beginning of PATH (Issue #323)
* Remove ImageMagick module (Issue #322)
* Add a deps folder to load additional dependencies for PHP (Issue #321)
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
* Update Apache to 2.4.29
* Update Composer to 1.5.6
* Update Console to 2.00.148.4
* Update Drush to 8.1.15
* Update Ghostscript to 9.22
* Update Git to 2.15.1.2
* Update Gitlist to 0.6.0
* Update Node.js to 8.9.3
* Update PHP to 7.1.12
* Update PHPUnit to 6.5.5
* Update Yarn to 1.3.2

## 1.3 (2017/09/01)

* Debug variables empty for MySQL and MariaDB (Issue #315)
* Remove Notepad2-mod (Issue #314)
* Update modules links (Issue #313)
* Add PHP 7.2 compatibility (Issue #311)
* Upgrade to OpenSSL 1.1.0f (Issue #309)
* Firefox & Chrome require the subjectAltName (SAN) X.509 extension for certificates (Issue #308)
* Paths are not updated while switching version (Issue #301)
* Exclude php_xdebug from PHP extensions (Issue #298)
* Check Console shell (Issue #291)
* Error while launching PostgreSQL console (Issue #290)
* Error while launching SVN console (Issue #289)
* New Console with Clink and GnuWin32 CoreUtils (Issues #287 #288)
* Update modules to stable releases (Issue #264)
* Wrong file size on version check window (Issue #251)
* Add Ghostscript (Issue #220)
* Add Yarn (Issue #157)
* Add Perl (Issue #155)
* Exclude OPCache from extensions list
* Error while refreshing Apache SSL conf
* Missing return statement
* Unused local variables
* Change some access modifiers
* 7z release format only

> :warning: Modules have been updated to the latest stable release (Issue #264). For Windows XP users, please read the [Windows XP limitation](http://neard.io/doc/faq/#windows-xp-limitation) section in the FAQ to download the right release.

## 1.2 (2017/04/20)

* Review versioning style (Issue #247)
* Blank page for phpMyAdmin and Adminer if MariaDB or MySQL disabled (Issue #243)
* Services cannot start / paused on Windows 10 Creators Update (Issue #242)
* Display module release in logs (Issue #209)
* Add ability to customize the env. PATH variable for NSSM services (Issue #233)
* Improve phpinfo output (Issue #230)
* Improve files scan on startup (Issue #229)
* Missing check enable for SVN (Issue #219)
* Service uninstallation error (Issue #218)
* Create checksum for downloads (Issue #211)
* MySQL/MariaDB console don't ask for password if not empty (Issue #206)
* Localhost menu shortcuts don't reflect Apache port change (Issue #205)
* Put online / offline does not work for virtual hosts and aliases (Issue #202)
* Add MongoDB (Issue #166)
* Update Hungarian language (Issue #112)
* Switch to phpMemAdmin 0.1.0.41 as default version
* Use AppVeyor instead of Travis

## 1.0.22 (2016/12/18)

* Cannot switch to trace verbosity (Issue #198)
* Apache PATH env mixed with LocalSystem env (Issue #193)
* Dissociate Apache and SVN (Issue #168)
* Use latest OpenSSL release to generate SSL certificate (Issue #167)
* Forgetting Memcached switch version impl (Issue #164)
* Port undefined error for MailHog service (Issue #163)
* 30% CPU usage for 2 minutes while generating SSL certificate (Issue #161)
* Can't switch PostgreSQL version (Issue #160)
* Bug while enable binary (Issue #156)
* Add Ruby tool (Issue #154)
* Add Python tool (Issue #98)
* Add Perl, Vbs and Python CGI scripts examples
* Bug while setting version in neard.conf
* Downgrade default Filezilla release to 0.9.42 (XP compatibility)
* Implement enable on tools and apps
* Integration of Codacy and Travis

## 1.0.21 (2016/10/23)

* Replace launchStartup with enable for binaries (Issue #153)
* Better way to manage modules (Issue #153)
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

## 1.0.20 (2016/06/23)

* Bug with homepage queries (Issue #139)
* Add Administration button for FileZilla Server Interface (Issue #138)
* Add download links for binaries / tools / apps on homepage
* Migrate from Notepad2 to Notepad2-mod
* Update Composer to 1.1.2
* Update Git to 2.9.0
* Update ImageMagick to 6.9.3-10
* Update PHPUnit to 4.8.26
* Update Adminer to 4.2.5
* Update phpMyAdmin to 4p3
* Update Webgrind to 1.3.1

## 1.0.19 (2016/05/05)

* Bug while loading latest changelog from homepage (Issue #135)
* Add MailHog binary : 0.2.0-r1 (Issue #32, Issue #119)
* Add menu item to change MySQL and MariaDB root password (Issue #134)
* Add PhpMetrics tool : 1.0.1-r1, 1.1.1-r1, 1.9.2-r1 (Issue #121)
* Add WP-CLI tool : 0.12.1-r1, 0.13.0-r1, 0.14.1-r1, 0.15.1-r1, 0.16.0-r1, 0.17.2-r1, 0.18.1-r1, 0.19.3-r1, 0.20.4-r1, 0.21.1-r1, 0.22.0-r1, 0.23.1-r1 (Issue #121)
* Add Drush tool : 5.9.0-r1, 6.7.0-r1, 7.3.0-r1, 8.1.0-r1 (Issue #121)
* Add PHPUnit tool : 4.8.24-r1, 5.3.2-r1 (Issue #121)
* Create Wiki documentation (Issue #31)
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

* Bug while checking port on MariaDB / MySQL (Issue #130)
* Add HostsEditor tool for editing windows Hosts file (Issue #129)
* Load homepage with ajax requests (Issue #128)
* Generate self-signed certificates with SHA256 digest (Issue #127)
* Move SetEnv tool to core (Issue #126)
* Change Notepad2 temporary folder location (Issue #125)
* Update SVN Apache module path on fly (Issue #124)
* Update config files on startup (apps, bins, tools) (Issue #123)
* Skip npm-cache clear on startup (Issue #122)
* Always need a restart to run Neard (Issue #118)
* Check valid domain name when adding vhost (Issue #117)
* Add aliases doesn't work with quote or accented letters or special letters (Issue #116)
* Add Hungarian language (Issue #112)
* Some chars are not handled by Aestan Tray Menu (Issue #112)
* New Apache release : 2.4.20-r2 (Issue #119)
* New Filezilla Server release : 0.9.56.1-r2 (Issue #119)
* New MariaDB release : 5.5.48-r2, 10.0.24-r2, 10.1.13-r2 (Issue #119)
* New MySQL release : 5.5.49-r2, 5.6.30-r2 (Issue #119)
* New Node.js release : 0.12.13-r3, 4.4.3-r3, 5.10.1-r3 (Issue #119)
* New PHP release : 5.5.34-r6, 5.6.20-36, 7.0.5-r6 (Issue #119)
* Add Ant build (Issue #54)
* Upgrade Composer module to version 1.0.0 (2016/04/05)
* Upgrade Console tool to pack 2 (Console 2.00.148 and TCC/LE 14.0.0.9)
* Upgrade Git module to version 2.8.1
* Upgrade SVN module to version 1.7.22
* Upgrade XDebugClient module to version 1.0b5
* Upgrade Adminer module to version 4.2.4
* Upgrade Gitlist module to version 0.5.0
* Upgrade phpMyAdmin module to pack 2 (4.0.10.15, 4.4.15.5, 4.6.0)
* Upgrade Webgrind module to version 1.1
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
* Upgrade phpMyAdmin to 4.0.10.12 / 4.4.15.2 / 4.5.3.1 (Issue #107)
* Add manifest inside neard.exe

## 1.0.16 (2015/12/13)

* Add PHP 7 support (Issue #103)
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

* Error while switching php parameters (Issue #97)
* Zend extension not recognized on PHP 5.2.x (Issue #96)
* Add NodeJS to PATH (Issue #95)
* Increase input length (Issue #91)
* Add Composer in Neard tools (Issue #80)
* Addon PHP : 5.6.14 (Issue #34)

## 1.0.14 (2015/06/09)

* Bug while archiving logs (Issue #89)
* Remove purge logs feature (Issue #88)
* System Idle Process block services (Issue #87)
* Increase timeout SSL certificate (Issue #86)
* Addons Apache : 2.2.29, 2.4.12 (Issue #34)
* Addons PHP : 5.2.17, 5.4.40, 5.5.24, 5.6.8 (Issue #34)
* Addon Filezilla Server : 0.9.52.1 (Issue #34)
* Addons MySQL : 5.5.44, 5.6.25 (Issue #34)
* Addons MariaDB : 5.5.43, 10.0.19 (Issue #34)
* Addons Node.js : 0.10.38, 0.11.16, 0.12.4 (Issue #34)
* Suitable for PHP 5.2

## 1.0.13 (2015/04/20)

* Launch on startup fails sometimes (Issue #83)
* Restarting Neard doesn't work (Issue #82)
* Installing Apache addon need manual configuration (Issue #81)
* Bug switching Online/Offline (Issue #78)
* Add option to scan SVN and Git repos on Startup (Issue #75)
* Review startup screen
* Review process management
* Adding APC configuration
* Bug on local url
* Add log archives

## 1.0.12 (2014/09/28)

* Split bins, tools, apps configuration (Issue #74)
* Adding APC manager page (Issue #73)
* Adding PHP extensions version (if available) on homepage (Issue #72)
* Add logs menu (Issue #71)
* Launch Neard as an explorer.exe child process (Issue #70)
* Bug with APC configuration (Issue #57, Issue #67)
* Addons : PHP 5.3.29, PHP 5.4.31, PHP 5.5.16, PHP 5.6.0, Filezilla Server 0.9.47 (Issue #34)
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

* Apache 2.4 not working (SSL issue) (Issue #66)
* Add option to check SSL port (Issue #65)
* Localhost SSL shortcut on menu (Issue #64)
* Change Xlight with Filezilla FTP Server (Issue #63)
* Bug checking latest release from Sourceforge (Issue #62)
* Add item menu to create SSL certificate (Issue #60)
* Error when XLight service start (Issue #47)
* Loading window while checking port
* Filezilla started at launch
* FTPS using Explicit SSL/TLS on Filezilla
* Webgrind conf problem
* Remove localhost SSL certificate from package
* SSL status displayed on home page for Apache and Filezilla

## 1.0.10 (2014/08/08)

* Htaccess bug on homepage (Issue #59)
* Add HTTPS support (Issue #58)
* SSL requests are written in `*_sslreq.log` files
* Edit vhost window title bug
* Localhost apache logs are moved in `apache_*.log` files

## 1.0.9 (2014/07/14)

* Bug check MySQL and MariaDB ports (Issue #56)
* Add Adminer application (Issue #55)
* Failed to load PHP extensions from config file (Issue #53)
* Restart all services restarts Neard (Issue #52)
* Display www directories on homepage (Issue #50)
* Bug switching php extension or apache module (Issue #49)
* Addons : PHP 5.4.30, PHP 5.5.14 (Issue #34)
* Remove Bitcoin donation
* Remove comments to manage services via Aestan or Win32Service ext

## 1.0.8 (2014/07/06)

* Bug switching php extension or apache module (Issue #49)
* Port other than 80 issue (Issue #48)
* XLight service disable by default

## 1.0.7 (2014/05/20)

* Restart item menu (Issue #46)
* Node.js - npm fail with any command (Issue #44)
* Manage and disable a service (Issue #43)

## 1.0.6 (2014/04/18)

* Error window when switching status (online/offline) (Issue #40)
* Refresh aliases and vhosts when switching status (Issue #39)
* Localhost menu item wrong redirect bug (Issue #38)
* Launch on startup item (Issue #29)
* Check version on homepage and startup (Issue #8)
* Clean some var_dump
* Bug with depreciated functions on PHP 5.6 (homepage)
* Bug with Gitlist

## 1.0.5 (2014/04/06)

* Move Apache rewrite logs (Issue #37)
* Move PHP config file to his root folder (Issue #36)
* Path bug in Node.js configuration file (Issue #35)
* Addons : Apache 2.2.27, Apache 2.4.9, PHP 5.3.28, PHP 5.4.26, PHP 5.5.10, PHP 5.6.a3 (Issue #34)
* Error window when refresh Git or SVN repo (Issue #33)
* Port 21 by default for FTP server
* Port 3306 by default for MySQL
* Port 3307 by default for MariaDB
* Add cgi-bin path (to use via http://localhost/cgi-bin)
* Add mysqli.allow_persistent PHP setting
* Review batch exec output redirect
* Check PHP setting exists
* Remove rewrite log when creating virtual host

## 1.0.4 (2014/03/17)

* Add Xlight FTP Server (Issue #30)
* Add Swedish language (Issue #28)
* Create Microsoft Visual C++ Runtime libraries package (Issue #27)
* Add Sublime Text app to display debug infos (Issue #26)
* Add debug settings in MySQL and MariaDB menu (Issue #25)
* If 'port X already used' error occurred, display the process using this port (Issue #20)
* Create patches (Issue #18)
* Review services launch process and increase pending timeout to 10 seconds
* Edit some gitignore
* Add clear temp folder item (right menu)
* Move VBS and Batch processes
* Review loader
* Review switch online/offline
* Add batch and vbs logs files (neard-batch.log and neard-vbs.log)

## 1.0.3 (2014/03/10)

* Add debug settings in Apache menu (Issue #22)
* Create vhosts add and edit item menu (Issue #21)
* Add menu to change logs verbose (Issue #19)
* Apache service error more verbose on startup (Issue #20)
* Fix process status error (299)
* Fix destroy window error
* Adding default virtual host for localhost in httpd.conf

## 1.0.2 (2014/03/01)

* Fix error in Gitlist if no repositories (Issue #15)
* Fix console won't launch (Issue #14)
* Fix wrong version displayed on splash screen (Issue #13)
* Loading window when reload (Issue #6)
* Display hosts on homepage (Issue #5)
* Restart service when change port (Issue #4)
* Fix Git console error (Issue #3)
* Fix errors on Windows XP (Issue #16, Issue #17)

## 1.0.1 (2014/02/26)

* Need SETX command to refresh environment variables (Issue #12)
* Remove ENVDEV
* Adding EXEC action to differ exit/restart (Issue #11)
* Adding hostname config var (auto completed during startup)
* Remove php_zip extension from core (7zip instead)
* Update SVN to 1.7.14
* Change services default port
* Bug when changing port

## 1.0.0 (2014/02/25)

* Initial version
