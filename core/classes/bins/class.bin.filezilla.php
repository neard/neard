<?php

class BinFilezilla
{
    const SERVICE_NAME = 'neardfilezilla';
    
    const CFG_VERSION = 'filezillaVersion';
    const CFG_EXE = 'filezillaExe';
    const CFG_CONF = 'filezillaConf';
    const CFG_PORT = 'filezillaPort';
    const CFG_SSL_PORT = 'filezillaSslPort';
    const CFG_LAUNCH_STARTUP = 'filezillaLaunchStartup';
    
    const LAUNCH_STARTUP_ON = 'on';
    const LAUNCH_STARTUP_OFF = 'off';
    
    const CFG_SERVER_PORT = 0;
    const CFG_WELCOME_MSG = 15;
    const CFG_IP_FILTER_ALLOWED = 39;
    const CFG_IP_FILTER_DISALLOWED = 40;
    const CFG_SERVICE_NAME = 58;
    const CFG_SERVICE_DISPLAY_NAME = 59;
    
    private $name;
    private $version;
    private $service;
    private $port;
    private $sslPort;
    private $launchStartup;
    
    private $rootPath;
    private $currentPath;
    private $log;
    private $linkLog;
    private $exe;
    private $conf;
    private $neardConf;
    
    public function __construct($rootPath, $version=null)
    {
        Util::logInitClass($this);
        $this->reload($rootPath);
    }
    
    public function reload($rootPath = null)
    {
        global $neardBs, $neardConfig, $neardLang;
        
        $this->name = $neardLang->getValue(Lang::FILEZILLA);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->conf = $neardConfig->getRaw(self::CFG_CONF);
        $this->port = $neardConfig->getRaw(self::CFG_PORT);
        $this->sslPort = $neardConfig->getRaw(self::CFG_SSL_PORT);
        $this->launchStartup = $neardConfig->getRaw(self::CFG_LAUNCH_STARTUP);
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/filezilla' . $this->version;
        $this->log = $neardBs->getLogsPath() . '/filezilla.log';
        $this->exe = $this->currentPath . '/' . $this->exe;
        $this->conf = $this->currentPath . '/' . $this->conf;
        $this->neardConf = $this->currentPath . '/neard.conf';
        
        $this->service = new Win32Service(self::SERVICE_NAME);
        $this->service->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $this->service->setBinPath($this->exe);
        $this->service->setStartType(Win32Service::SERVICE_DEMAND_START);
        $this->service->setErrorControl(Win32Service::SERVER_ERROR_NORMAL);
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
        }
        
        // Create log hard link
        $log = $this->currentPath . '/Logs/FileZilla Server.log';
        if (!file_exists($this->linkLog) && file_exists($log)) {
            @link($log, $this->linkLog);
        }
    }
    
    public function __toString()
    {
        return $this->getName();
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
            Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_FILEZILLA_PORT', intval($port));
            $neardWinbinder->incrProgressBar($wbProgressBar);
    
            // neard.conf
            $neardConfig->replace(self::CFG_PORT, $port);
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
        
        $fp = fsockopen(($ssl ? 'ssl://' : '') . '127.0.0.1', $port, $errno, $errstr, 5);
        if ($fp) {
            $out = fgets($fp);
            $expOut = explode(PHP_EOL, $out);
            if ($expOut[0] == '220 ' . $this->getService()->getDisplayName()) {
                Util::logDebug($this->getName() . ' port ' . $port . ' is used by: ' . str_replace('220 ', '', $expOut[0]));
                if ($showWindow) {
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::PORT_USED_BY), $port, str_replace('220 ', '', $expOut[0])),
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
        Util::logDebug('Switch Filezilla Server version to ' . $version);
    
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
    
        $newConf = str_replace('filezilla' . $this->getVersion(), 'filezilla' . $version, $this->getConf());
        $neardConf = str_replace('filezilla' . $this->getVersion(), 'filezilla' . $version, $this->getNeardConf());
    
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
        Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_FILEZILLA_VERSION', $version);
    
        // neard.conf
        $neardConfig->replace(BinFilezilla::CFG_VERSION, $version);
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

    public function getService()
    {
        return $this->service;
    }

    public function getPort()
    {
        return $this->port;
    }
    
    public function getSslPort()
    {
        return $this->sslPort;
    }
    
    public function getLaunchStartup()
    {
        return $this->launchStartup;
    }
    
    public function getRootPath()
    {
        return $this->rootPath;
    }

    public function getCurrentPath()
    {
        return $this->currentPath;
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
    
    public function getNeardConf()
    {
        return $this->neardConf;
    }
    
}
