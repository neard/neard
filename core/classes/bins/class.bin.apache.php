<?php

class BinApache
{
    const SERVICE_NAME = 'neardapache';
    const SERVICE_PARAMS = '-k runservice';
    
    const CFG_VERSION = 'apacheVersion';
    const CFG_EXE = 'apacheExe';
    const CFG_CONF = 'apacheConf';
    const CFG_PORT = 'apachePort';
    
    const CMD_VERSION_NUMBER = '-v';
    const CMD_COMPILE_SETTINGS = '-V';
    const CMD_COMPILED_MODULES = '-l';
    const CMD_CONFIG_DIRECTIVES = '-L';
    const CMD_VHOSTS_SETTINGS = '-S';
    const CMD_LOADED_MODULES = '-M';
    const CMD_SYNTAX_CHECK = '-t';
    
    private $name;
    private $version;
    private $service;
    private $port;
    
    private $rootPath;
    private $currentPath;
    private $modulesPath;
    private $accessLog;
    private $errorLog;
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
        
        $this->name = $neardLang->getValue(Lang::APACHE);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->conf = $neardConfig->getRaw(self::CFG_CONF);
        $this->port = $neardConfig->getRaw(self::CFG_PORT);
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/apache' . $this->version;
        $this->modulesPath = $this->currentPath . '/modules';
        $this->accessLog = $neardBs->getLogsPath() . '/apache_access.log';
        $this->errorLog = $neardBs->getLogsPath() . '/apache_error.log';
        $this->exe = $this->currentPath . '/' . $this->exe;
        $this->conf = $this->currentPath . '/' . $this->conf;
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
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
        }
    }
    
    public function __toString()
    {
        return $this->getName();
    }
    
    public function changePort($port, $checkUsed = false, $wbProgressBar = null)
    {
        global $neardBs, $neardCore, $neardConfig, $neardBins, $neardWinbinder;
        
        if (!Util::isValidPort($port)) {
            Util::logError($this->getName() . ' port not valid: ' . $port);
            return false;
        }
        
        $port = intval($port);
        $neardWinbinder->incrProgressBar($wbProgressBar);
        
        if (!$checkUsed || !Util::isPortInUse($port, $wbProgressBar)) {
            // bootstrap
            Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_APACHE_PORT', intval($port));
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // neard.conf
            $neardConfig->replace(BinApache::CFG_PORT, $port);
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // httpd.conf
            Util::replaceInFile($this->getConf(), array(
                '/^Listen\s(\d+)/' => 'Listen ' . $port,
                '/^ServerName\s+([a-zA-Z0-9.]+):(\d+)/' => 'ServerName {{1}}:' . $port,
                '/^NameVirtualHost\s+([a-zA-Z0-9.*]+):(\d+)/' => 'NameVirtualHost {{1}}:' . $port,
                '/^<VirtualHost\s+([a-zA-Z0-9.*]+):(\d+)>/' => '<VirtualHost {{1}}:' . $port . '>'
            ));
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // vhosts
            foreach ($this->getVhosts() as $vhost) {
                Util::replaceInFile($neardBs->getVhostsPath() . '/' . $vhost . '.conf', array(
                    '/ServerName\s+([a-zA-Z0-9.]+):(\d+)/' => 'ServerName {{1}}:' . $port,
                    '/^NameVirtualHost\s+([a-zA-Z0-9.*]+):(\d+)/' => 'NameVirtualHost {{1}}:' . $port,
                    '/^<VirtualHost\s+([a-zA-Z0-9.*]+):(\d+)>/' => '<VirtualHost {{1}}:' . $port . '>'
                ));
            }
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            return true;
        }
        
        Util::logDebug($this->getName() . ' port in used: ' . $port);
        return false;
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
        $out = "GET / HTTP/1.1\r\nHost: 127.0.0.1\r\nConnection: Close\r\n\r\n";
        if ($fp) {
            fwrite($fp, $out);
            while (!feof($fp)) {
                $line = fgets($fp, 128);
                if (preg_match('/Server: /',$line)) {
                    Util::logDebug($this->getName() . ' port ' . $port . ' is used by: ' . $this->getName() . ' ' . str_replace('Server: ', '', trim($line)));
                    if ($showWindow) {
                        $neardWinbinder->messageBoxInfo(
                            sprintf($neardLang->getValue(Lang::PORT_USED_BY), $port, str_replace('Server: ', '', trim($line))),
                            $boxTitle
                        );
                    }
                    return true;
                }
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
        Util::logDebug('Switch Apache version to ' . $version);
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
        
        $apacheConf = str_replace('apache' . $this->getVersion(), 'apache' . $version, $this->getConf());
        $neardConf = str_replace('apache' . $this->getVersion(), 'apache' . $version, $this->getNeardConf());
        $apachePhpModule = $neardBins->getPhp()->getApacheModule($version);
        
        if (!file_exists($apacheConf) || !file_exists($neardConf)) {
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
        
        if ($apachePhpModule === false) {
            Util::logDebug($this->getName() . ' ' . $version . ' does not seem to be compatible with PHP ' . $neardBins->getPhp()->getVersion());
            if ($showWindow) {
                $neardWinbinder->messageBoxError(
                    sprintf($neardLang->getValue(Lang::APACHE_INCPT), $version, $neardBins->getPhp()->getVersion()),
                    $boxTitle
                );
            }
            return false;
        }
        
        // bootstrap
        Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_APACHE_VERSION', $version);
    
        // neard.conf
        $neardConfig->replace(BinApache::CFG_VERSION, $version);
        
        // change PHP module
        Util::replaceInFile($apacheConf, array(
            '/^LoadModule\sphp5_module\s.*/' => 'LoadModule php5_module "' . $apachePhpModule . '"',
        ));
    }
    
    public function getModules()
    {
        $fromFolder = $this->getModulesFromFolder();
        $fromConf = $this->getModulesFromConf();
        $result = array_merge($fromFolder, $fromConf);
        ksort($result);
        return $result;
    }
    
    public function getModulesFromConf()
    {
        $result = array();
    
        $confContent = file($this->getConf());
        foreach ($confContent as $row) {
            $modMatch = array();
            if (preg_match('/^(#)?LoadModule\s*([a-z0-9_-]+)\s*"?(.*)"?/i', $row, $modMatch)) {
                $name = $modMatch[2];
                $path = $modMatch[3];
                if ($name != 'php5_module') {
                    if ($modMatch[1] == '#') {
                        $result[$name] = ActionSwitchApacheModule::SWITCH_OFF;
                    } else {
                        $result[$name] = ActionSwitchApacheModule::SWITCH_ON;
                    }
                }
            }
        }
    
        ksort($result);
        return $result;
    }
    
    public function getModulesLoaded()
    {
        $result = array();
        foreach ($this->getModulesFromConf() as $name => $status) {
            if ($status == ActionSwitchApacheModule::SWITCH_ON) {
                $result[] = $name;
            }
        }
        return $result;
    }
    
    public function getModulesFromFolder()
    {
        $result = array();
        
        if ($handle = opendir($this->getModulesPath())) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && Util::startWith($file, 'mod_') && (Util::endWith($file, '.so') || Util::endWith($file, '.dll'))) {
                    $name = str_replace(array('mod_', '.so', '.dll'), '', $file) . '_module';
                    $result[$name] = ActionSwitchApacheModule::SWITCH_OFF;
                }
            }
            closedir($handle);
        }
        
        ksort($result);
        return $result;
    }
    
    public function getAlias()
    {
        global $neardBs;
        $result = array();
        
        if ($handle = opendir($neardBs->getAliasPath())) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && Util::endWith($file, '.conf')) {
                    $result[] = str_replace('.conf', '', $file);
                }
            }
            closedir($handle);
        }
        
        ksort($result);
        return $result;
    }
    
    public function getVhosts()
    {
        global $neardBs;
        $result = array();
    
        if ($handle = opendir($neardBs->getVhostsPath())) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && Util::endWith($file, '.conf')) {
                    $result[] = str_replace('.conf', '', $file);
                }
            }
            closedir($handle);
        }
    
        ksort($result);
        return $result;
    }
    
    public function getVhostsUrl()
    {
        global $neardBs;
        $result = array();
        
        foreach ($this->getVhosts() as $vhost) {
            $vhostContent = file($neardBs->getVhostsPath() . '/' . $vhost . '.conf');
            foreach ($vhostContent as $vhostLine) {
                $vhostLine = trim($vhostLine);
                $enabled = !Util::startWith($vhostLine, '#');
                if (preg_match_all('/ServerName\s+(.*)/', $vhostLine, $matches)) {
                    foreach ($matches as $match) {
                        $found = isset($match[1]) ? trim($match[1]) : trim($match[0]);
                        if (filter_var('http://' . $found, FILTER_VALIDATE_URL) !== false) {
                            $result[$found] = $enabled;
                            break 2;
                        }
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function getCmdLineOutput($cmd)
    {
        global $neardCore, $neardBins;
        $result = array(
            'syntaxOk' => false,
            'content'  => null,
        );
        
        if (file_exists($this->getExe())) {
            $resultFile = Util::formatWindowsPath($neardCore->getTmpPath() . '/' . Util::random() . '.tmp');
            $scriptContent = '@ECHO OFF' . PHP_EOL . PHP_EOL;
            $scriptContent .= '"' . $this->getExe() . '" ' . $cmd . ' > "' . $resultFile . '" 2>&1' . PHP_EOL;
            
            $tmpResult = Util::execBatch($resultFile, $scriptContent, 10);
            if ($tmpResult !== false && is_array($tmpResult)) {
                $result['syntaxOk'] = trim($tmpResult[count($tmpResult) - 1]) == 'Syntax OK';
                if ($result['syntaxOk']) {
                    unset($tmpResult[count($tmpResult) - 1]);
                }
                $result['content'] = implode(PHP_EOL, $tmpResult);
            }
        }
        
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
    
    public function getModulesPath()
    {
        return $this->modulesPath;
    }
    
    public function getAccessLog()
    {
        return $this->accessLog;
    }
    
    public function getErrorLog()
    {
        return $this->errorLog;
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
