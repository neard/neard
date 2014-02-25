<?php

class ToolXdc
{
    const CFG_VERSION = 'xdcVersion';
    const CFG_EXE = 'xdcExe';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    private $exe;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::XDC);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/xdc' . $this->version;
        $this->exe = $this->currentPath . '/' . $this->exe;
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
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

    public function getExe()
    {
        return $this->exe;
    }

}
