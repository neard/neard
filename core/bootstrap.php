<?php

define('APP_TITLE', 'Neard');
define('APP_WEBSITE', 'http://neard.io');
define('APP_GITHUB_USER', 'neard');
define('APP_GITHUB_REPO', 'neard');
define('APP_AUTHOR_NAME', 'CrazyMax');

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
