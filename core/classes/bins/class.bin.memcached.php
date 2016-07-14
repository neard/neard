<?php

class BinMemcached
{
    const SERVICE_NAME = 'neardmemcached';
    const SERVICE_PARAMS = '-m %d -p %d -U 0 -vv';
    
    const ROOT_CFG_VERSION = 'memcachedVersion';
    const ROOT_CFG_LAUNCH_STARTUP = 'memcachedLaunchStartup';
    
    const LOCAL_CFG_EXE = 'memcachedExe';
    const LOCAL_CFG_MEMORY = 'memcachedMemory';
    const LOCAL_CFG_PORT = 'memcachedPort';
    
    private $name;
    private $version;
    private $launchStartup;
    
    private $rootPath;
    private $currentPath;
    private $neardConf;
    private $neardConfRaw;
    
    private $log;
    
    private $exe;
    private $memory;
    private $port;
    
    private $service;
    
    public function __construct($rootPath, $version=null)
    {
        Util::logInitClass($this);
        $this->reload($rootPath);
    }
    
    public function reload($rootPath = null)
    {
        global $neardBs, $neardConfig, $neardLang;
        
        $this->name = $neardLang->getValue(Lang::MEMCACHED);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        $this->launchStartup = $neardConfig->getRaw(self::ROOT_CFG_LAUNCH_STARTUP) == Config::ENABLED;
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/memcached' . $this->version;
        $this->neardConf = $this->currentPath . '/neard.conf';
        
        $this->log = $neardBs->getLogsPath() . '/memcached.log';
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
            return;
        }
        if (!is_file($this->neardConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->neardConf));
            return;
        }
        
        $this->neardConfRaw = parse_ini_file($this->neardConf);
        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->memory = intval($this->neardConfRaw[self::LOCAL_CFG_MEMORY]);
            $this->port = intval($this->neardConfRaw[self::LOCAL_CFG_PORT]);
        }
        
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
            return;
        }
        if (empty($this->memory)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_API_PORT, $this->memory));
            return;
        }
        if (empty($this->port)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_UI_PORT, $this->port));
            return;
        }
        
        $nssm = new Nssm(self::SERVICE_NAME);
        $nssm->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $nssm->setBinPath($this->exe);
        $nssm->setParams(sprintf(self::SERVICE_PARAMS, $this->memory, $this->port));
        $nssm->setStart(Nssm::SERVICE_DEMAND_START);
        $nssm->setLogsPath($neardBs->getLogsPath() . '/memcached.log');
        
        $this->service = new Win32Service(self::SERVICE_NAME);
        $this->service->setNssm($nssm);
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
            switch($key) {
                case self::LOCAL_CFG_MEMORY:
                    $this->memory = intval($value);
                    break;
                case self::LOCAL_CFG_PORT:
                    $this->port = intval($value);
                    break;
            }
        }
    
        file_put_contents($this->neardConf, $content);
    }
    
    public function rebuildConf()
    {
        global $neardRegistry;
        
        $exists = $neardRegistry->exists(
            Registry::HKEY_LOCAL_MACHINE,
            'SYSTEM\CurrentControlSet\Services\\' . self::SERVICE_NAME . '\Parameters',
            Nssm::INFO_APP_PARAMETERS
        );
        if ($exists) {
            return $neardRegistry->setExpandStringValue(
                Registry::HKEY_LOCAL_MACHINE,
                'SYSTEM\CurrentControlSet\Services\\' . self::SERVICE_NAME . '\Parameters',
                Nssm::INFO_APP_PARAMETERS,
                sprintf(self::SERVICE_PARAMS, $this->memory, $this->port)
            );
        }
        
        return false;
    }
    
    public function changePort($port, $checkUsed = false, $wbProgressBar = null)
    {
        global $neardWinbinder;
    
        if (!Util::isValidPort($port)) {
            Util::logError($this->getName() . ' port not valid: ' . $port);
            return false;
        }
    
        $port = intval($port);
        $neardWinbinder->incrProgressBar($wbProgressBar);
    
        $isPortInUse = Util::isPortInUse($port);
        if (!$checkUsed || $isPortInUse === false) {
            // neard.conf
            $this->setPort($port);
            $neardWinbinder->incrProgressBar($wbProgressBar);
    
            // conf
            $this->update();
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
        
        if (function_exists('memcache_connect')) {
            $memcache = @memcache_connect('127.0.0.1', $port);
            if ($memcache) {
                $memcacheVersion = memcache_get_version($memcache);
                Util::logDebug($this->getName() . ' port ' . $port . ' is used by: ' . $this->getName() . ' ' . $memcacheVersion);
                memcache_close($memcache);
                if ($showWindow) {
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::PORT_USED_BY), $port, $this->getName() . ' ' . $memcacheVersion),
                        $boxTitle
                    );
                }
                return true;
            }
        } else {
            $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 3);
            if (!$fp) {
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
                fclose($fp);
            }
        }
    
        return false;
    }
    
    public function switchVersion($version, $showWindow = false)
    {
        Util::logDebug('Switch ' . $this->name . ' version to ' . $version);
        return $this->updateConfig($version, 0, $showWindow);
    }
    
    public function update($sub = 0, $showWindow = false)
    {
        return $this->updateConfig(null, $sub, $showWindow);
    }
    
    private function updateConfig($version = null, $sub = 0, $showWindow = false)
    {
        global $neardBs, $neardCore, $neardLang, $neardBins, $neardApps, $neardWinbinder;
        $version = $version == null ? $this->version : $version;
        $shortVersion = substr(str_replace('.', '', $version), 0, 2);
        Util::logDebug(($sub > 0 ? str_repeat(' ', 2 * $sub) : '') . 'Update ' . $this->name . ' ' . $version . ' config...');
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
        
        $neardConf = str_replace('memcached' . $this->getVersion(), 'memcached' . $version, $this->neardConf);
        if (!file_exists($neardConf)) {
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
        
        // phpmemadmin
        $neardApps->getPhpmemadmin()->update($sub + 1);
        
        return true;
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

    public function isLaunchStartup()
    {
        return $this->launchStartup;
    }
    
    public function setLaunchStartup($enabled)
    {
        global $neardConfig;
        $this->launchStartup = $enabled == Config::ENABLED;
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
    
    public function getLog()
    {
        return $this->log;
    }
    
    public function getExe()
    {
        return $this->exe;
    }
    
    public function getMemory()
    {
        return $this->memory;
    }
    
    public function setMemory($memory)
    {
        return $this->replace(self::LOCAL_CFG_MEMORY, $memory);
    }
    
    public function getPort()
    {
        return $this->port;
    }
    
    public function setPort($port)
    {
        return $this->replace(self::LOCAL_CFG_PORT, $port);
    }
    
    public function getService()
    {
        return $this->service;
    }
}
