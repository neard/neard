<?php

class AppAdminer extends Module
{
    const ROOT_CFG_VERSION = 'adminerVersion';
    
    const LOCAL_CFG_CONF = 'adminerConf';
    
    private $conf;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardConfig, $neardLang;

        $this->name = $neardLang->getValue(Lang::ADMINER);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);
        
        if ($this->neardConfRaw !== false) {
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
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
        }
    }

    protected function updateConfig($version = null, $sub = 0, $showWindow = false) {
        global $neardBs, $neardBins;
        
        if (!$this->enable) {
            return true;
        }
        
        $version = $version == null ? $this->version : $version;
        Util::logDebug(($sub > 0 ? str_repeat(' ', 2 * $sub) : '') . 'Update ' . $this->name . ' ' . $version . ' config...');
    
        $alias = $neardBs->getAliasPath() . '/adminer.conf';
        if (is_file($alias)) {
            Util::replaceInFile($alias, array(
                '/^Alias\s\/adminer\s.*/' => 'Alias /adminer "' . $this->getCurrentPath() . '/"',
                '/^<Directory\s.*/' => '<Directory "' . $this->getCurrentPath() . '/">',
            ));
        } else {
            Util::logError($this->getName() . ' alias not found : ' . $alias);
        }
        
        if ($neardBins->getMysql()->isEnable()) {
            Util::replaceInFile($this->getConf(), array(
                '/^\$mysqlPort\s=\s(\d+)/' => '$mysqlPort = ' . $neardBins->getMysql()->getPort() . ';',
                '/^\$mysqlRootUser\s=\s/' => '$mysqlRootUser = \'' . $neardBins->getMysql()->getRootUser() . '\';',
                '/^\$mysqlRootPwd\s=\s/' => '$mysqlRootPwd = \'' . $neardBins->getMysql()->getRootPwd() . '\';'
            ));
        }
        if ($neardBins->getMariadb()->isEnable()) {
            Util::replaceInFile($this->getConf(), array(
                '/^\$mariadbPort\s=\s(\d+)/' => '$mariadbPort = ' . $neardBins->getMariadb()->getPort() . ';',
                '/^\$mariadbRootUser\s=\s/' => '$mariadbRootUser = \'' . $neardBins->getMariadb()->getRootUser() . '\';',
                '/^\$mariadbRootPwd\s=\s/' => '$mariadbRootPwd = \'' . $neardBins->getMariadb()->getRootPwd() . '\';'
            ));
        }
        if ($neardBins->getPostgresql()->isEnable()) {
            Util::replaceInFile($this->getConf(), array(
                '/^\$postgresqlPort\s=\s(\d+)/' => '$postgresqlPort = ' . $neardBins->getPostgresql()->getPort() . ';',
                '/^\$postgresqlRootUser\s=\s/' => '$postgresqlRootUser = \'' . $neardBins->getPostgresql()->getRootUser() . '\';',
                '/^\$postgresqlRootPwd\s=\s/' => '$postgresqlRootPwd = \'' . $neardBins->getPostgresql()->getRootPwd() . '\';'
            ));
        }
        if ($neardBins->getMongodb()->isEnable()) {
            Util::replaceInFile($this->getConf(), array(
                '/^\$mongodbPort\s=\s(\d+)/' => '$mongodbPort = ' . $neardBins->getMongodb()->getPort() . ';'
            ));
        }

        return true;
    }
    
    public function setVersion($version) {
        global $neardConfig;
        $this->version = $version;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
    }
    
    public function getConf() {
        return $this->conf;
    }
}
