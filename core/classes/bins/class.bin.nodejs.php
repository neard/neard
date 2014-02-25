<?php

class BinNodejs
{
    const CFG_VERSION = 'nodejsVersion';
    const CFG_EXE = 'nodejsExe';
    const CFG_VARS = 'nodejsVars';
    const CFG_NPM = 'nodejsNpm';
    const CFG_LAUNCH = 'nodejsLaunch';
    const CFG_CONF = 'nodejsConf';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    private $exe;
    private $vars;
    private $npm;
    private $launch;
    private $conf;
    private $neardConf;
    
    public function __construct($rootPath)
    {
        Util::logInitClass($this);
        $this->reload($rootPath);
    }
    
    public function reload($rootPath = null)
    {
        global $neardBs, $neardConfig, $neardLang;
        
        $this->name = $neardLang->getValue(Lang::NODEJS);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->vars = $neardConfig->getRaw(self::CFG_VARS);
        $this->npm = $neardConfig->getRaw(self::CFG_NPM);
        $this->launch = $neardConfig->getRaw(self::CFG_LAUNCH);
        $this->conf = $neardConfig->getRaw(self::CFG_CONF);
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/nodejs' . $this->version;
        $this->exe = $this->currentPath . '/' . $this->exe;
        $this->vars = $this->currentPath . '/' . $this->vars;
        $this->npm = $this->currentPath . '/' . $this->npm;
        $this->launch = $this->currentPath . '/' . $this->launch;
        $this->conf = $this->currentPath . '/' . $this->conf;
        $this->neardConf = $this->currentPath . '/neard.conf';
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
        }
    }
    
    public function __toString()
    {
        return $this->getName();
    }
    
    public function switchVersion($version, $showWindow = false)
    {
        global $neardBs, $neardCore, $neardConfig, $neardLang, $neardWinbinder;
        Util::logDebug('Switch NodeJS version to ' . $version);
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
        
        $newConf = str_replace('nodejs' . $this->getVersion(), 'nodejs' . $version, $this->getConf());
        $neardConf = str_replace('nodejs' . $this->getVersion(), 'nodejs' . $version, $this->getNeardConf());
        
        if (!file_exists($newConf) || !file_exists($neardConf)) {
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
        if ($neardConfRaw === false || !isset($neardConfRaw[self::CFG_VERSION]) || $neardConfRaw[self::CFG_VERSION] != $version) {
            Util::logError('Neard config file malformed for ' . $this->getName() . ' ' . $version);
            if ($showWindow) {
                $neardWinbinder->messageBoxError(
                    sprintf($neardLang->getValue(Lang::NEARD_CONF_MALFORMED_ERROR), $this->getName() . ' ' . $version),
                    $boxTitle
                );
            }
            return false;
        }
    
        // bootstrap
        Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_NODEJS_VERSION', $version);
    
        // neard.conf
        $neardConfig->replace(BinNodejs::CFG_VERSION, $version);
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

    public function getVars()
    {
        return $this->vars;
    }

    public function getNpm()
    {
        return $this->npm;
    }
    
    public function getLaunch()
    {
        return $this->launch;
    }

    public function getConf()
    {
        return $this->conf;
    }
    
    public function getNeardConf()
    {
        return $this->neardConf;
    }
    
}
