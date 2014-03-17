<?php

class BinXlight
{
    const SERVICE_NAME = 'neardxlight';
    const SERVICE_PARAMS = '-runservice';
    
    const CFG_VERSION = 'xlightVersion';
    const CFG_EXE = 'xlightExe';
    const CFG_CONF_OPTION = 'xlightConfOption';
    const CFG_CONF_HOSTS = 'xlightConfHosts';
    const CFG_CONF_USERS = 'xlightConfUsers';
    const CFG_PORT = 'xlightPort';
    
    private $name;
    private $version;
    private $service;
    private $port;
    
    private $rootPath;
    private $currentPath;
    private $logError;
    private $logSession;
    private $logTransfer;
    private $logStats;
    private $exe;
    private $confOption;
    private $confHosts;
    private $confUsers;
    private $neardConf;
    
    public function __construct($rootPath, $version=null)
    {
        Util::logInitClass($this);
        $this->reload($rootPath);
    }
    
    public function reload($rootPath = null)
    {
        global $neardBs, $neardConfig, $neardLang;
        
        $this->name = $neardLang->getValue(Lang::XLIGHT);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->confOption = $neardConfig->getRaw(self::CFG_CONF_OPTION);
        $this->confHosts = $neardConfig->getRaw(self::CFG_CONF_HOSTS);
        $this->confUsers = $neardConfig->getRaw(self::CFG_CONF_USERS);
        $this->port = $neardConfig->getRaw(self::CFG_PORT);
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/xlight' . $this->version;
        $this->logError = $neardBs->getLogsPath() . '/xlight_error.log';
        $this->logSession = $neardBs->getLogsPath() . '/xlight_session.log';
        $this->logStats = $neardBs->getLogsPath() . '/xlight_stats.log';
        $this->logTransfer = $neardBs->getLogsPath() . '/xlight_transfer.log';
        $this->exe = $this->currentPath . '/' . $this->exe;
        $this->confOption = $this->currentPath . '/' . $this->confOption;
        $this->confHosts = $this->currentPath . '/' . $this->confHosts;
        $this->confUsers = $this->currentPath . '/' . $this->confUsers;
        $this->neardConf = $this->currentPath . '/neard.conf';
        
        $this->service = new Win32Service(self::SERVICE_NAME);
        $this->service->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $this->service->setBinPath($this->exe);
        $this->service->setParams(self::SERVICE_PARAMS);
        $this->service->setStartType(Win32Service::SERVICE_DEMAND_START);
        $this->service->setErrorControl(Win32Service::SERVER_ERROR_NORMAL);
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->confOption)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->confOption));
        }
        if (!is_file($this->confHosts)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->confHosts));
        }
        if (!is_file($this->confUsers)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->confUsers));
        }
    }
    
    public function __toString()
    {
        return $this->getName();
    }
    
    public function changePort($port, $checkUsed = false, $wbProgressBar = null)
    {
        global $neardCore, $neardConfig, $neardBins, $neardApps, $neardWinbinder;
    
        if (!Util::isValidPort($port)) {
            Util::logError($this->getName() . ' port not valid: ' . $port);
            return false;
        }
    
        $port = intval($port);
        $neardWinbinder->incrProgressBar($wbProgressBar);
    
        $isPortInUse = Batch::isPortInUse($port);
        if (!$checkUsed || $isPortInUse === false) {
            // bootstrap
            Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_XLIGHT_PORT', intval($port));
            $neardWinbinder->incrProgressBar($wbProgressBar);
    
            // neard.conf
            $neardConfig->replace(self::CFG_PORT, $port);
            $neardWinbinder->incrProgressBar($wbProgressBar);
    
            // ftpd.hosts
            Util::replaceInFile($this->getConfHosts(), array(
                '/^<virtualserver\s+([a-zA-Z0-9.*]+):(\d+)>/' => '<virtualserver {{1}}:' . $port . '>'
            ));
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // ftpd.users
            Util::replaceInFile($this->getConfUsers(), array(
                '/^<virtualserver\s+([a-zA-Z0-9.*]+):(\d+)>/' => '<virtualserver {{1}}:' . $port . '>'
            ));
            $neardWinbinder->incrProgressBar($wbProgressBar);

            return true;
        }
    
        Util::logDebug($this->getName() . ' port in used: ' . $port . ' - ' . $isPortInUse);
        return $isPortInUse;
    }
    
    public function checkPort($port, $showWindow = false)
    {
        global $neardLang, $neardWinbinder;
        $boxTitle = sprintf($neardLang->getValue(Lang::CHECK_PORT_TITLE), $this->getName(), $port);
    
        if (!Util::isValidPort($port)) {
            Util::logError($this->getName() . ' port not valid: ' . $port);
            return false;
        }
    
        $fp = Util::fsockopenAlt('127.0.0.1', $port);
        if ($fp) {
            $out = fgets($fp);
            $expOut = explode(' ', $out);
            if (count($expOut) > 4 && $expOut[1] == 'Xlight') {
                Util::logDebug($this->getName() . ' port ' . $port . ' is used by: ' . $expOut[1] . ' ' . $expOut[2] . ' ' . $expOut[3]);
                if ($showWindow) {
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::PORT_USED_BY), $port, $expOut[1] . ' ' . $expOut[2] . ' ' . $expOut[3]),
                        $boxTitle
                    );
                }
                return true;
            }
            fclose($fp);
            Util::logDebug($this->getName() . ' port ' . $port . ' is used by another application');
            if ($showWindow) {
                $neardWinbinder->messageBoxWarning(
                    sprintf($neardLang->getValue(Lang::PORT_NOT_USED_BY), $port),
                    $boxTitle
                );
            }
        } else {
            Util::logDebug($this->getName() . ' port ' . $port . ' is not used');
            if ($showWindow) {
                $neardWinbinder->messageBoxError(
                    sprintf($neardLang->getValue(Lang::PORT_NOT_USED), $port),
                    $boxTitle
                );
            }
        }
    
        return false;
    }
    
    public function switchVersion($version, $showWindow = false)
    {
        global $neardBs, $neardCore, $neardConfig, $neardLang, $neardBins, $neardWinbinder;
        Util::logDebug('Switch Xlight version to ' . $version);
    
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
    
        $newConf = str_replace('xlight' . $this->getVersion(), 'xlight' . $version, $this->getConfOption());
        $neardConf = str_replace('xlight' . $this->getVersion(), 'xlight' . $version, $this->getNeardConf());
    
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
        Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_XLIGHT_VERSION', $version);
    
        // neard.conf
        $neardConfig->replace(BinMysql::CFG_VERSION, $version);
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

    public function getService()
    {
        return $this->service;
    }

    public function getPort()
    {
        return $this->port;
    }
    
    public function getRootPath()
    {
        return $this->rootPath;
    }

    public function getCurrentPath()
    {
        return $this->currentPath;
    }
    
    public function getLogError()
    {
        return $this->logError;
    }
    
    public function getLogSession()
    {
        return $this->logSession;
    }
    
    public function getLogStats()
    {
        return $this->logStats;
    }
    
    public function getLogTransfer()
    {
        return $this->logTransfer;
    }
    
    public function getExe()
    {
        return $this->exe;
    }
    
    public function getConfOption()
    {
        return $this->confOption;
    }
    
    public function getConfHosts()
    {
        return $this->confHosts;
    }
    
    public function getConfUsers()
    {
        return $this->confUsers;
    }
    
    public function getNeardConf()
    {
        return $this->neardConf;
    }
    
}
