<?php

class ToolImagick
{
    const CFG_VERSION = 'imagickVersion';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::IMAGICK);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/imagick' . $this->version;
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

}
