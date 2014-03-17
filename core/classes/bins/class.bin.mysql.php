<?php

class BinMysql
{
    const SERVICE_NAME = 'neardmysql';
    
    const CFG_VERSION = 'mysqlVersion';
    const CFG_EXE = 'mysqlExe';
    const CFG_CLI_EXE = 'mysqlCliExe';
    const CFG_ADMIN = 'mysqlAdmin';
    const CFG_CONF = 'mysqlConf';
    const CFG_PORT = 'mysqlPort';
    
    const CMD_VERSION = '--version';
    const CMD_VARIABLES = 'variables';
    const CMD_SYNTAX_CHECK = '--help --verbose 1>NUL';
    
    private $name;
    private $version;
    private $service;
    private $port;
    
    private $rootPath;
    private $currentPath;
    private $errorLog;
    private $exe;
    private $cliExe;
    private $admin;
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
        
        $this->name = $neardLang->getValue(Lang::MYSQL);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->cliExe = $neardConfig->getRaw(self::CFG_CLI_EXE);
        $this->admin = $neardConfig->getRaw(self::CFG_ADMIN);
        $this->conf = $neardConfig->getRaw(self::CFG_CONF);
        $this->port = $neardConfig->getRaw(self::CFG_PORT);
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/mysql' . $this->version;
        $this->errorLog = $neardBs->getLogsPath() . '/mysql.log';
        $this->exe = $this->currentPath . '/' . $this->exe;
        $this->cliExe = $this->currentPath . '/' . $this->cliExe;
        $this->admin = $this->currentPath . '/' . $this->admin;
        $this->conf = $this->currentPath . '/' . $this->conf;
        $this->neardConf = $this->currentPath . '/neard.conf';
        
        $this->service = new Win32Service(self::SERVICE_NAME);
        $this->service->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $this->service->setBinPath($this->exe);
        $this->service->setParams(self::SERVICE_NAME);
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
            Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_MYSQL_PORT', intval($port));
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // neard.conf
            $neardConfig->replace(BinMysql::CFG_PORT, $port);
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // config.inc.php (phpmyadmin)
            Util::replaceInFile($neardApps->getPhpmyadmin()->getConf(), array(
                '/^\$mysqlPort\s=\s(\d+)/' => '$mysqlPort = ' . $port . ';'
            ));
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // php.ini (apache)
            Util::replaceInFile($neardBins->getPhp()->getApacheConf(), array(
                '/^mysqli.default_port\s=\s(\d+)/' => 'mysqli.default_port = ' . $port
            ));
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // php.ini (php)
            Util::replaceInFile($neardBins->getPhp()->getConf(), array(
                '/^mysqli.default_port\s=\s(\d+)/' => 'mysqli.default_port = ' . $port
            ));
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // my.ini
            Util::replaceInFile($this->getConf(), array(
                '/^port(.*?)=(.*?)(\d+)/' => 'port = ' . $port
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
            $dbLink = mysql_connect('127.0.0.1:' . $port, 'root', '');
            $isMysql = false;
            $version = false;
            
            if ($dbLink) {
                $result = mysql_query('SHOW VARIABLES', $dbLink);
                if ($result) {
                    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
                        if ($row[0] == 'version') {
                            $version = explode("-", $row[1]);
                            $version = count($version) > 1 ? $version[0] : $row[1];
                        }
                        if ($row[0] == 'version_comment' && Util::startWith(strtolower($row[1]), 'mysql')) {
                            $isMysql = true;
                        }
                    }
                    if (!$isMysql) {
                        Util::logDebug($this->getName() . ' port used by another DBMS: ' . $port);
                        if ($showWindow) {
                            $neardWinbinder->messageBoxWarning(
                                sprintf($neardLang->getValue(Lang::PORT_USED_BY_ANOTHER_DBMS), $port),
                                $boxTitle
                            );
                        }
                    } else {
                        Util::logDebug($this->getName() . ' port ' . $port . ' is used by: ' . $this->getName() . ' ' . $version);
                        if ($showWindow) {
                            $neardWinbinder->messageBoxInfo(
                                sprintf($neardLang->getValue(Lang::PORT_USED_BY), $port, $this->getName() . ' ' . $version),
                                $boxTitle
                            );
                        }
                        return true;
                    }
                }
                mysql_close($dbLink);
            } else {
                Util::logDebug($this->getName() . ' port ' . $port . ' is used by another application');
                if ($showWindow) {
                    $neardWinbinder->messageBoxWarning(
                        sprintf($neardLang->getValue(Lang::PORT_NOT_USED_BY), $port),
                        $boxTitle
                    );
                }
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
        Util::logDebug('Switch MySQL version to ' . $version);
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
        
        $newConf = str_replace('mysql' . $this->getVersion(), 'mysql' . $version, $this->getConf());
        $neardConf = str_replace('mysql' . $this->getVersion(), 'mysql' . $version, $this->getNeardConf());
        
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
        Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_MYSQL_VERSION', $version);
    
        // neard.conf
        $neardConfig->replace(BinMysql::CFG_VERSION, $version);
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
        if ($cmd == self::CMD_SYNTAX_CHECK) {
            $outputFrom = '2';
        } elseif ($cmd == self::CMD_VARIABLES) {
            $bin = $this->getAdmin();
            $removeLines = 2;
        }
    
        if (file_exists($this->getExe())) {
            $tmpResult = Batch::exec('mysqlGetCmdLineOutput', '"' . $bin . '" ' . $cmd . ' ' . $outputFrom, 10);
            if ($tmpResult !== false && is_array($tmpResult)) {
                $result['syntaxOk'] = !Util::contains(trim($tmpResult[count($tmpResult) - 1]), '[ERROR]');
                for ($i = 0; $i < $removeLines; $i++) {
                    unset($tmpResult[$i]);
                }
                $result['content'] = trim(str_replace($bin, '', implode(PHP_EOL, $tmpResult)));
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

    public function getExe()
    {
        return $this->exe;
    }
    
    public function getCliExe()
    {
        return $this->cliExe;
    }
    
    public function getAdmin()
    {
        return $this->admin;
    }

    public function getConf()
    {
        return $this->conf;
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
    
    public function getErrorLog()
    {
        return $this->errorLog;
    }
    
    public function getNeardConf()
    {
        return $this->neardConf;
    }
    
}
