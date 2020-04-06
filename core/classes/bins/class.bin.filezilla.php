<?php

class BinFilezilla extends Module
{
    const SERVICE_NAME = 'neardfilezilla';

    const ROOT_CFG_ENABLE = 'filezillaEnable';
    const ROOT_CFG_VERSION = 'filezillaVersion';

    const LOCAL_CFG_EXE = 'filezillaExe';
    const LOCAL_CFG_ITF_EXE = 'filezillaItfExe';
    const LOCAL_CFG_CONF = 'filezillaConf';
    const LOCAL_CFG_ITF_CONF = 'filezillaItfConf';
    const LOCAL_CFG_PORT = 'filezillaPort';
    const LOCAL_CFG_SSL_PORT = 'filezillaSslPort';

    const CFG_SERVER_PORT = 0;
    const CFG_WELCOME_MSG = 15;
    const CFG_IP_FILTER_ALLOWED = 39;
    const CFG_IP_FILTER_DISALLOWED = 40;
    const CFG_SERVICE_NAME = 58;
    const CFG_SERVICE_DISPLAY_NAME = 59;

    private $service;
    private $logsPath;
    private $log;

    private $exe;
    private $itfExe;
    private $conf;
    private $itfConf;
    private $localItfConf;
    private $port;
    private $sslPort;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardBs, $neardConfig, $neardLang;
        Util::logReloadClass($this);

        $this->name = $neardLang->getValue(Lang::FILEZILLA);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);

        $this->enable = $this->enable && $neardConfig->getRaw(self::ROOT_CFG_ENABLE);
        $this->service = new Win32Service(self::SERVICE_NAME);
        $this->logsPath = $this->symlinkPath . '/Logs';
        $this->log = $neardBs->getLogsPath() . '/filezilla.log';

        if ($this->neardConfRaw !== false) {
            $this->exe = $this->symlinkPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->itfExe = $this->symlinkPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_ITF_EXE];
            $this->conf = $this->symlinkPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
            $this->itfConf = $this->symlinkPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_ITF_CONF];
            $this->localItfConf = Util::formatUnixPath(getenv('APPDATA')) . '/FileZilla Server/' . $this->neardConfRaw[self::LOCAL_CFG_ITF_CONF];
            $this->port = $this->neardConfRaw[self::LOCAL_CFG_PORT];
            $this->sslPort = $this->neardConfRaw[self::LOCAL_CFG_SSL_PORT];
        }

        if (!$this->enable) {
            Util::logInfo($this->name . ' is not enabled!');
            return;
        }
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
            return;
        }
        if (!is_dir($this->symlinkPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->symlinkPath));
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
        if (!file_exists($this->localItfConf)) {
            if (!is_dir(dirname($this->localItfConf))) {
                Util::logDebug('Create folder ' . dirname($this->localItfConf));
                @mkdir(dirname($this->localItfConf), 0777);
            }
            Util::logDebug('Write ' . $this->neardConfRaw[self::LOCAL_CFG_ITF_CONF] . ' to ' . $this->localItfConf);
            @copy($this->itfConf, $this->localItfConf);
        }

        $this->service->setDisplayName(APP_TITLE . ' ' . $this->getName());
        $this->service->setBinPath($this->exe);
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
                case self::LOCAL_CFG_SSL_PORT:
                    $this->sslPort = $value;
                    break;
            }
        }

        file_put_contents($this->neardConf, $content);
    }

    public function rebuildConf() {
        if (!$this->enable) {
            return;
        }

        $this->setConf(array(
            self::CFG_SERVER_PORT => $this->port,
            self::CFG_SERVICE_NAME => $this->service->getName(),
            self::CFG_WELCOME_MSG => $this->service->getDisplayName(),
            self::CFG_SERVICE_DISPLAY_NAME => $this->service->getDisplayName()
        ));
    }

    public function setConf($elts) {
        if (!$this->enable) {
            return;
        }

        $conf = simplexml_load_file($this->conf);
        foreach ($elts as $key => $value) {
            $conf->Settings->Item[$key] = $value;
        }
        $conf->asXML($this->conf);
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

    public function checkPort($port, $ssl = false, $showWindow = false) {
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

    public function switchVersion($version, $showWindow = false) {
        Util::logDebug('Switch ' . $this->name . ' version to ' . $version);
        return $this->updateConfig($version, 0, $showWindow);
    }

    protected function updateConfig($version = null, $sub = 0, $showWindow = false) {
        global $neardLang, $neardWinbinder;

        if (!$this->enable) {
            return true;
        }

        $version = $version == null ? $this->version : $version;
        Util::logDebug(($sub > 0 ? str_repeat(' ', 2 * $sub) : '') . 'Update ' . $this->name . ' ' . $version . ' config...');

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

        // neard.conf
        $this->setVersion($version);

        // conf
        $this->rebuildConf();

        return true;
    }

    public function setVersion($version) {
        global $neardConfig;
        $this->version = $version;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
        $this->reload();
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
            Util::installService($this, $this->port, null, $showWindow);
        } else {
            Util::removeService($this->service, $this->name);
        }
    }

    public function getLogsPath() {
        return $this->logsPath;
    }

    public function getLog() {
        return $this->log;
    }

    public function getExe() {
        return $this->exe;
    }

    public function getItfExe() {
        return $this->itfExe;
    }

    public function getConf() {
        return $this->conf;
    }

    public function getItfConf() {
        return $this->itfConf;
    }

    public function getPort() {
        return $this->port;
    }

    public function setPort($port) {
        $this->replace(self::LOCAL_CFG_PORT, $port);
    }

    public function getSslPort() {
        return $this->sslPort;
    }

    public function setSslPort($sslPort) {
        $this->replace(self::LOCAL_CFG_SSL_PORT, $sslPort);
    }
}
