# Neard

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->

- [About](#about)
  - [Binaries](#binaries)
  - [Applications](#applications)
  - [Tools](#tools)
- [Requirements](#requirements)
  - [Windows XP](#windows-xp)
- [Download](#download)
  - [Subrepositories](#subrepositories)
  - [Get notified on new updates / releases](#get-notified-on-new-updates--releases)
- [Configuration](#configuration)
- [Usage](#usage)
- [Changelog](#changelog)
- [Found a bug?](#found-a-bug)
- [Donate](#donate)
- [Contribute](#contribute)
  - [Translations](#translations)
- [License](#license)
- [Screenshots](#screenshots)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## About

Neard is an Integrated Web Development Tools environment combining :

### Binaries

* **Apache**, the world's most used web server software.
* **Filezilla**, a FTP server application.
* **MariaDB**, a community-developed fork of the MySQL relational database management system intended to remain free under the GNU GPL.
* **MySQL**, an open-source relational database management system.
* **Node.js**, an open-source, cross-platform runtime environment for developing server-side web applications.
* **PHP**, a server-side scripting language designed for web development including PEAR and extra extensions.

### Applications

* **Adminer** (formerly phpMinAdmin) is a full-featured database management tool written in PHP.
* **GitList**, an elegant and modern git repository viewer.
* **phpMyAdmin** to handle the administration of MySQL and MariaDB over the Web.
* **Webgrind**, the Xdebug Profiling Web Frontend in PHP.
* **WebSVN**, an Online subversion repository browser.

### Tools

* **Composer**, Dependency Manager for PHP.
* **Console2** + **TCC/LE**, Multi command prompt including PEAR, MySQL / MariaDB, Git, SVN, Node.js and Composer views.
* **Git**, a widely used version control system for software development.
* **HostsEditor**, is a small application for editing windows Hosts file.
* **ImageMagick**, a free and open-source software suite for displaying, converting, and editing raster image and vector image files.
* **SVN**, a software versioning and revision control system.
* **XDebugClient**, a simple frontend for XDebug.

And many other features.

## Requirements

* [WSH (Windows Script Host)](http://support.microsoft.com/kb/232211) : Open a command prompt and type `wscript` to check.
* [SETX](http://technet.microsoft.com/en-us/library/cc755104.aspx) : Open a command prompt and type `setx /?` to check.
* Be [Admin user](http://windows.microsoft.com/en-US/windows7/How-do-I-log-on-as-an-administrator).
* Download and install the latests [Neard Visual C++ Redistributables Package](https://github.com/crazy-max/neard-misc#visual-c-redistributables-package).

### Windows XP

* Download and install [Windows Support Tools](http://www.microsoft.com/en-us/download/details.aspx?id=18546).

## Download

[![Neard 1.0.18](https://img.shields.io/badge/download-neard%201.0.18-brightgreen.svg)](https://github.com/crazy-max/neard/releases/download/v1.0.18/neard-1.0.18.7z)

### Subrepositories

Neard offers several versions of the various binaries, applications and tools for download on github subrepositories.

#### Binaries repos

* Neard Apache repository : [neard-bin-apache](https://github.com/crazy-max/neard-bin-apache)
* Neard Filezilla Server repository : [neard-bin-filezilla](https://github.com/crazy-max/neard-bin-filezilla)
* Neard MariaDB repository : [neard-bin-mariadb](https://github.com/crazy-max/neard-bin-mariadb)
* Neard MySQL repository : [neard-bin-mysql](https://github.com/crazy-max/neard-bin-mysql)
* Neard Node.js repository : [neard-bin-nodejs](https://github.com/crazy-max/neard-bin-nodejs)
* Neard PHP repository : [neard-bin-php](https://github.com/crazy-max/neard-bin-php)

#### Tools repos

* Neard Composer repository : [neard-tool-composer](https://github.com/crazy-max/neard-tool-composer)
* Neard Console repository : [neard-tool-console](https://github.com/crazy-max/neard-tool-console)
* Neard Git repository : [neard-tool-git](https://github.com/crazy-max/neard-tool-git)
* Neard HostsEditor repository : [neard-tool-hostseditor](https://github.com/crazy-max/neard-tool-hostseditor)
* Neard ImageMagick repository : [neard-tool-imagemagick](https://github.com/crazy-max/neard-tool-imagemagick)
* Neard Notepad2 repository : [neard-tool-notepad2](https://github.com/crazy-max/neard-tool-notepad2)
* Neard SVN repository : [neard-tool-svn](https://github.com/crazy-max/neard-tool-svn)

#### Applications repos

* Neard Adminer repository : [neard-app-adminer](https://github.com/crazy-max/neard-app-adminer)
* Neard Gitlist repository : [neard-app-gitlist](https://github.com/crazy-max/neard-app-gitlist)
* Neard phpMyAdmin repository : [neard-app-phpmyadmin](https://github.com/crazy-max/neard-app-phpmyadmin)
* Neard Webgrind repository : [neard-app-webgrind](https://github.com/crazy-max/neard-app-webgrind)
* Neard WebSVN repository : [neard-app-websvn](https://github.com/crazy-max/neard-app-websvn)

### Tools

### Get notified on new updates / releases

To get notified about new updates, juste click on [Watch](https://github.com/crazy-max/neard/subscription) button on Neard repository.<br />
You can watch Neard sub repositories to follow binaries updates.<br />

If you just want to follow releases, star the project and subscribe to [Sibbell notifications platform](https://sibbell.com). 

## Configuration

* Use a file archiver that supports [7z format](http://www.7-zip.org/7z.html) like [7zip](http://www.7-zip.org/) and extract the archive where you want.

Edit the configuration file `neard.conf` :
* **lang** - Language (see core\langs folder for a complete list). Default : `english`
* **timezone** - The default timezone used by all date/time functions. Default : `"Europe/Paris"`
* **notepad** - The editor while opening files. Default : `"notepad.exe"`
* **logsVerbose** - Control the debug output (0=simple, 1=report, 2=debug). Default : `0`
* **purgeLogsOnStartup** - Purge logs from Neard logs folder (0=false, 1= true). Default `0`
* **scriptsTimeout** - The default timeout when VBS/Batch are executed. May vary depending on your system. Default : `120`
* **scriptsDelete** - Delete temporary scripts in core/tmp folder (0=false, 1= true). Default : `1`

## Usage

Launch `neard.exe`.

## Changelog

See `CHANGELOG.md` and `DIFF.md`.

## Found a bug?

Please search for existing issues first and make sure to include all relevant information.
Before [reporting an issue](https://github.com/crazy-max/neard/issues), please :
* Tell me what is your operating system and platform (eg. Windows 7 64-bits).
* Tell me your Neard version (eg. 1.0.0).
* Close Neard.
* Change the `logsVerbose` variable to this value `2` in the `neard.conf` file.
* Launch Neard and reproduce your problem.
* Close Neard.
* Zip the `logs` folder, the `core/tmp` folder and a screenshot of your issue.
* Upload the zip file on a file hosting system like [Sendspace](https://www.sendspace.com/).
* Add the link of the uploaded file to the issue.

## Donate

I have put in a lot of time to this project and appreciate donations.
You can use the [Paypal donate link](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=4H86AJZ6M865A&item_name=Neard&no_note=0&cn=Message%20%3a&no_shipping=1&rm=1&return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&cancel_return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted).

Thank you to everyone who has donated, it is much appreciated.

## Contribute

Neard is open for everyone to contribute. Please give me some feedback and join the development!

### Translations

If you want to translate Neard in your language, just follow [these steps](https://github.com/crazy-max/neard/issues/28).

## License

LGPL. See `LICENSE` for more details.

## Screenshots

### Startup screen
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-startup-min-20160421.png)

### Menu Left and Right
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-menu1-20140814.png)  ![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-menu2-20140814.png)

### Homepage
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-homepage-20160421.png)

### Console
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-console-20160421.png)

### Generate SSL certificate
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-change-gen-ssl-20160421.png)  ![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-change-gen-ssl-files-20160421.png)

### Change browser
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-change-browser-20160421.png)

### Apache add alias
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-add-alias-20160421.png)

### Apache add vhost
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-add-vhost-20160421.png)

### Apache change port
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-change-port-20160421.png)

### Apache check port
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-check-port-20160421.png)

### About
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-about-20160421.png)
