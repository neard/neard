<?php

class ToolGhostscript extends Module
{
    const ROOT_CFG_VERSION = 'ghostscriptVersion';
    const LOCAL_CFG_EXE = 'ghostscriptExe';
    const LOCAL_CFG_EXE_CONSOLE= 'ghostscriptExeConsole';

    private $exe;
    private $exeConsole;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardConfig, $neardLang;

        $this->name = $neardLang->getValue(Lang::GHOSTSCRIPT);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);
        
        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->exeConsole = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE_CONSOLE];
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
        if (!is_file($this->exeConsole)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exeConsole));
        }
    }
    
    public function setVersion($version) {
        global $neardConfig;
        $this->version = $version;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
    }
    
    public function getExe() {
        return $this->exe;
    }
    
    public function getExeConsole() {
        return $this->exeConsole;
    }
}
