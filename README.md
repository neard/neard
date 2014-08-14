# Neard

Neard is an Integrated Web Development Tools environment combining :

### Major binaries

* **Apache**
* **PHP**
* **MySQL**
* **MariaDB**
* **Node.js**
* **Filezilla**

### Applications

* **GitList**, an elegant and modern git repository viewer.
* **phpMyAdmin** to handle the administration of MySQL over the Web.
* **Webgrind**, the Xdebug Profiling Web Frontend in PHP.
* **WebSVN**, an Online subversion repository browser.
* **Adminer** (formerly phpMinAdmin) is a full-featured database management tool written in PHP.

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
* Download and install the latests [Visual C++ Redistributables Packages (x86)](https://sourceforge.net/projects/neard/files/Tools/neard-vcredists-x86.exe/download).

### Windows XP

* Download and install [Windows Support Tools](http://www.microsoft.com/en-us/download/details.aspx?id=18546).

## Download

Neard is [available on SourceForge](https://sourceforge.net/projects/neard/) :

* Latest release : [Neard 1.0.11](https://sourceforge.net/projects/neard/files/Releases/1.0.11/neard-1.0.11.zip/download) (2014/08/14)
* [Patches](https://sourceforge.net/projects/neard/files/Patches/) : to migrate from older release to the latest.
* [Addons](https://sourceforge.net/projects/neard/files/Addons/) : other versions of binaries (Apache, PHP, MYSQL, MariaDB, etc...).
* [Tools](https://sourceforge.net/projects/neard/files/Tools/) : useful tools for Neard.
* [Archives](https://sourceforge.net/projects/neard/files/Releases/) : all releases.

### RSS feed

Stay up-to-date with the latest release of Neard by subscribe to [this feed](https://sourceforge.net/api/file/index/project-id/2115941/path/Releases/mtime/desc/rss). 

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

## Get involved

Neard is open for everyone to contribute. Please give us some feedback and join the development!

## Found a bug?

Please search for existing issues first and make sure to include all relevant information.
Before [reporting an issue](https://github.com/crazy-max/neard/issues), please :
* Tell me what is your operating system and platform (eg. Windows 7 64-bits).
* Tell me your Neard version (eg. 1.0.0).
* Close Neard.
* Change this variable in the ``neard.conf`` file ``appLogsVerbose = 2``.
* Launch Neard and reproduce your problem.
* Close Neard.
* Zip the ``logs`` folder and a screenshot of your issue.
* Upload the zip file on a file hosting system like [Sendspace](https://www.sendspace.com/).
* Add the link of the uploaded file to the issue.

## Donate

I have put in a lot of time to this project and appreciate donations.
You can use the [Paypal donate link](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=4H86AJZ6M865A&item_name=Neard&no_note=0&cn=Message%20%3a&no_shipping=1&rm=1&return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&cancel_return=https%3A%2F%2Fgithub.com%2Fcrazy-max%2Fneard&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted).<br /><br />
Thank you to everyone who has donated, it is much appreciated.

## Contribute

You want to help me and participate in the development or the documentation? Just fork Neard and send me a pull request.

### Translations

If you want to translate Neard in your language, just follow [these steps](https://github.com/crazy-max/neard/issues/28).

## License

LGPL. See ``LICENSE`` for more details.

## Screenshots

### Startup screen
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-startup.png)

### Menu Left and Right
![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-menu1.png)  ![](https://raw.github.com/crazy-max/neard/master/core/resources/screenshots/neard-menu2.png)

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
