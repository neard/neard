<?php

class BinFilezilla
{
    const SERVICE_NAME = 'neardfilezilla';
    
    const ROOT_CFG_VERSION = 'filezillaVersion';
    const ROOT_CFG_LAUNCH_STARTUP = 'filezillaLaunchStartup';
    
    const LOCAL_CFG_EXE = 'filezillaExe';
    const LOCAL_CFG_CONF = 'filezillaConf';
    const LOCAL_CFG_PORT = 'filezillaPort';
    const LOCAL_CFG_SSL_PORT = 'filezillaSslPort';
    
    const CFG_SERVER_PORT = 0;
    const CFG_WELCOME_MSG = 15;
    const CFG_IP_FILTER_ALLOWED = 39;
    const CFG_IP_FILTER_DISALLOWED = 40;
    const CFG_SERVICE_NAME = 58;
    const CFG_SERVICE_DISPLAY_NAME = 59;
    
    private $name;
    private $version;
    private $launchStartup;
    
    private $rootPath;
    private $currentPath;
    private $neardConf;
    private $neardConfRaw;
    
    private $logsPath;
    private $log;
    
    private $exe;
    private $conf;
    private $port;
    private $sslPort;
    
    private $service;
    
    public function __construct($rootPath, $version=null)
    {
        Util::logInitClass($this);
        $this->reload($rootPath);
    }
    
