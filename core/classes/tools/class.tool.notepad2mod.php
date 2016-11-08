<?php

class ToolNotepad2Mod
{
    const ROOT_CFG_VERSION = 'notepad2modVersion';
    
    const LOCAL_CFG_EXE = 'notepad2modExe';
    const LOCAL_CFG_CONF = 'notepad2modConf';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    private $neardConf;
    private $neardConfRaw;
    private $enable;
    
    private $exe;
    private $conf;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::NOTEPAD2MOD);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/notepad2mod' . $this->version;
        $this->neardConf = $this->currentPath . '/neard.conf';
        $this->enable = is_dir($this->currentPath);
        
        $this->neardConfRaw = @parse_ini_file($this->neardConf);
        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
        }
        
        if (!$this->enable) {
            Util::logInfo($this->name . ' is not enabled!');
            return;
        }
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->neardConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->neardConf));
        }
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
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
    
    public function update($sub = 0, $showWindow = false)
    {
        return $this->updateConfig(null, $sub, $showWindow);
    }
    
    private function updateConfig($version = null, $sub = 0, $showWindow = false)
    {
        $version = $version == null ? $this->version : $version;
        Util::logDebug(($sub > 0 ? str_repeat(' ', 2 * $sub) : '') . 'Update ' . $this->name . ' ' . $version . ' config...');
    
        //TODO: Update config
    }
    
    public function open($caption, $content)
    {
        global $neardBs, $neardWinbinder;
        
        TplNotepad2Mod::process();
    
        $folderPath = $neardBs->getTmpPath() . '/notepad2mod-' . Util::random();
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
        $this->version = $version;
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
    
    public function isEnable()
    {
        return $this->enable;
    }

    public function getExe()
    {
        return $this->exe;
    }
    
    public function getConf()
    {
        return $this->conf;
    }
    
}
