<?php

class BinPostgresql
{
    const SERVICE_NAME = 'neardpostgresql';
    
    const ROOT_CFG_ENABLE = 'postgresqlEnable';
    const ROOT_CFG_VERSION = 'postgresqlVersion';
    
    const LOCAL_CFG_CTL_EXE = 'postgresqlCtlExe';
    const LOCAL_CFG_CLI_EXE = 'postgresqlCliExe';
    const LOCAL_CFG_DUMP_EXE = 'postgresqlDumpExe';
    const LOCAL_CFG_DUMP_ALL_EXE = 'postgresqlDumpAllExe';
    const LOCAL_CFG_CONF = 'postgresqlConf';
    const LOCAL_CFG_HBA_CONF = 'postgresqlUserConf';
    const LOCAL_CFG_ALT_CONF = 'postgresqlAltConf';
    const LOCAL_CFG_ALT_HBA_CONF = 'postgresqlAltUserConf';
    const LOCAL_CFG_PORT = 'postgresqlPort';
    const LOCAL_CFG_ROOT_USER = 'postgresqlRootUser';
    const LOCAL_CFG_ROOT_PWD = 'postgresqlRootPwd';
    
    const CMD_VERSION = '--version';
    
    private $name;
    private $version;
    private $service;
    
    private $rootPath;
    private $currentPath;
    private $neardConf;
    private $neardConfRaw;
    private $enable;
    
    private $errorLog;
    
    private $ctlExe;
    private $cliExe;
    private $dumpExe;
    private $dumpAllExe;
    private $conf;
    private $hbaConf;
    private $altConf;
    private $altHbaConf;
    private $port;
    private $rootUser;
    private $rootPwd;
    
    public function __construct($rootPath)
    {
        Util::logInitClass($this);
        $this->reload($rootPath);
    }
    
