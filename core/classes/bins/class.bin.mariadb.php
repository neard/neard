<?php

class BinMariadb extends Module
{
    const SERVICE_NAME = 'neardmariadb';
    
    const ROOT_CFG_ENABLE = 'mariadbEnable';
    const ROOT_CFG_VERSION = 'mariadbVersion';
    
    const LOCAL_CFG_EXE = 'mariadbExe';
    const LOCAL_CFG_CLI_EXE = 'mariadbCliExe';
    const LOCAL_CFG_ADMIN = 'mariadbAdmin';
    const LOCAL_CFG_CONF = 'mariadbConf';
    const LOCAL_CFG_PORT = 'mariadbPort';
    const LOCAL_CFG_ROOT_USER = 'mariadbRootUser';
    const LOCAL_CFG_ROOT_PWD = 'mariadbRootPwd';
    
    const CMD_VERSION = '--version';
    const CMD_VARIABLES = 'variables';
    const CMD_SYNTAX_CHECK = '--help --verbose 1>NUL';
    
    private $service;
    private $errorLog;
    
    private $exe;
    private $conf;
    private $port;
    private $rootUser;
    private $rootPwd;
    private $cliExe;
    private $admin;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardBs, $neardConfig, $neardLang;

        $this->name = $neardLang->getValue(Lang::MARIADB);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);

        $this->enable = $this->enable && $neardConfig->getRaw(self::ROOT_CFG_ENABLE);
        $this->service = new Win32Service(self::SERVICE_NAME);
        $this->errorLog = $neardBs->getLogsPath() . '/mariadb.log';

        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
            $this->port = $this->neardConfRaw[self::LOCAL_CFG_PORT];
            $this->rootUser = isset($this->neardConfRaw[self::LOCAL_CFG_ROOT_USER]) ? $this->neardConfRaw[self::LOCAL_CFG_ROOT_USER] : 'root';
            $this->rootPwd = isset($this->neardConfRaw[self::LOCAL_CFG_ROOT_PWD]) ? $this->neardConfRaw[self::LOCAL_CFG_ROOT_PWD] : '';
            $this->cliExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CLI_EXE];
            $this->admin = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_ADMIN];
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
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
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
        if (!is_file($this->cliExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->cliExe));
            return;
        }
        if (!is_file($this->admin)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->admin));
            return;
        }
        
        $this->service->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $this->service->setBinPath($this->exe);
        $this->service->setParams(self::SERVICE_NAME);
        $this->service->setStartType(Win32Service::SERVICE_DEMAND_START);
        $this->service->setErrorControl(Win32Service::SERVER_ERROR_NORMAL);
    }

    protected function replaceAll($params) {
        $content = file_get_contents($this->neardConf);
    
        foreach ($params as $key => $value) {
            $content = preg_replace('|' . $key . ' = .*|', $key . ' = ' . '"' . $value.'"', $content);
            $this->neardConfRaw[$key] = $value;
            switch ($key) {
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
            if (version_compare(phpversion(), '5.3') === -1) {
                $dbLink = mysqli_connect('127.0.0.1', $this->rootUser, $this->rootPwd, '', $port);
            } else {
                $dbLink = mysqli_connect('127.0.0.1:' . $port, $this->rootUser, $this->rootPwd);
            }
            $isMariadb = false;
            $version = false;
        
            if ($dbLink) {
                $result = mysqli_query($dbLink, 'SHOW VARIABLES');
                if ($result) {
                    while (false !== ($row = mysqli_fetch_array($result, MYSQLI_NUM))) {
                        if ($row[0] == 'version') {
                            $version = explode("-", $row[1]);
                            $version = count($version) > 1 ? $version[0] : $row[1];
                        }
                        if ($row[0] == 'version_comment' && Util::startWith(strtolower($row[1]), 'mariadb')) {
                            $isMariadb = true;
                        }
                        if ($isMariadb && $version !== false) {
                            break;
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
    
    public function changeRootPassword($currentPwd, $newPwd, $wbProgressBar = null) {
        global $neardWinbinder;
        $error = null;
    
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if (version_compare(phpversion(), '5.3') === -1) {
            $dbLink = @mysqli_connect('127.0.0.1', $this->rootUser, $currentPwd, '', $this->port);
        } else {
            $dbLink = @mysqli_connect('127.0.0.1:' . $this->port, $this->rootUser, $currentPwd);
        }
        if (!$dbLink) {
            $error = mysqli_connect_error();
        }
    
        $neardWinbinder->incrProgressBar($wbProgressBar);
        $stmt = @mysqli_prepare($dbLink, 'UPDATE mysql.user SET Password=PASSWORD(?) WHERE User=?');
        if (empty($error) && $stmt === false) {
            $error = mysqli_error($dbLink);
        }
    
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if (empty($error) && !@mysqli_stmt_bind_param($stmt, 'ss', $newPwd, $this->rootUser)) {
            $error = mysqli_stmt_error($stmt);
        }
    
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if (empty($error) && !@mysqli_stmt_execute($stmt)) {
            $error = mysqli_stmt_error($stmt);
        }
    
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if ($stmt !== false) {
            mysqli_stmt_close($stmt);
        }
    
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if (empty($error) && @mysqli_query($dbLink, "FLUSH PRIVILEGES") === false) {
            $error = mysqli_error($dbLink);
        }
    
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if ($dbLink) {
            mysqli_close($dbLink);
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
    
    public function checkRootPassword($currentPwd = null, $wbProgressBar = null) {
        global $neardWinbinder;
        $currentPwd = $currentPwd == null ? $this->rootPwd : $currentPwd;
        $error = null;
    
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if (version_compare(phpversion(), '5.3') === -1) {
            $dbLink = @mysqli_connect('127.0.0.1', $this->rootUser, $currentPwd, '', $this->port);
        } else {
            $dbLink = @mysqli_connect('127.0.0.1:' . $this->port, $this->rootUser, $currentPwd);
        }
        if (!$dbLink) {
            $error = mysqli_connect_error();
        }
    
        $neardWinbinder->incrProgressBar($wbProgressBar);
        if ($dbLink) {
            mysqli_close($dbLink);
        }
    
        if (!empty($error)) {
            return $error;
        }
    
        return true;
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
        
        $conf = str_replace('mariadb' . $this->getVersion(), 'mariadb' . $version, $this->getConf());
        $neardConf = str_replace('mariadb' . $this->getVersion(), 'mariadb' . $version, $this->neardConf);
        
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
        
        // phpmyadmin
        $neardApps->getPhpmyadmin()->update($sub + 1);
        
        // adminer
        $neardApps->getAdminer()->update($sub + 1);
        
        return true;
    }
    
    public function getCmdLineOutput($cmd) {
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
            $cmd .= ' --user=' . $this->getRootUser();
            if ($this->getRootPwd()) {
                $cmd .= ' --password=' . $this->getRootPwd();
            }
            $removeLines = 2;
        }
    
        if (file_exists($bin)) {
            $tmpResult = Batch::exec('mariadbGetCmdLineOutput', '"' . $bin . '" ' . $cmd . ' ' . $outputFrom, 5);
            if ($tmpResult !== false && is_array($tmpResult)) {
                $result['syntaxOk'] = empty($tmpResult) || !Util::contains(trim($tmpResult[count($tmpResult) - 1]), '[ERROR]');
                for ($i = 0; $i < $removeLines; $i++) {
                    unset($tmpResult[$i]);
                }
                $result['content'] = trim(str_replace($bin, '', implode(PHP_EOL, $tmpResult)));
            }
        }
    
        return $result;
    }
    
    public function setVersion($version) {
        global $neardConfig;
        $this->version = $version;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
    }

    public function getService()
    {
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
            Util::installService($this, $this->port, self::CMD_SYNTAX_CHECK, $showWindow);
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
    
    public function getConf() {
        return $this->conf;
    }
    
    public function getPort() {
        return $this->port;
    }
    
    public function setPort($port) {
        $this->replace(self::LOCAL_CFG_PORT, $port);
    }
    
    public function getRootUser() {
        return $this->rootUser;
    }
    
    public function setRootUser($rootUser) {
        $this->replace(self::LOCAL_CFG_ROOT_USER, $rootUser);
    }
    
    public function getRootPwd() {
        return $this->rootPwd;
    }
    
    public function setRootPwd($rootPwd) {
        $this->replace(self::LOCAL_CFG_ROOT_PWD, $rootPwd);
    }
    
    public function getCliExe() {
        return $this->cliExe;
    }
    
    public function getAdmin() {
        return $this->admin;
    }
}
