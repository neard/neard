<?php

class Core
{
    const BOOTSTRAP_FILE = 'bootstrap.php';
    const PATH_WIN_PLACEHOLDER = '~NEARD_WIN_PATH~';
    const PATH_LIN_PLACEHOLDER = '~NEARD_LIN_PATH~';

    const PHP_VERSION = '5.4.23';
    const PHP_CLI_EXE = 'php.exe';
    const PHP_CLI_SILENT_EXE = 'php-win.exe';
    const PHP_CONF = 'php.ini';
    
    const APP_VERSION = 'version.dat';
    const LAST_PATH = 'lastPath.dat';
    const EXEC = 'exec.dat';
    const LOADING_PID = 'loading.pid';
    
    const SCRIPT_EXEC_SILENT = 'execSilent.vbs';

    private $langsPath;
    private $libsPath;

    public function __construct()
    {
        if (extension_loaded('winbinder')) {
            require_once $this->getLibsPath() . '/winbinder/winbinder.php';
        }
        require_once $this->getLibsPath() . '/markdown/markdown.php';
    }

    public function getLangsPath($aetrayPath = false)
    {
        global $neardBs;
        return $neardBs->getCorePath($aetrayPath) . '/langs';
    }

    public function getLibsPath($aetrayPath = false)
    {
        global $neardBs;
        return $neardBs->getCorePath($aetrayPath) . '/libs';
    }
    
    public function getResourcesPath($aetrayPath = false)
    {
        global $neardBs;
        return $neardBs->getCorePath($aetrayPath) . '/resources';
    }
    
    public function getScriptsPath($aetrayPath = false)
    {
        global $neardBs;
        return $neardBs->getCorePath($aetrayPath) . '/scripts';
    }
    
    public function getScript($type)
    {
        return $this->getScriptsPath() . '/' . $type;
    }
    
    public function getTmpPath($aetrayPath = false)
    {
        global $neardBs;
        return $neardBs->getCorePath($aetrayPath) . '/tmp';
    }

    public function getBootstrapFilePath($aetrayPath = false)
    {
        global $neardBs;
        return $neardBs->getCorePath($aetrayPath) . '/' . self::BOOTSTRAP_FILE;
    }
    
    public function getAppVersion()
    {
        global $neardLang;
        
        $filePath = $this->getResourcesPath() . '/' . self::APP_VERSION;
        if (!is_file($filePath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), APP_TITLE, $filePath));
            return null;
        }
        
        return trim(file_get_contents($filePath));
    }
    
    public function getLastPath($aetrayPath = false)
    {
        return $this->getResourcesPath($aetrayPath) . '/' . self::LAST_PATH;
    }
    
    public function getLastPathContent()
    {
    	return @file_get_contents($this->getLastPath());
    }
    
    public function getExec($aetrayPath = false)
    {
        return $this->getTmpPath($aetrayPath) . '/' . self::EXEC;
    }
    
    public function setExec($action)
    {
        file_put_contents($this->getExec(), $action);
    }
    
    public function getLoadingPid($aetrayPath = false)
    {
        return $this->getResourcesPath($aetrayPath) . '/' . self::LOADING_PID;
    }
    
    public function addLoadingPid($pid)
    {
        file_put_contents($this->getLoadingPid(), $pid . PHP_EOL, FILE_APPEND);
    }

    public function getPhpPath($aetrayPath = false)
    {
        return $this->getLibsPath($aetrayPath) . '/php';
    }

    public function getPhpCliSilentExe($aetrayPath = false)
    {
        return $this->getPhpPath($aetrayPath) . '/' . self::PHP_CLI_SILENT_EXE;
    }

}
