## 1.0.21 > 1.0.22

* **required** : Remove file `alias\svn.conf`
* **required** : Copy folder `bin\svn`
* **required** : Remove then replace folder `core`
* **required** : Move folders inside `svnrepos` to `bin\svn\svn1.7.19\repos`
* **required** : Remove folder `svnrepos`
* **required** : Copy folder `tools\python`
* **required** : Copy folder `tools\ruby`
* **required** : Remove folder `tools\svn`
* **required** : Add line `pythonVersion = "2.7.12.3"` after `phpunitVersion` in `neard.conf`
* **required** : Add line `rubyVersion = "2.0.0.p648"` after `pythonVersion` in `neard.conf`
* **required** : Remove line `svnVersion` in the tools part in `neard.conf`
* **required** : Add line `svnVersion = "1.7.19"` after `memcachedEnable` in `neard.conf`
* **required** : Add line `svnEnable = "1"` after `svnVersion` in `neard.conf`
* **required** : Remove then replace file `sprites.dat`
* **required** : Remove files `ssl\neardfilezilla.*`
* **required** : Replace your existing Apache version with the latest [Apache bundle](http://neard.io/bins/apache/#releases).
* **required** : Replace your existing Filezilla version with the latest [Filezilla bundle](http://neard.io/bins/filezilla/#releases).

## 1.0.20 > 1.0.21

* **required** : Download and install the latests [Neard Prerequisites Package](https://github.com/crazy-max/neard-prerequisites/releases/latest)
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
* **required** : Add line `phpEnable = "1"` after `phpVersion` in `neard.conf`
* **required** : Add line `nodejsEnable = "1"` after `nodejsVersion` in `neard.conf`
* **required** : Add line `postgresqlVersion = "9.4.8"` after `mariadbEnable` in `neard.conf`
* **required** : Add line `postgresqlEnable = "1"` after `postgresqlVersion` in `neard.conf`
* **required** : Add line `memcachedVersion = "1.4.5"` after `mailhogEnable` in `neard.conf`
* **required** : Add line `memcachedEnable = "1"` after `memcachedVersion` in `neard.conf`
* **required** : Add line `phpmemadminVersion = "0.3.1"` after `gitlistVersion` in `neard.conf`
* **required** : Add line `phppgadminVersion = "5.2"` after `phpmyadminVersion` in `neard.conf`

## 1.0.19 > 1.0.20

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

## 1.0.18 > 1.0.19

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

## 1.0.17 > 1.0.18

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

## 1.0.16 > 1.0.17

* Remove `tccleVersion` key in `neard.conf`
* Change `phpmyadminVersion` value to `4` in `neard.conf`
* Remove then replace file `alias/phpmyadmin.conf`
* Remove then replace folder `apps/phpmyadmin`
* Remove then replace folder `core`
* Remove then replace folder `tools/console`
* Remove folder `tools/tccle`
* Remove file `neard.exe.manifest`
* Remove file `neard.exe.rc`
