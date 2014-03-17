<?php

class Action
{
    const ABOUT = 'about';
    const ADD_ALIAS = 'addAlias';
    const ADD_VHOST = 'addVhost';
    const CHANGE_BROWSER = 'changeBrowser';
    const CHANGE_PORT = 'changePort';
    const CHECK_PORT = 'checkPort';
    const CLEAR_FOLDERS = 'clearFolders';
    const DEBUG_APACHE = 'debugApache';
    const DEBUG_MARIADB = 'debugMariadb';
    const DEBUG_MYSQL = 'debugMysql';
    const EDIT_ALIAS = 'editAlias';
    const EDIT_VHOST = 'editVhost';
    const EXEC = 'exec';
    const LOADING = 'loading';
    const QUIT = 'quit';
    const REFRESH_REPOS = 'refreshRepos';
    const RELOAD = 'reload';
    const RESTART = 'restart';
    const SERVICE = 'service';
    const STARTUP = 'startup';
    const SWITCH_APACHE_MODULE = 'switchApacheModule';
    const SWITCH_HOST = 'switchHost';
    const SWITCH_LANG = 'switchLang';
    const SWITCH_LOGS_VERBOSE = 'switchLogsVerbose';
    const SWITCH_PHP_EXTENSION = 'switchPhpExtension';
    const SWITCH_PHP_PARAM = 'switchPhpParam';
    const SWITCH_STATUS = 'switchStatus';
    const SWITCH_VERSION = 'switchVersion';
    
    private $current;
    
    public function __construct()
    {
        
    }

    public function process()
    {
        if ($this->exists()) {
            $action = Util::cleanArgv(1);
            $actionClass = 'Action' . ucfirst($action);
            
            $args = array();
            foreach ($_SERVER['argv'] as $key => $arg) {
                if ($key > 1) {
                    $args[] = base64_decode($arg);
                }
            }
            
            $this->current = null;
            if (class_exists($actionClass)) {
                Util::logDebug('Start ' . $actionClass);
                $this->current = new $actionClass($args);
            }
        }
    }

    public function exists()
    {
        return isset($_SERVER['argv'])
            && isset($_SERVER['argv'][1])
            && !empty($_SERVER['argv'][1]);
    }
    
}
