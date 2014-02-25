<?php

class Core
{
    const BOOTSTRAP_FILE = 'bootstrap.php';

    const PHP_VERSION = '5.4.23';
    const PHP_CLI_EXE = 'php.exe';
    const PHP_CLI_SILENT_EXE = 'php-win.exe';
    const PHP_CONF = 'php.ini';
    
    const APP_PATHS = 'paths.dat';

    private $langsPath;
    private $libsPath;

    public function __construct()
    {
        if (extension_loaded('winbinder')) {
            require_once $this->getLibsPath() . '/winbinder/winbinder.php';
        }
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
    
    public function getAppPaths($aetrayPath = false)
    {
        return $this->getResourcesPath($aetrayPath) . '/' . self::APP_PATHS;
    }

    public function getPhpPath($aetrayPath = false)
    {
        return $this->getLibsPath($aetrayPath) . '/php';
    }

    public function getPhpCliExe($aetrayPath = false)
    {
        return $this->getPhpPath($aetrayPath) . '/' . self::PHP_CLI_EXE;
    }

    public function getPhpCliSilentExe($aetrayPath = false)
    {
        return $this->getPhpPath($aetrayPath) . '/' . self::PHP_CLI_SILENT_EXE;
    }

}
