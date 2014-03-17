<?php

class ToolSublimetext
{
    const CFG_VERSION = 'sublimetextVersion';
    const CFG_EXE = 'sublimetextExe';
    const CFG_SESSION = 'sublimetextSession';
    const CFG_CONF = 'sublimetextConf';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    private $exe;
    private $session;
    private $conf;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::SUBLIMETEXT);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->session = $neardConfig->getRaw(self::CFG_SESSION);
        $this->conf = $neardConfig->getRaw(self::CFG_CONF);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/sublimetext' . $this->version;
        $this->exe = $this->currentPath . '/' . $this->exe;
        $this->session = $this->currentPath . '/' . $this->session;
        $this->conf = $this->currentPath . '/' . $this->conf;
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
        }
        if (!is_file($this->session)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->session));
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
        }
    }
    
    public function open($caption, $content)
    {
        global $neardCore, $neardTools, $neardWinbinder;
        
        TplSublimetext::process();
    
        $folderPath = $this->getCurrentPath() . '/Tmp/' . Util::random();
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
    
        $filepath = Util::formatWindowsPath($folderPath . '/' . $caption);
        file_put_contents($filepath, $content);
    
        $neardWinbinder->exec($this->getExe(), '"' . $filepath . '"');
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getVersionList()
    {
        return Util::getVersionList($this->getRootPath());
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getRootPath()
    {
        return $this->rootPath;
    }

    public function getCurrentPath()
    {
        return $this->currentPath;
    }

    public function getExe()
    {
        return $this->exe;
    }
    
    public function getSession()
    {
        return $this->session;
    }
    
    public function getConf()
    {
        return $this->conf;
    }
    
}