    public function reload($rootPath = null)
    {
        global $neardBs, $neardConfig, $neardLang;
        
        $this->name = $neardLang->getValue(Lang::POSTGRESQL);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        $this->service = new Win32Service(self::SERVICE_NAME);
        
        $this->rootPath = $rootPath == null ? $this->rootPath : $rootPath;
        $this->currentPath = $this->rootPath . '/postgresql' . $this->version;
        $this->neardConf = $this->currentPath . '/neard.conf';
        $this->enable = $neardConfig->getRaw(self::ROOT_CFG_ENABLE) == Config::ENABLED && is_dir($this->currentPath);
        
        $this->errorLog = $neardBs->getLogsPath() . '/postgresql.log';

        $this->neardConfRaw = @parse_ini_file($this->neardConf);
        if ($this->neardConfRaw !== false) {
            $this->ctlExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CTL_EXE];
            $this->cliExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CLI_EXE];
            $this->dumpExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_DUMP_EXE];
            $this->dumpAllExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_DUMP_ALL_EXE];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
            $this->hbaConf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_HBA_CONF];
            $this->altConf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_ALT_CONF];
            $this->altHbaConf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_ALT_HBA_CONF];
            $this->port = $this->neardConfRaw[self::LOCAL_CFG_PORT];
            $this->rootUser = isset($this->neardConfRaw[self::LOCAL_CFG_ROOT_USER]) ? $this->neardConfRaw[self::LOCAL_CFG_ROOT_USER] : 'postgres';
            $this->rootPwd = isset($this->neardConfRaw[self::LOCAL_CFG_ROOT_PWD]) ? $this->neardConfRaw[self::LOCAL_CFG_ROOT_PWD] : '';
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
        if (!file_exists($this->conf)) {
            $this->conf = $this->altConf;
        }
        if (!file_exists($this->hbaConf)) {
            $this->hbaConf = $this->altHbaConf;
        }
        
        if (!is_file($this->ctlExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->ctlExe));
            return;
        }
        if (!is_file($this->cliExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->cliExe));
            return;
        }
        if (!is_file($this->dumpExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->dumpExe));
            return;
        }
        if (!is_file($this->dumpAllExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->dumpAllExe));
            return;
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
            return;
        }
        if (!is_file($this->hbaConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->hbaConf));
            return;
        }
        if (!is_numeric($this->port) || $this->port <= 0) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_PORT, $this->port));
            return;
        }
        if (empty($this->rootUser)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_ROOT_USER, $this->rootUser));
            return;
        }
        
        $this->service->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $this->service->setBinPath($this->ctlExe);
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
                    $this->port = $value;
                    break;
                case self::LOCAL_CFG_ROOT_USER:
                    $this->rootUser = $value;
                    break;
                case self::LOCAL_CFG_ROOT_PWD:
                    $this->rootPwd = $value;
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
        
        $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 5);
        if ($fp) {
            $dbLink = pg_connect('host=127.0.0.1 port=' . $port . ' user=' . $this->rootUser . ' password=' . $this->rootPwd);
            
            $isPostgresql = false;
            $version = false;
            
            if ($dbLink) {
                $result = pg_version($dbLink);
                pg_close($dbLink);
                if ($result) {
                    if (isset($result['server']) && $result['server'] == $this->getVersion()) {
                        $version = $result['server'];
                        $isPostgresql = true;
                    }
                    if (!$isPostgresql) {
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
    
    public function changeRootPassword($currentPwd, $newPwd, $wbProgressBar = null)
    {
        global $neardWinbinder;
        $error = null;
        
        $neardWinbinder->incrProgressBar($wbProgressBar);
        $dbLink = pg_connect('host=127.0.0.1 port=' . $this->port . ' user=' . $this->rootUser . ' password=' . $currentPwd);
        
        if (!$dbLink) {
            $error = pg_last_error($dbLink);
        }
        
        $neardWinbinder->incrProgressBar($wbProgressBar);
        $pgr = pg_query_params($dbLink, 'SELECT quote_ident($1)', array(pg_escape_string($this->rootUser)));
        list($quoted_user) = pg_fetch_array($pgr);
        $password = pg_escape_string($newPwd);
        $result = pg_query($dbLink, "ALTER USER $quoted_user WITH PASSWORD '$password'");
        if (empty($error) && !$result) {
            $error = pg_last_error($dbLink);
        }
        
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if ($dbLink) {
            pg_close($dbLink);
        }
        
        if (!empty($error)) {
            return $error;
        }
        
        // neard.conf
        $neardWinbinder->incrProgressBar($wbProgressBar);
        $this->setRootPwd($newPwd);
        
        // conf
        $this->update();
        $neardWinbinder->incrProgressBar($wbProgressBar);
        
        return true;
    }
    
    public function checkRootPassword($currentPwd = null, $wbProgressBar = null)
    {
        global $neardWinbinder;
        $currentPwd = $currentPwd == null ? $this->rootPwd : $currentPwd;
        $error = null;
        
        $neardWinbinder->incrProgressBar($wbProgressBar);
        $dbLink = pg_connect('host=127.0.0.1 port=' . $this->port . ' user=' . $this->rootUser . ' password=' . $currentPwd);
        if (!$dbLink) {
            $error = pg_last_error($dbLink);
        }
        
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if ($dbLink) {
            pg_close($dbLink);
        }
        
        if (!empty($error)) {
            return $error;
        }
        
        return true;
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
        
        $currentPath = str_replace('postgresql' . $this->getVersion(), 'postgresql' . $version, $this->getCurrentPath());
        $conf = str_replace('postgresql' . $this->getVersion(), 'postgresql' . $version, $this->getConf());
        $neardConf = str_replace('postgresql' . $this->getVersion(), 'postgresql' . $version, $this->neardConf);
        
        if ($this->version != $version) {
            $this->initData($currentPath);
        }
        
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
            '/^port(.*?)=(.*?)(\d+)/' => 'port = ' . $this->port
        ));
        
        // phppgadmin
        $neardApps->getPhppgadmin()->update($sub + 1);
        
        // adminer
        $neardApps->getAdminer()->update($sub + 1);
        
        return true;
    }
    
    public function initData($path = null) {
        $path = $path != null ? $path : $this->getCurrentPath();
        
        if (file_exists($path . '/data')) {
            return;
        }
        
        Batch::initializePostgresql($path);
    }
    
    public function rebuildConf()
    {
        Util::replaceInFile($this->conf, array(
            '/^port(.*?)=(.*?)(\d+)/' => 'port = ' . $this->port
        ));
        Util::replaceInFile($this->altConf, array(
            '/^port(.*?)=(.*?)(\d+)/' => 'port = ' . $this->port
        ));
    }
    
    public function getCmdLineOutput($cmd)
    {
        $result = null;
        
        $bin = $this->getCliExe();
        if (file_exists($bin)) {
            $tmpResult = Batch::exec('postgresqlGetCmdLineOutput', '"' . $bin . '" ' . $cmd);
            if ($tmpResult !== false && is_array($tmpResult)) {
                $result = trim(str_replace($bin, '', implode(PHP_EOL, $tmpResult)));
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
    
    public function getErrorLog()
    {
        return $this->errorLog;
    }

    public function getCtlExe()
    {
        return $this->ctlExe;
    }
    
    public function getCliExe()
    {
        return $this->cliExe;
    }
    
    public function getDumpExe()
    {
        return $this->dumpExe;
    }
    
    public function getDumpAllExe()
    {
        return $this->dumpAllExe;
    }
    
    public function getConf()
    {
        return $this->conf;
    }
    
    public function getHbaConf()
    {
        return $this->hbaConf;
    }
    
    public function getPort()
    {
        return $this->port;
    }
    
    public function setPort($port)
    {
        return $this->replace(self::LOCAL_CFG_PORT, $port);
    }
    
    public function getRootUser()
    {
        return $this->rootUser;
    }

    public function setRootUser($rootUser)
    {
        return $this->replace(self::LOCAL_CFG_ROOT_USER, $rootUser);
    }
    
    public function getRootPwd()
    {
        return $this->rootPwd;
    }

    public function setRootPwd($rootPwd)
    {
        return $this->replace(self::LOCAL_CFG_ROOT_PWD, $rootPwd);
    }
}
