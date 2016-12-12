<?php

define('APP_TITLE', 'Neard');
define('APP_GITHUB_HOME', 'https://github.com/crazy-max/neard');
define('APP_GITHUB_ANCHOR', '#neard');
define('APP_GITHUB_ISSUES', APP_GITHUB_HOME . '/issues');
define('APP_AUTHOR_NAME', 'Cr@zy');
define('APP_AUTHOR_EMAIL', 'webmaster@crazyws.fr');
define('APP_DONATE_URL', 'https://www.paypal.me/crazyws');

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
