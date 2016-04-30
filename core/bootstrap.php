<?php

define('APP_TITLE', 'Neard');
define('APP_GITHUB_HOME', 'https://github.com/crazy-max/neard');
define('APP_GITHUB_ISSUES', APP_GITHUB_HOME . '/issues');
define('APP_AUTHOR_NAME', 'Cr@zy');
define('APP_AUTHOR_EMAIL', 'webmaster@crazyws.fr');

define('CURRENT_APACHE_PORT', 80);
define('CURRENT_APACHE_SSL_PORT', 443);
define('CURRENT_APACHE_VERSION', '2.2.22');
define('CURRENT_FILEZILLA_PORT', 21);
define('CURRENT_FILEZILLA_SSL_PORT', 990);
define('CURRENT_FILEZILLA_VERSION', '0.9.46');
define('CURRENT_MARIADB_PORT', 3307);
define('CURRENT_MARIADB_VERSION', '5.5.34');
define('CURRENT_MYSQL_PORT', 3306);
define('CURRENT_MYSQL_VERSION', '5.5.24');
define('CURRENT_NODEJS_VERSION', '0.10.22');
define('CURRENT_PHP_VERSION', '5.3.13');

define('RETURN_TAB', '	');

// Bootstrap
require_once dirname(__FILE__) . '/classes/class.bootstrap.php';
$neardBs = new Bootstrap(dirname(__FILE__));
$neardBs->register();

// Process action
$neardAction = new Action();
$neardAction->process();

// Stop loading
if ($neardBs->isBootstrap()) {
    Util::stopLoading();
}
