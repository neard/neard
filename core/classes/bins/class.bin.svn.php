<?php

class BinSvn
{
    const SERVICE_NAME = 'neardsvn';
    const SERVICE_PARAMS = '--service --root "%s" --listen-port "%d" --log-file "%s"';
    
    const ROOT_CFG_ENABLE = 'svnEnable';
    const ROOT_CFG_VERSION = 'svnVersion';
    
    const LOCAL_CFG_EXE = 'svnExe';
    const LOCAL_CFG_ADMIN = 'svnAdmin';
    const LOCAL_CFG_SERVE = 'svnServe';
    const LOCAL_CFG_PORT = 'svnPort';
    
    const CMD_VERSION = '--version';
    
    private $name;
    private $version;
    private $service;
    
    private $rootPath;
    private $currentPath;
    private $neardConf;
    private $neardConfRaw;
    private $enable;
    
    private $log;
    private $root;
    
    private $exe;
    private $adminExe;
    private $serveExe;
    private $port;
    
    public function __construct($rootPath, $version=null)
    {
        Util::logInitClass($this);
        $this->reload($rootPath);
    }
    
    public function reload($rootPath = null)
    {
        global $neardBs, $neardConfig, $neardLang;
        
        $this->name = $neardLang->getValue(Lang::SVN);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        $this->service = new Win32Service(self::SERVICE_NAME);
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/svn' . $this->version;
        $this->neardConf = $this->currentPath . '/neard.conf';
        $this->enable = $neardConfig->getRaw(self::ROOT_CFG_ENABLE) == Config::ENABLED && is_dir($this->currentPath);
        
        $this->log = $neardBs->getLogsPath() . '/svn.log';
        $this->root = $this->currentPath . '/repos';
        
        $this->neardConfRaw = @parse_ini_file($this->neardConf);
        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->adminExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_ADMIN];
            $this->serveExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_SERVE];
            $this->port = intval($this->neardConfRaw[self::LOCAL_CFG_PORT]);
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
        if (!is_file($this->adminExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->adminExe));
            return;
        }
        if (!is_file($this->serveExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->serveExe));
            return;
        }
        if (empty($this->port)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_PORT, $this->port));
            return;
        }
        
        $this->service->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $this->service->setBinPath($this->serveExe);
        $this->service->setParams(sprintf(self::SERVICE_PARAMS, $this->root, $this->port, $this->log));
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
            switch($key) {
                case self::LOCAL_CFG_PORT:
                    $this->port = intval($value);
                    break;
            }
        }
    
        file_put_contents($this->neardConf, $content);
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
        
        $headers = Util::getHttpHeaders('http://localhost:' . $port);
        if (!empty($headers)) {
            if (count($headers) == 1 && Util::startWith($headers[0], '( success (')) {
                Util::logDebug($this->getName() . ' port ' . $port . ' is used by: ' . $this->getName());
                if ($showWindow) {
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::PORT_USED_BY), $port, $this->getName()),
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
        Util::logDebug('Switch ' . $this->name . ' version to ' . $version);
        return $this->updateConfig($version, 0, $showWindow);
    }
    
    public function update($sub = 0, $showWindow = false)
    {
        return $this->updateConfig(null, $sub, $showWindow);
    }
    
    private function updateConfig($version = null, $sub = 0, $showWindow = false)
    {
        global $neardLang, $neardApps, $neardWinbinder;
        $version = $version == null ? $this->version : $version;
        Util::logDebug(($sub > 0 ? str_repeat(' ', 2 * $sub) : '') . 'Update ' . $this->name . ' ' . $version . ' config...');
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
        
        $neardConf = str_replace('svn' . $this->getVersion(), 'svn' . $version, $this->neardConf);
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
        
        // websvn
        $neardApps->getWebsvn()->update($sub + 1);
        
        return true;
    }
    
    public function getCmdLineOutput($cmd)
    {
        $result = array(
            'syntaxOk' => false,
            'content'  => null,
        );
    
        $bin = $this->getExe();
        $removeLines = 0;
        $outputFrom = '';
    
        if (file_exists($this->getExe())) {
            $tmpResult = Batch::exec('svnGetCmdLineOutput', '"' . $bin . '" ' . $cmd . ' ' . $outputFrom);
            if ($tmpResult !== false && is_array($tmpResult)) {
                $result['syntaxOk'] = !Util::contains(trim($tmpResult[0]), 'invalid option');
                for ($i = 0; $i < $removeLines; $i++) {
                    unset($tmpResult[$i]);
                }
                $rebuildTmpResult = array();
                foreach ($tmpResult as $row) {
                    $rebuildTmpResult[] = Util::cp1252ToUtf8($row);
                }
                if ($cmd == self::CMD_VERSION) {
                    $result['content'] = trim($tmpResult[0]) . PHP_EOL . trim($tmpResult[1]);
                } else {
                    $result['content'] = trim(str_replace($bin, '', implode(PHP_EOL, $tmpResult)));
                }
            }
        }
    
        return $result;
    }
    
    public function findRepos()
    {
        $result = array();
        
        $handle = @opendir($this->root);
        if (!$handle) {
            return $result;
        }
        
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && is_dir($this->root . '/' . $file)) {
                $result[] = $this->root . '/' . $file;
            }
        }
        
        closedir($handle);
        ksort($result);
        return $result;
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

    public function getService()
    {
        return $this->service;
    }
    
    public function getRootPath()
    {
        return $this->rootPath;
    }
    
    public function getCurrentPath()
    {
        return $this->currentPath;
    }
    
    public function isEnable()
    {
        return $this->enable;
    }
    
    public function setEnable($enabled, $showWindow = false)
    {
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
            Util::removeService($this->service, $this->name, $showWindow);
        }
    }
    
    public function getLog()
    {
        return $this->log;
    }
    
    public function getRoot()
    {
        return $this->root;
    }
    
    public function getExe()
    {
        return $this->exe;
    }
    
    public function getAdminExe()
    {
        return $this->adminExe;
    }
    
    public function getServeExe()
    {
        return $this->serveExe;
    }
    
    public function getPort()
    {
        return $this->port;
    }
    
    public function setPort($port)
    {
        $this->replace(self::LOCAL_CFG_PORT, $port);
    }
}
