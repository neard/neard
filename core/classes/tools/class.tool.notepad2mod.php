<?php

class ToolNotepad2Mod extends Module
{
    const ROOT_CFG_VERSION = 'notepad2modVersion';
    
    const LOCAL_CFG_EXE = 'notepad2modExe';
    const LOCAL_CFG_CONF = 'notepad2modConf';

    private $exe;
    private $conf;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardConfig, $neardLang;

        $this->name = $neardLang->getValue(Lang::NOTEPAD2MOD);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);
        
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
    
    public function open($caption, $content) {
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
    
    public function setVersion($version) {
        global $neardConfig;
        $this->version = $version;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
    }

    public function getExe() {
        return $this->exe;
    }
    
    public function getConf() {
        return $this->conf;
    }
}
