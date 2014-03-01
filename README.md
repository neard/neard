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
* [SETX](http://technet.microsoft.com/en-us/library/cc755104.aspx) : Open a command prompt and type ``setx /?`` to check.
* Be [Admin user](http://windows.microsoft.com/en-US/windows7/How-do-I-log-on-as-an-administrator).

### Windows XP

* Download and install [Visual C++ 2008 SP1 Redistributable Package (x86)](http://www.microsoft.com/en-us/download/details.aspx?id=5582).
* Download and install [Visual C++ 2010 Redistributable Package (x86)](https://www.microsoft.com/en-us/download/details.aspx?id=8328).
* Download and install [Windows Support Tools](http://www.microsoft.com/en-us/download/details.aspx?id=18546).

## Download

Neard is [available on SourceForge](https://sourceforge.net/projects/neard/) :

* Latest release : [Neard 1.0.2](https://sourceforge.net/projects/neard/files/Releases/1.0.2/neard-1.0.2.zip/download) (2014/03/01)
* [Archives](https://sourceforge.net/projects/neard/files/Releases/).
* [Testing](https://sourceforge.net/projects/neard/files/Testing/).
* [Addons](https://sourceforge.net/projects/neard/files/Addons/).

## Configuration

* Just extract the ZIP file where you want.

Edit the configuration file ``neard.conf`` :
* **appLogsVerbose** - Control the debug output (0=simple, 1=report, 2=debug). Default : ``0``
* **appPurgeLogsOnStartup** - Purge logs from Neard logs folder (0=false, 1= true). Default ``0``
* **lang** - Language (see core\langs folder for a complete list). Default : ``english``
* **timezone** - The default timezone used by all date/time functions. Default : ``"Europe/Paris"``
* **notepad** - The editor while opening files. Default : ``"notepad.exe"``

## Usage

Launch ``neard.exe``.

## Changelog

See ``CHANGELOG.md``.

## Reporting an issue

Before [reporting an issue](https://github.com/crazy-max/neard/issues), please :
* Tell me what is your operating system and platform (eg. Windows 7 64-bits).
* Tell me your Neard version (eg. 1.0.0).
* Change this variable in the ``neard.conf`` file ``appLogsVerbose = 2``.
* Zip the ``logs`` folder and a screenshot of your issue and attached the archive file to the issue.

## Donate

I have put in a lot of time to this project and appreciate donations.
If you prefer to donate via paypal you can use the [donate link](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=4H86AJZ6M865A&item_name=Neard&no_note=0&cn=Message%20%3a&no_shipping=1&rm=1&return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&cancel_return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted).
Or with bitcoin you can send your donations to [1BdhK62JY2xQKXjmvLLA8Dpbit1uJ5JkrC](bitcoin:1BdhK62JY2xQKXjmvLLA8Dpbit1uJ5JkrC?label=Neard%20Donations&message=Contribution%20to%20Neard).<br /><br />
Thank you to everyone who has donated, it is much appreciated.

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
