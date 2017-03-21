<?php

class ToolPython extends Module
{
    const ROOT_CFG_VERSION = 'pythonVersion';
    
    const LOCAL_CFG_EXE = 'pythonExe';
    const LOCAL_CFG_CP_EXE = 'pythonCpExe';
    const LOCAL_CFG_IDLE_EXE = 'pythonIdleExe';

    private $exe;
    private $cpExe;
    private $idleExe;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardConfig, $neardLang;

        $this->name = $neardLang->getValue(Lang::PYTHON);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);
        
        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->cpExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CP_EXE];
            $this->idleExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_IDLE_EXE];
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
        if (!is_file($this->cpExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->cpExe));
        }
        if (!is_file($this->idleExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->idleExe));
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
    
    public function getCpExe() {
        return $this->cpExe;
    }
    
    public function getIdleExe() {
        return $this->idleExe;
    }
}
