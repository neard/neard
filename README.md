# Neard

Neard is an Integrated Web Development Tools environment combining :

### Major binaries

* **Apache2**
* **PHP**
* **MySQL**
* **MariaDB**
* **Node.js**

### Applications

* **GitList**, an elegant and modern git repository viewer.
* **phpMyAdmin** to handle the administration of MySQL over the Web.
* **Webgrind**, the Xdebug Profiling Web Frontend in PHP.
* **WebSVN**, an Online subversion repository browser.

### Tools

* A command prompt with **Console2** and **TCC/LE**.
* **Git** support with **PortableGit**.
* **Imagick** binaries.
* **SVN** binaries.
* **XDebugClient**, a simple frontend for XDebug.

And many other features.

## Requirements

* [WSH (Windows Script Host)](http://support.microsoft.com/kb/232211) : Open a command prompt and type ``wscript`` to check.
* Be [Admin user](http://windows.microsoft.com/en-US/windows7/How-do-I-log-on-as-an-administrator).

## Installation

### Download

Neard is [available on SourceForge](https://sourceforge.net/projects/neard/).

#### Latest releases

* [Neard 1.0.0](https://sourceforge.net/projects/neard/files/Releases/1.0.0/neard-1.0.0-32bits.zip/download)

#### Testing releases

* [Neard Testing 201402251352](https://sourceforge.net/projects/neard/files/Testing/neard-testing-201402251352.zip/download)

#### Archives

[Archives](https://sourceforge.net/projects/neard/files/Releases/) are available on SourceForge.

### Addons

[Addons](https://sourceforge.net/projects/neard/files/Addons/) are available on SourceForge.

#### Apache

* [Apache 2.4.4](https://sourceforge.net/projects/neard/files/Addons/apache/2.4.4/neard-apache-2.4.4.zip/download)

#### PHP

* [PHP 5.4.16](https://sourceforge.net/projects/neard/files/Addons/php/5.4.16/neard-php-5.4.16.zip/download)

#### MySQL

* [MySQL 5.6.12](https://sourceforge.net/projects/neard/files/Addons/mysql/5.6.12/neard-mysql-5.6.12.zip/download)

#### MariaDB

* [MariaDB 10.0.6](https://sourceforge.net/projects/neard/files/Addons/mariadb/10.0.6/neard-mariadb-10.0.6.zip/download)

#### Node.js

* [Node.js 0.11.9](https://sourceforge.net/projects/neard/files/Addons/nodejs/0.11.9/neard-nodejs-0.11.9.zip/download)

### Configuration

* Just extract the ZIP file where you want.

Edit the configuration file ``neard.conf`` :
* **appLogsVerbose** - Control the debug output (0=simple, 1=report, 2=debug). Default : ``0``
* **appPurgeLogsOnStartup** - Purge logs from Neard logs folder (0=false, 1= true). Default ``0``;
* **lang** - Language (see core\langs folder for a complete list). Default : ``english``
* **timezone** - The default timezone used by all date/time functions. Default : ``"Europe/Paris"``
* **notepad** - The editor while opening files. Default : ``"notepad.exe"``

### Usage

Launch ``neard.exe``.

## Reporting an issue

Before [reporting an issue](https://github.com/crazy-max/neard/issues), please :
* Tell me what is your operating system and platform (eg. Windows 7 64-bits).
* Tell me your Neard version (eg. 1.0.0).
* Change this variable in the ``neard.conf`` file ``appLogsVerbose = 2``.
* Zip the ``logs`` folder and a screenshot of your issue and attached the archive file to the issue.

## License

LGPL. See ``LICENSE`` for more details.

## Screenshots

### Startup screen
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-startup.png)

### Menu Left and Right
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-menu1.png)
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-menu2.png)

### Homepage
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-homepage.png)

### Console
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-console.png)

### Change browser
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-change-browser.png)

### Apache add alias
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-add-alias.png)

### Apache change port
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-change-port.png)

### Apache check port
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-check-port.png)

### About
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-about.png)
