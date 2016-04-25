# Neard

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [About](#about)
  - [Binaries](#binaries)
  - [Applications](#applications)
  - [Tools](#tools)
- [Download](#download)
  - [Additionals downloads (binaries, tools, applications)](#additionals-downloads-binaries-tools-applications)
  - [Get notified on new updates / releases](#get-notified-on-new-updates--releases)
- [Installation and configuration](#installation-and-configuration)
- [Usage](#usage)
- [Changelog](#changelog)
- [Found a bug?](#found-a-bug)
- [Contribute](#contribute)
- [Donate](#donate)
- [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## About

Neard is a portable WAMP software stack involving :

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
* **HostsEditor**, a small application for editing windows Hosts file.
* **ImageMagick**, a free and open-source software suite for displaying, converting, and editing raster image and vector image files.
* **PHPUnit**, a programmer-oriented testing framework for PHP. It is an instance of the xUnit architecture for unit testing frameworks.
* **SVN**, a software versioning and revision control system.
* **XDebugClient**, a simple frontend for XDebug.

And many other features.

## Download

Before installing Neard, read the [Requirements Wiki page](https://github.com/crazy-max/neard/wiki/Requirements).

[![Neard 1.0.18](https://img.shields.io/badge/download-neard%201.0.18-brightgreen.svg)](https://github.com/crazy-max/neard/releases/download/v1.0.18/neard-1.0.18.7z)

### Additionals downloads (binaries, tools, applications)

Neard offers several versions of the various binaries, applications and tools for download on github subrepositories.<br />
You can find the download links on the [Wiki](https://github.com/crazy-max/neard/wiki).

### Get notified on new updates / releases

To get notified about new updates :
* Click on [Watch](https://github.com/crazy-max/neard/subscription) button on Neard repository. You can watch Neard subrepositories too to follow bundles updates if you want.
* If you just want to follow releases, star the project and subscribe to [Sibbell notifications platform](https://sibbell.com).

## Installation and configuration

Use a file archiver that supports [7z format](http://www.7-zip.org/7z.html) like [7zip](http://www.7-zip.org/) and extract the archive where you want.<br />
Then edit the configuration file `neard.conf` :
* **lang** - Language (see `neard\core\langs` folder for a complete list). Default : `english`
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

See [Reporting an issue Wiki page](https://github.com/crazy-max/neard/wiki/Reporting-an-issue).

## Contribute

Neard is open for everyone to contribute. Please give me some feedback and join the development!<br />
If you want to translate Neard in your language, read the [Translations Wiki page](https://github.com/crazy-max/neard/wiki/Translations).

## Donate

I have put in a lot of time to this project and appreciate donations.<br />
You can use the [Paypal donate link](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=4H86AJZ6M865A&item_name=Neard&no_note=0&cn=Message%20%3a&no_shipping=1&rm=1&return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&cancel_return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted).

Thank you to everyone who has donated, it is much appreciated.

## License

LGPL. See `LICENSE` for more details.
