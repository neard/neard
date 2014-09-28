<?php

class ToolSublimetext
{
    const ROOT_CFG_VERSION = 'sublimetextVersion';
    
    const LOCAL_CFG_EXE = 'sublimetextExe';
    const LOCAL_CFG_SESSION = 'sublimetextSession';
    const LOCAL_CFG_CONF = 'sublimetextConf';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    private $neardConf;
    private $neardConfRaw;
    
    private $exe;
    private $session;
    private $conf;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::SUBLIMETEXT);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/sublimetext' . $this->version;
        $this->neardConf = $this->currentPath . '/neard.conf';
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->neardConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->neardConf));
        }
        
        $this->neardConfRaw = parse_ini_file($this->neardConf);
        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->session = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_SESSION];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
        }
        
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
        }
        if (!is_file($this->session)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->session));
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
        }
    }
    
    public function __toString()
    {
        return $this->getName();
    }
    
    private function replace($key, $value)
    {
        $this->replaceAll(array($key => $value));
    }
    
    private function replaceAll($params)
    {
        $content = file_get_contents($this->neardConf);
    
        foreach ($params as $key => $value) {
            $content = preg_replace('|' . $key . ' = .*|', $key . ' = ' . '"' . $value.'"' , $content);
            $this->neardConfRaw[$key] = $value;
        }
    
        file_put_contents($this->neardConf, $content);
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
    
    public function setVersion($version)
    {
        global $neardConfig;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
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
