[![GitHub release](https://img.shields.io/github/release/crazy-max/neard.svg?style=flat-square)](https://github.com/crazy-max/neard/releases/latest)
![Total downloads](https://img.shields.io/github/downloads/crazy-max/neard/total.svg?style=flat-square)
[![Donate Paypal](https://img.shields.io/badge/donate-paypal-blue.svg?style=flat-square)](https://www.paypal.me/crazyws)

# Neard

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [About](#about)
  - [Binaries](#binaries)
  - [Applications](#applications)
  - [Tools](#tools)
- [Download](#download)
  - [Binaries, tools, applications](#binaries-tools-applications)
- [Installation](#installation)
- [Configuration](#configuration)
- [Upgrade](#upgrade)
- [Usage](#usage)
- [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## About

**Neard** is a portable WAMP software stack involving useful binaries, tools and applications for [your web development](../../wiki/Screenshots).<br />
It is open for everyone to contribute. You can contribute to [binaries, tools, applications](../../wiki/Contribute) and [translations](../../wiki/Translations) too.<br />
To be notified of new releases, Star the project or subscribe to this [Atom feed](https://github.com/crazy-max/neard/releases.atom).<br />
If you've got a problem do not forget to read the [Reporting an issue Wiki page](../../wiki/Reporting-an-issue).

![](../../wiki/screenshots/neard-menu1-20161018.png)  ![](../../wiki/screenshots/neard-menu2-20160505.png)

### Binaries

* **[Apache](../../wiki/binApache)**, the world's most used web server software.
* **[Filezilla](../../wiki/binFilezilla)**, a FTP server application.
* **[MailHog](../../wiki/binMailHog)**, a Web and API based SMTP testing.
* **[MariaDB](../../wiki/binMariaDB)**, a community-developed fork of the MySQL relational database management system.
* **[Memcached](../../wiki/binMemcached)**, a distributed memory object caching system.
* **[MySQL](../../wiki/binMySQL)**, an open-source relational database management system.
* **[Node.js](../../wiki/binNode.js)**, an open-source, cross-platform runtime environment for developing server-side web applications.
* **[PHP](../../wiki/binPHP)**, a server-side scripting language designed for web development including PEAR and extra extensions.
* **[PostgreSQL](../../wiki/binPostgreSQL)**, an object-relational database management system (ORDBMS) with an emphasis on extensibility and standards-compliance.
* **[SVN](../../wiki/binSVN)**, a software versioning and revision control system.

### Applications

* **[Adminer](../../wiki/appAdminer)**, (formerly phpMinAdmin) is a full-featured database management tool written in PHP.
* **[GitList](../../wiki/appGitList)**, an elegant and modern git repository viewer.
* **[phpMemAdmin](../../wiki/appPhpMemAdmin)**, to handle the administration of Memcached over the web.
* **[phpMyAdmin](../../wiki/appPhpMyAdmin)**, to handle the administration of MySQL and MariaDB over the Web.
* **[phpPgAdmin](../../wiki/appPhpPgAdmin)**, a web-based administration tool for PostgreSQL.
* **[Webgrind](../../wiki/appWebgrind)**, the Xdebug Profiling Web Frontend in PHP.
* **[WebSVN](../../wiki/appWebSVN)**, an Online subversion repository browser.

### Tools

* **[Composer](../../wiki/toolComposer)**, Dependency Manager for PHP.
* **[Console](../../wiki/toolConsole)**, a multi command prompt with TCC/LE and ANSICON.
* **[Drush](../../wiki/toolDrush)**, a command line shell and scripting interface for Drupal.
* **[Git](../../wiki/toolGit)**, a widely used version control system for software development.
* **[HostsEditor](../../wiki/toolHostsEditor)**, a small application for editing windows Hosts file.
* **[ImageMagick](../../wiki/toolImageMagick)**, a free and open-source software suite to manipulate image files.
* **[Notepad2-mod](../../wiki/toolNotepad2-mod)**, a fork of Notepad2 text editor with syntax highlighting.
* **[PhpMetrics](../../wiki/toolPhpMetrics)**, gives metrics about PHP project and classes.
* **[PHPUnit](../../wiki/toolPHPUnit)**, a programmer-oriented testing framework for PHP.
* **[Python](../../wiki/toolPython)**, a widely used high-level, general-purpose, interpreted, dynamic programming language.
* **[Ruby](../../wiki/toolRuby)**, a dynamic, reflective, object-oriented, general-purpose programming language.
* **[WP-CLI](../../wiki/toolWP-CLI)**, a command line interface for WordPress.
* **[XDebugClient](../../wiki/toolXDebugClient)**, a simple frontend for XDebug.

And many other features.

## Download

First, read the [Requirements Wiki page](../../wiki/Requirements).<br />
Then go to the [releases page](https://github.com/crazy-max/neard/releases) and download one of the available archive :
* `neard-x.x.x.7z`
* `neard-x.x.x.zip`

### Binaries, tools, applications

Neard offers several versions of the various binaries, applications and tools for download on github subrepositories.<br />
You can find the download links on the [Wiki](../../wiki).

## Installation

Use a file archiver that supports [7z format](http://www.7-zip.org/7z.html) like [7zip](http://www.7-zip.org/) and extract the archive where you want.

## Configuration

Before starting Neard, edit the configuration file `neard.conf` :
* **lang** - Language (see `neard\core\langs` folder for a complete list). Default : `english`
* **timezone** - The default timezone used by all date/time functions. Default : `"Europe/Paris"`
* **notepad** - The editor while opening files. Default : `"notepad.exe"`
* **logsVerbose** - Control the log output verbose (0=simple, 1=report, 2=debug, 3=trace). Default : `0`
* **purgeLogsOnStartup** - Purge logs from Neard logs folder (0=false, 1= true). Default `0`
* **scriptsTimeout** - The default timeout when VBS/Batch are executed. May vary depending on your system. Default : `120`
* **scriptsDelete** - Delete temporary scripts in core/tmp folder on startup (0=false, 1= true). Default : `1`

## Upgrade

All instructions to upgrade from a previous release are added in the [CHANGELOG.md](https://github.com/crazy-max/neard/blob/master/CHANGELOG.md).

## Usage

Launch `neard.exe`.

## License

LGPL-3.0. See `LICENSE` for more details.<br />
Icon credit to [David Vignoni](http://www.icon-king.com/).
