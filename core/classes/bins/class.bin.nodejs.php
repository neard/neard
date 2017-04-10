<?php

class BinNodejs extends Module
{
    const ROOT_CFG_ENABLE = 'nodejsEnable';
    const ROOT_CFG_VERSION = 'nodejsVersion';
    
    const LOCAL_CFG_EXE = 'nodejsExe';
    const LOCAL_CFG_VARS = 'nodejsVars';
    const LOCAL_CFG_NPM = 'nodejsNpm';
    const LOCAL_CFG_LAUNCH = 'nodejsLaunch';
    const LOCAL_CFG_CONF = 'nodejsConf';
    
    private $exe;
    private $conf;
    private $vars;
    private $npm;
    private $launch;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardConfig, $neardLang;
        
        $this->name = $neardLang->getValue(Lang::NODEJS);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);

        $this->enable = $this->enable && $neardConfig->getRaw(self::ROOT_CFG_ENABLE);

        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
            $this->vars = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_VARS];
            $this->npm = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_NPM];
            $this->launch = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_LAUNCH];
        }
        
        if (!$this->enable) {
            Util::logInfo($this->name . ' is not enabled!');
            return;
        }
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
            return;
        }
        if (!is_file($this->neardConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->neardConf));
            return;
        }
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
        }
        if (!is_file($this->vars)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->vars));
        }
        if (!is_file($this->npm)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->npm));
        }
        if (!is_file($this->launch)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->launch));
        }
    }
    
    public function switchVersion($version, $showWindow = false) {
        Util::logDebug('Switch ' . $this->name . ' version to ' . $version);
        return $this->updateConfig($version, 0, $showWindow);
    }
    
    protected function updateConfig($version = null, $sub = 0, $showWindow = false) {
        global $neardLang, $neardWinbinder;
        
        if (!$this->enable) {
            return true;
        }
        
        $version = $version == null ? $this->version : $version;
        Util::logDebug(($sub > 0 ? str_repeat(' ', 2 * $sub) : '') . 'Update ' . $this->name . ' ' . $version . ' config...');
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
        
        $conf = str_replace('nodejs' . $this->getVersion(), 'nodejs' . $version, $this->getConf());
        $neardConf = str_replace('nodejs' . $this->getVersion(), 'nodejs' . $version, $this->neardConf);
        
        if (!file_exists($conf) || !file_exists($neardConf)) {
            Util::logError('Neard config files not found for ' . $this->getName() . ' ' . $version);
            if ($showWindow) {
                $neardWinbinder->messageBoxError(
                    sprintf($neardLang->getValue(Lang::NEARD_CONF_NOT_FOUND_ERROR), $this->getName() . ' ' . $version),
                    $boxTitle
                );
            }
            return false;
        }
        
        $neardConfRaw = parse_ini_file($neardConf);
        if ($neardConfRaw === false || !isset($neardConfRaw[self::ROOT_CFG_VERSION]) || $neardConfRaw[self::ROOT_CFG_VERSION] != $version) {
            Util::logError('Neard config file malformed for ' . $this->getName() . ' ' . $version);
            if ($showWindow) {
                $neardWinbinder->messageBoxError(
                    sprintf($neardLang->getValue(Lang::NEARD_CONF_MALFORMED_ERROR), $this->getName() . ' ' . $version),
                    $boxTitle
                );
            }
            return false;
        }
        
        // neard.conf
        $this->setVersion($version);
        
        return true;
    }
    
    public function setVersion($version) {
        global $neardConfig;
        $this->version = $version;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
    }

    public function setEnable($enabled, $showWindow = false) {
        global $neardConfig, $neardLang, $neardWinbinder;

        if ($enabled == Config::ENABLED && !is_dir($this->currentPath)) {
            Util::logDebug($this->getName() . ' cannot be enabled because bundle ' . $this->getVersion() . ' does not exist in ' . $this->currentPath);
            if ($showWindow) {
                $neardWinbinder->messageBoxError(
                    sprintf($neardLang->getValue(Lang::ENABLE_BUNDLE_NOT_EXIST), $this->getName(), $this->getVersion(), $this->currentPath),
                    sprintf($neardLang->getValue(Lang::ENABLE_TITLE), $this->getName())
                );
            }
            $enabled = Config::DISABLED;
        }
    
        Util::logInfo($this->getName() . ' switched to ' . ($enabled == Config::ENABLED ? 'enabled' : 'disabled'));
        $this->enable = $enabled == Config::ENABLED;
        $neardConfig->replace(self::ROOT_CFG_ENABLE, $enabled);
    }

    public function getExe() {
        return $this->exe;
    }
    
    public function getConf() {
        return $this->conf;
    }

    public function getVars() {
        return $this->vars;
    }

    public function getNpm() {
        return $this->npm;
    }
    
    public function getLaunch() {
        return $this->launch;
    }
}
