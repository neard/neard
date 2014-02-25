<?php

class AppWebsvn
{
    const CFG_VERSION = 'websvnVersion';
    const CFG_CONF = 'websvnConf';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    private $conf;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::PHPMYADMIN);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->conf = $neardConfig->getRaw(self::CFG_CONF);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/websvn' . $this->version;
        $this->conf = $this->currentPath . '/' . $this->conf;
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
        }
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
    
    public function getConf()
    {
        return $this->conf;
    }
    
}
