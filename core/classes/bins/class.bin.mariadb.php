<?php

class BinMariadb
{
    const SERVICE_NAME = 'neardmariadb';
    
    const CFG_VERSION = 'mariadbVersion';
    const CFG_EXE = 'mariadbExe';
    const CFG_CLI_EXE = 'mariadbCliExe';
    const CFG_ADMIN = 'mariadbAdmin';
    const CFG_CONF = 'mariadbConf';
    const CFG_PORT = 'mariadbPort';
    const CFG_LAUNCH_STARTUP = 'mariadbLaunchStartup';
    
    const LAUNCH_STARTUP_ON = 'on';
    const LAUNCH_STARTUP_OFF = 'off';
    
    const CMD_VERSION = '--version';
    const CMD_VARIABLES = 'variables';
    const CMD_SYNTAX_CHECK = '--help --verbose 1>NUL';
    
    private $name;
    private $version;
    private $service;
    private $port;
    private $launchStartup;
    
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
        
        $this->name = $neardLang->getValue(Lang::MARIADB);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->cliExe = $neardConfig->getRaw(self::CFG_CLI_EXE);
        $this->admin = $neardConfig->getRaw(self::CFG_ADMIN);
        $this->conf = $neardConfig->getRaw(self::CFG_CONF);
        $this->port = $neardConfig->getRaw(self::CFG_PORT);
        $this->launchStartup = $neardConfig->getRaw(self::CFG_LAUNCH_STARTUP);
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/mariadb' . $this->version;
        $this->errorLog = $neardBs->getLogsPath() . '/mariadb.log';
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
            Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_MARIADB_PORT', intval($port));
            $neardWinbinder->incrProgressBar($wbProgressBar);
    
            // neard.conf
            $neardConfig->replace(BinMariadb::CFG_PORT, $port);
            $neardWinbinder->incrProgressBar($wbProgressBar);
    
            // config.inc.php (phpmyadmin)
            Util::replaceInFile($neardApps->getPhpmyadmin()->getConf(), array(
                '/^\$mariadbPort\s=\s(\d+)/' => '$mariadbPort = ' . $port . ';'
            ));
            $neardWinbinder->incrProgressBar($wbProgressBar);
            
            // config.php (adminer)
            Util::replaceInFile($neardApps->getAdminer()->getConf(), array(
                '/^\$mariadbPort\s=\s(\d+)/' => '$mariadbPort = ' . $port . ';'
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
        
        $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 5);
        if ($fp) {
            $dbLink = mysqli_connect('127.0.0.1:' . $port, 'root', '');
            $isMariadb = false;
            $version = false;
        
            if ($dbLink) {
                $result = mysqli_query($dbLink, 'SHOW VARIABLES');
                if ($result) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                        if ($row[0] == 'version') {
                            $version = explode("-", $row[1]);
                            $version = count($version) > 1 ? $version[0] : $row[1];
                        }
                        if ($row[0] == 'version_comment' && Util::startWith(strtolower($row[1]), 'mariadb')) {
                            $isMariadb = true;
                        }
                    }
                    if (!$isMariadb) {
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
                mysqli_close($dbLink);
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
        Util::logDebug('Switch MariaDB version to ' . $version);
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
        
        $newConf = str_replace('mariadb' . $this->getVersion(), 'mariadb' . $version, $this->getConf());
        $neardConf = str_replace('mariadb' . $this->getVersion(), 'mariadb' . $version, $this->getNeardConf());
        
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
        Util::replaceDefine($neardCore->getBootstrapFilePath(), 'CURRENT_MARIADB_VERSION', $version);
    
        // neard.conf
        $neardConfig->replace(BinMariadb::CFG_VERSION, $version);
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
            $tmpResult = Batch::exec('mariadbGetCmdLineOutput', '"' . $bin . '" ' . $cmd . ' ' . $outputFrom, 10);
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
    
    public function getErrorLog()
    {
        return $this->errorLog;
    }
    
    public function getNeardConf()
    {
        return $this->neardConf;
    }
    
}
