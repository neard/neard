# Neard

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->

- [About](#about)
  - [Major binaries](#major-binaries)
  - [Applications](#applications)
  - [Tools](#tools)
- [Requirements](#requirements)
  - [Windows XP](#windows-xp)
- [Download](#download)
  - [Binaries](#binaries)
  - [Get notified on new updates / releases](#get-notified-on-new-updates--releases)
- [Configuration](#configuration)
- [Usage](#usage)
- [Changelog](#changelog)
- [Get involved](#get-involved)
- [Found a bug?](#found-a-bug)
- [Donate](#donate)
- [Contribute](#contribute)
  - [Translations](#translations)
- [License](#license)
- [Screenshots](#screenshots)
  - [Startup screen](#startup-screen)
  - [Menu Left and Right](#menu-left-and-right)
  - [Homepage](#homepage)
  - [Console](#console)
  - [Change browser window](#change-browser-window)
  - [Apache add alias window](#apache-add-alias-window)
  - [Apache change port window](#apache-change-port-window)
  - [Apache check port window](#apache-check-port-window)
  - [About window](#about-window)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## About

Neard is an Integrated Web Development Tools environment combining :

### Major binaries

* **Apache**, the world's most used web server software.
* **PHP**, a server-side scripting language designed for web development.
* **MySQL**, an open-source relational database management system.
* **MariaDB**, a community-developed fork of the MySQL relational database management system intended to remain free under the GNU GPL.
* **Node.js**, an open-source, cross-platform runtime environment for developing server-side web applications.
* **Filezilla**, a FTP server application.

### Applications

* **GitList**, an elegant and modern git repository viewer.
* **phpMyAdmin** to handle the administration of MySQL and MariaDB over the Web.
* **Webgrind**, the Xdebug Profiling Web Frontend in PHP.
* **WebSVN**, an Online subversion repository browser.
* **Adminer** (formerly phpMinAdmin) is a full-featured database management tool written in PHP.

### Tools

* A command prompt with **Console2** and **TCC/LE**.
* **Git** support with **PortableGit**, a widely used version control system for software development.
* **Imagick**, a free and open-source software suite for displaying, converting, and editing raster image and vector image files.
* **SVN**, a software versioning and revision control system.
* **XDebugClient**, a simple frontend for XDebug.
* **PEAR**, PHP Extension and Application Repository.
* **Composer**, Dependency Manager for PHP.

And many other features.

## Requirements

* [WSH (Windows Script Host)](http://support.microsoft.com/kb/232211) : Open a command prompt and type ``wscript`` to check.
* [SETX](http://technet.microsoft.com/en-us/library/cc755104.aspx) : Open a command prompt and type ``setx /?`` to check.
* Be [Admin user](http://windows.microsoft.com/en-US/windows7/How-do-I-log-on-as-an-administrator).
* Download and install the latests [Neard Visual C++ Redistributables Package](https://github.com/crazy-max/neard-misc#visual-c-redistributables-package).

### Windows XP

* Download and install [Windows Support Tools](http://www.microsoft.com/en-us/download/details.aspx?id=18546).

## Download

[![Neard 1.0.17](https://img.shields.io/badge/download-neard%201.0.17%20-brightgreen.svg)](https://github.com/crazy-max/neard/releases/download/v1.0.17/neard-1.0.17.zip)

### Binaries

Neard offers several versions of the various binaries for download on other github repositories :

* Neard Apache repository : [neard-bin-apache](https://github.com/crazy-max/neard-bin-apache)
* Neard Filezilla Server repository : [neard-bin-filezilla](https://github.com/crazy-max/neard-bin-filezilla)
* Neard MariaDB repository : [neard-bin-mariadb](https://github.com/crazy-max/neard-bin-mariadb)
* Neard MySQL repository : [neard-bin-mysql](https://github.com/crazy-max/neard-bin-mysql)
* Neard Node.js repository : [neard-bin-nodejs](https://github.com/crazy-max/neard-bin-nodejs)
* Neard PHP repository : [neard-bin-php](https://github.com/crazy-max/neard-bin-php)

### Get notified on new updates / releases

To get notified about new updates, juste click on [Watch](https://github.com/crazy-max/neard/subscription) button on Neard repository.<br />
You can watch Neard sub repositories to follow binaries updates.<br />

If you just want to follow releases, star the project and subscribe to [Sibbell notifications platform](https://sibbell.com). 

## Configuration

* Use a good file archiver like [7zip](http://www.7-zip.org/) to avoid data corruption and extract the ZIP file where you want.

Edit the configuration file ``neard.conf`` :
* **lang** - Language (see core\langs folder for a complete list). Default : ``english``
* **timezone** - The default timezone used by all date/time functions. Default : ``"Europe/Paris"``
* **notepad** - The editor while opening files. Default : ``"notepad.exe"``
* **logsVerbose** - Control the debug output (0=simple, 1=report, 2=debug). Default : ``0``
* **purgeLogsOnStartup** - Purge logs from Neard logs folder (0=false, 1= true). Default ``0``
* **scriptsTimeout** - The default timeout when VBS/Batch are executed. May vary depending on your system. Default : ``120``
* **scriptsDelete** - Delete temporary scripts in core/tmp folder (0=false, 1= true). Default : ``1``

## Usage

Launch ``neard.exe``.

## Changelog

See ``CHANGELOG.md`` and ``DIFF.md``.

## Get involved

Neard is open for everyone to contribute. Please give us some feedback and join the development!

## Found a bug?

Please search for existing issues first and make sure to include all relevant information.
Before [reporting an issue](https://github.com/crazy-max/neard/issues), please :
* Tell me what is your operating system and platform (eg. Windows 7 64-bits).
* Tell me your Neard version (eg. 1.0.0).
* Close Neard.
* Change the ``logsVerbose`` variable to this value ``2`` in the ``neard.conf`` file.
* Launch Neard and reproduce your problem.
* Close Neard.
* Zip the ``logs`` folder, the ``core/tmp`` folder and a screenshot of your issue.
* Upload the zip file on a file hosting system like [Sendspace](https://www.sendspace.com/).
* Add the link of the uploaded file to the issue.

## Donate

I have put in a lot of time to this project and appreciate donations.
You can use the [Paypal donate link](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=4H86AJZ6M865A&item_name=Neard&no_note=0&cn=Message%20%3a&no_shipping=1&rm=1&return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&cancel_return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted).

Thank you to everyone who has donated, it is much appreciated.

## Contribute

You want to help me and participate in the development or the documentation? Just fork Neard and send me a pull request.

### Translations

If you want to translate Neard in your language, just follow [these steps](https://github.com/crazy-max/neard/issues/28).

## License

LGPL. See ``LICENSE`` for more details.

## Screenshots

### Startup screen
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-startup-min.png)

### Menu Left and Right
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-menu1.png)  ![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-menu2.png)

### Homepage
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-homepage.png)

### Console
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-console.png)

### Change browser window
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-change-browser.png)

### Apache add alias window
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-add-alias.png)

### Apache change port window
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-change-port.png)

### Apache check port window
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-apache-check-port.png)

### About window
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-about.png)