    public function reload($rootPath = null)
    {
        global $neardBs, $neardConfig, $neardLang;
        
        $this->name = $neardLang->getValue(Lang::FILEZILLA);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        $this->launchStartup = $neardConfig->getRaw(self::ROOT_CFG_LAUNCH_STARTUP) == Config::ENABLED;
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/filezilla' . $this->version;
        $this->neardConf = $this->currentPath . '/neard.conf';
        
        $this->logsPath = $this->currentPath . '/Logs';
        $this->log = $neardBs->getLogsPath() . '/filezilla.log';
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
            return;
        }
        if (!is_file($this->neardConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->neardConf));
            return;
        }
        
        // Create log hard link
        $log = $this->logsPath . '/FileZilla Server.log';
        if (!file_exists($this->log) && file_exists($log)) {
            @link($log, $this->log);
        }
        
        $this->neardConfRaw = parse_ini_file($this->neardConf);
        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
            $this->port = $this->neardConfRaw[self::LOCAL_CFG_PORT];
            $this->sslPort = $this->neardConfRaw[self::LOCAL_CFG_SSL_PORT];
        }
        
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
            return;
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
            return;
        }
        if (!is_numeric($this->port) || $this->port <= 0) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_PORT, $this->port));
            return;
        }
        if (!is_numeric($this->sslPort) || $this->sslPort <= 0) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_SSL_PORT, $this->sslPort));
            return;
        }
        
        $this->service = new Win32Service(self::SERVICE_NAME);
        $this->service->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $this->service->setBinPath($this->exe);
        $this->service->setStartType(Win32Service::SERVICE_DEMAND_START);
        $this->service->setErrorControl(Win32Service::SERVER_ERROR_NORMAL);
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
    
    public function rebuildConf()
    {
        $this->setConf(array(
            self::CFG_SERVICE_NAME => $this->service->getName(),
            self::CFG_WELCOME_MSG => $this->service->getDisplayName(),
            self::CFG_SERVICE_DISPLAY_NAME => $this->service->getDisplayName()
        ));
    }
    
    public function setConf($elts)
    {
        $conf = simplexml_load_file($this->conf);
        foreach ($elts as $key => $value) {
            $conf->Settings->Item[$key] = $value;
        }
        $conf->asXML($this->conf);
    }
    
    public function changePort($port, $checkUsed = false, $wbProgressBar = null)
    {
        global $neardCore, $neardBins, $neardApps, $neardWinbinder;
        
        if (!Util::isValidPort($port)) {
            Util::logError($this->getName() . ' port not valid: ' . $port);
            return false;
        }
    
        $port = intval($port);
        $neardWinbinder->incrProgressBar($wbProgressBar);
    
        $isPortInUse = Util::isPortInUse($port);
        if (!$checkUsed || $isPortInUse === false) {
            // bootstrap
            Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_FILEZILLA_PORT', intval($port));
            $neardWinbinder->incrProgressBar($wbProgressBar);
    
            // neard.conf
            $this->setPort($port);
            $neardWinbinder->incrProgressBar($wbProgressBar);
    
            // FileZilla Server.xml
            $this->setConf(array(self::CFG_SERVER_PORT => $port));
            $neardWinbinder->incrProgressBar($wbProgressBar);

            return true;
        }
    
        Util::logDebug($this->getName() . ' port in used: ' . $port . ' - ' . $isPortInUse);
        return $isPortInUse;
    }
    
    public function checkPort($port, $ssl = false, $showWindow = false)
    {
        global $neardLang, $neardWinbinder;
        $boxTitle = sprintf($neardLang->getValue(Lang::CHECK_PORT_TITLE), $this->getName(), $port);
    
        if (!Util::isValidPort($port)) {
            Util::logError($this->getName() . ' port not valid: ' . $port);
            return false;
        }
        
        $headers = Util::getHeaders('127.0.0.1', $port, $ssl);
        if (!empty($headers)) {
            if ($headers[0] == '220 ' . $this->getService()->getDisplayName()) {
                Util::logDebug($this->getName() . ' port ' . $port . ' is used by: ' . str_replace('220 ', '', $headers[0]));
                if ($showWindow) {
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::PORT_USED_BY), $port, str_replace('220 ', '', $headers[0])),
                        $boxTitle
                    );
                }
                return true;
            }
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
        Util::logDebug('Switch Filezilla Server version to ' . $version);
        $this->updateConfig($version, $showWindow);
    }
    
    public function update($showWindow = false)
    {
        $this->updateConfig(null, $showWindow);
    }
    
    private function updateConfig($version = null, $showWindow = false)
    {
        global $neardBs, $neardCore, $neardLang, $neardBins, $neardWinbinder;
        $version = $version == null ? $this->getVersion() : $version;
        Util::logDebug('Update Filezilla Server ' . $version . ' config...');
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
    
        $conf = str_replace('filezilla' . $this->getVersion(), 'filezilla' . $version, $this->getConf());
        $neardConf = str_replace('filezilla' . $this->getVersion(), 'filezilla' . $version, $this->neardConf);
    
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
    
        // bootstrap
        Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_FILEZILLA_VERSION', $version);
    
        // neard.conf
        $this->setVersion($version);
    }
    
    public function existsSslCrt()
    {
        global $neardBs;
    
        $ppkPath = $neardBs->getSslPath() . '/' . self::SERVICE_NAME . '.ppk';
        $pubPath = $neardBs->getSslPath() . '/' . self::SERVICE_NAME . '.pub';
        $crtPath = $neardBs->getSslPath() . '/' . self::SERVICE_NAME . '.crt';
    
        return is_file($ppkPath) && is_file($pubPath) && is_file($crtPath);
    }
    
    public function removeSslCrt()
    {
        global $neardBs;
    
        $ppkPath = $neardBs->getSslPath() . '/' . self::SERVICE_NAME . '.ppk';
        $pubPath = $neardBs->getSslPath() . '/' . self::SERVICE_NAME . '.pub';
        $crtPath = $neardBs->getSslPath() . '/' . self::SERVICE_NAME . '.crt';
    
        return @unlink($ppkPath) && @unlink($pubPath) && @unlink($crtPath);
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

    public function isLaunchStartup()
    {
        return $this->launchStartup;
    }
    
    public function setLaunchStartup($enabled)
    {
        global $neardConfig;
        $neardConfig->replace(self::ROOT_CFG_LAUNCH_STARTUP, $enabled);
    }
    
    public function getRootPath()
    {
        return $this->rootPath;
    }

    public function getCurrentPath()
    {
        return $this->currentPath;
    }
    
    public function getLogsPath()
    {
        return $this->logsPath;
    }
    
    public function getLog()
    {
        return $this->log;
    }
    
    public function getExe()
    {
        return $this->exe;
    }
    
    public function getConf()
    {
        return $this->conf;
    }
    
    public function getPort()
    {
        return $this->port;
    }
    
    public function setPort($port)
    {
        return $this->replace(self::LOCAL_CFG_PORT, $port);
    }
    
    public function getSslPort()
    {
        return $this->sslPort;
    }
    
    public function setSslPort($sslPort)
    {
        return $this->replace(self::LOCAL_CFG_SSL_PORT, $sslPort);
    }
    
    public function getService()
    {
        return $this->service;
    }
    
}
