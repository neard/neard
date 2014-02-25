<?php

define('ENVDEV', true);

define('APP_TITLE', 'Neard');
define('APP_GITHUB_HOME', 'https://github.com/crazy-max/neard');
define('APP_GITHUB_ISSUES', 'https://github.com/crazy-max/neard/issues');
define('APP_AUTHOR_NAME', 'Cr@zy');
define('APP_AUTHOR_EMAIL', 'webmaster@crazyws.fr');

define('HOSTS_FILE', 'C:\Windows\System32\drivers\etc\hosts');

define('CURRENT_APACHE_PORT', 81);
define('CURRENT_APACHE_VERSION', '2.2.22');
define('CURRENT_PHP_VERSION', '5.3.13');
define('CURRENT_MYSQL_PORT', 3308);
define('CURRENT_MYSQL_VERSION', '5.5.24');
define('CURRENT_MARIADB_PORT', 3309);
define('CURRENT_MARIADB_VERSION', '5.5.34');
define('CURRENT_NODEJS_VERSION', '0.10.22');

define('RETURN_TAB', '	');

// Bootstrap
require_once __DIR__ . '/classes/class.bootstrap.php';
$neardBs = new Bootstrap(__DIR__);
$neardBs->register();

// Process action
$neardAction = new Action();
$neardAction->process();
