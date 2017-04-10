<?php

class BinMongodb extends Module
{
    const SERVICE_NAME = 'neardmongodb';
    const SERVICE_PARAMS = '--config "%s" --service';
    
    const ROOT_CFG_ENABLE = 'mongodbEnable';
    const ROOT_CFG_VERSION = 'mongodbVersion';
    
    const LOCAL_CFG_EXE = 'mongodbExe';
    const LOCAL_CFG_CLI_EXE = 'mongodbCliExe';
    const LOCAL_CFG_CONF = 'mongodbConf';
    const LOCAL_CFG_PORT = 'mongodbPort';
    
    const CMD_VERSION = '--version';
    const CMD_STATUS = '--quiet 127.0.0.1:%d --eval "JSON.stringify(db.runCommand( { serverStatus: 1 } ))"';
    const URL_STATUS = 'http://127.0.0.1:%d/serverStatus';
    
    private $service;
    private $errorLog;
    
    private $exe;
    private $cliExe;
    private $conf;
    private $port;
    private $webPort;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardBs, $neardConfig, $neardLang;
        
        $this->name = $neardLang->getValue(Lang::MONGODB);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);

        $this->enable = $this->enable && $neardConfig->getRaw(self::ROOT_CFG_ENABLE);
        $this->service = new Win32Service(self::SERVICE_NAME);
        $this->errorLog = $neardBs->getLogsPath() . '/mongodb.log';

        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->cliExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CLI_EXE];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
            $this->port = $this->neardConfRaw[self::LOCAL_CFG_PORT];
            $this->webPort = $this->port + 1000;
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
            return;
        }
        if (!is_file($this->cliExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->cliExe));
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
        if (!is_numeric($this->webPort) || $this->webPort <= 0) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), 'webPort', $this->webPort));
            return;
        }
        
        $this->service->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $this->service->setBinPath($this->exe);
        $this->service->setParams(sprintf(self::SERVICE_PARAMS, Util::formatWindowsPath($this->conf)));
        $this->service->setStartType(Win32Service::SERVICE_DEMAND_START);
        $this->service->setErrorControl(Win32Service::SERVER_ERROR_NORMAL);
    }
    
    private function replaceAll($params) {
        $content = file_get_contents($this->neardConf);
    
        foreach ($params as $key => $value) {
            $content = preg_replace('|' . $key . ' = .*|', $key . ' = ' . '"' . $value.'"', $content);
            $this->neardConfRaw[$key] = $value;
            switch ($key) {
                case self::LOCAL_CFG_PORT:
                    $this->port = $value;
                    $this->webPort = $this->port + 1000;
                    break;
            }
        }
    
        file_put_contents($this->neardConf, $content);
    }
    
    public function changePort($port, $checkUsed = false, $wbProgressBar = null) {
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
    
    public function checkPort($port, $showWindow = false) {
        global $neardLang, $neardWinbinder;
        $boxTitle = sprintf($neardLang->getValue(Lang::CHECK_PORT_TITLE), $this->getName(), $port);
    
        if (!Util::isValidPort($port)) {
            Util::logError($this->getName() . ' port not valid: ' . $port);
            return false;
        }
        
        $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 5);
        if ($fp) {
            $serverStatus = json_decode(file_get_contents(sprintf(self::URL_STATUS, $port + 1000)), true);
            if (!is_array($serverStatus) || !isset($serverStatus['version'])) {
                Util::logDebug($this->getName() . ' port ' . $port . ' is used by another application');
                if ($showWindow) {
                    $neardWinbinder->messageBoxWarning(
                        sprintf($neardLang->getValue(Lang::PORT_NOT_USED_BY), $port),
                        $boxTitle
                    );
                }
                return false;
            }
            
            $version = $serverStatus['version'];
            Util::logDebug($this->getName() . ' port ' . $port . ' is used by: ' . $this->getName() . ' ' . $version);
            if ($showWindow) {
                $neardWinbinder->messageBoxInfo(
                    sprintf($neardLang->getValue(Lang::PORT_USED_BY), $port, $this->getName() . ' ' . $version),
                    $boxTitle
                );
            }
            return true;
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
    
    public function switchVersion($version, $showWindow = false) {
        Util::logDebug('Switch ' . $this->name . ' version to ' . $version);
        return $this->updateConfig($version, 0, $showWindow);
    }

    protected function updateConfig($version = null, $sub = 0, $showWindow = false) {
        global $neardLang, $neardApps, $neardWinbinder;
        
        if (!$this->enable) {
            return true;
        }
        
        $version = $version == null ? $this->version : $version;
        Util::logDebug(($sub > 0 ? str_repeat(' ', 2 * $sub) : '') . 'Update ' . $this->name . ' ' . $version . ' config...');
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
        
        $conf = str_replace('mongodb' . $this->getVersion(), 'mongodb' . $version, $this->getConf());
        $neardConf = str_replace('mongodb' . $this->getVersion(), 'mongodb' . $version, $this->neardConf);
        
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
        
        // conf
        Util::replaceInFile($this->getConf(), array(
            '/^(.*?)port(.*?):(.*?)(\d+)/' => '  port: ' . $this->port
        ));
        
        // adminer
        $neardApps->getAdminer()->update($sub + 1);
        
        return true;
    }
    
    public function initData() {
        if (!file_exists($this->getCurrentPath() . '/data/mongod.lock')) {
            return;
        }
        
        @unlink($this->getCurrentPath() . '/data/mongod.lock');
        Batch::repairMongodb($this->getExe(), $this->getConf());
    }
    
    public function getCmdLineOutput($cmd) {
        $result = null;
    
        $bin = $this->getCliExe();
        if (file_exists($bin)) {
            $tmpResult = Batch::exec('mongodbGetCmdLineOutput', '"' . $bin . '" ' . $cmd);
            if ($tmpResult !== false && is_array($tmpResult)) {
                $result = trim(str_replace($bin, '', implode(PHP_EOL, $tmpResult)));
            }
        }
    
        return $result;
    }
    
    public function setVersion($version) {
        global $neardConfig;
        $this->version = $version;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
    }

    public function getService() {
        return $this->service;
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
    
        $this->reload();
        if ($this->enable) {
            Util::installService($this, $this->port, null, $showWindow);
        } else {
            Util::removeService($this->service, $this->name);
        }
    }
    
    public function getErrorLog() {
        return $this->errorLog;
    }

    public function getExe() {
        return $this->exe;
    }
    
    public function getCliExe() {
        return $this->cliExe;
    }
    
    public function getConf() {
        return $this->conf;
    }
    
    public function getPort() {
        return $this->port;
    }
    
    public function setPort($port) {
        $this->replace(self::LOCAL_CFG_PORT, $port);
    }
    
    public function getWebPort() {
        return $this->webPort;
    }
}
