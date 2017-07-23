<?php

class BinApache extends Module
{
    const SERVICE_NAME = 'neardapache';
    const SERVICE_PARAMS = '-k runservice';
    
    const ROOT_CFG_ENABLE = 'apacheEnable';
    const ROOT_CFG_VERSION = 'apacheVersion';
    
    const LOCAL_CFG_EXE = 'apacheExe';
    const LOCAL_CFG_CONF = 'apacheConf';
    const LOCAL_CFG_PORT = 'apachePort';
    const LOCAL_CFG_SSL_PORT = 'apacheSslPort';
    const LOCAL_CFG_OPENSSL_EXE = 'apacheOpensslExe';
    
    const CMD_VERSION_NUMBER = '-v';
    const CMD_COMPILE_SETTINGS = '-V';
    const CMD_COMPILED_MODULES = '-l';
    const CMD_CONFIG_DIRECTIVES = '-L';
    const CMD_VHOSTS_SETTINGS = '-S';
    const CMD_LOADED_MODULES = '-M';
    const CMD_SYNTAX_CHECK = '-t';
    
    const TAG_START_SWITCHONLINE = '# START switchOnline tag - Do not replace!';
    const TAG_END_SWITCHONLINE = '# END switchOnline tag - Do not replace!';
    
    private $service;
    private $modulesPath;
    private $sslConf;
    private $accessLog;
    private $rewriteLog;
    private $errorLog;
    
    private $exe;
    private $conf;
    private $port;
    private $sslPort;
    private $opensslExe;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardBs, $neardConfig, $neardLang;

        $this->name = $neardLang->getValue(Lang::APACHE);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);

        $this->enable = $this->enable && $neardConfig->getRaw(self::ROOT_CFG_ENABLE);
        $this->service = new Win32Service(self::SERVICE_NAME);
        $this->modulesPath = $this->currentPath . '/modules';
        $this->sslConf = $this->currentPath . '/conf/extra/httpd-ssl.conf';
        $this->accessLog = $neardBs->getLogsPath() . '/apache_access.log';
        $this->rewriteLog = $neardBs->getLogsPath() . '/apache_rewrite.log';
        $this->errorLog = $neardBs->getLogsPath() . '/apache_error.log';

        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
            $this->port = $this->neardConfRaw[self::LOCAL_CFG_PORT];
            $this->sslPort = $this->neardConfRaw[self::LOCAL_CFG_SSL_PORT];
            $this->opensslExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_OPENSSL_EXE];
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
        if (!is_file($this->sslConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->sslConf));
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
        if (!is_numeric($this->sslPort) || $this->sslPort <= 0) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_SSL_PORT, $this->sslPort));
            return;
        }
        if (!is_file($this->opensslExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->opensslExe));
            return;
        }
        
        $nssm = new Nssm(self::SERVICE_NAME);
        $nssm->setDisplayName(APP_TITLE . ' ' . $this->getName() . ' ' . $this->version);
        $nssm->setBinPath($this->exe);
        $nssm->setStart(Nssm::SERVICE_DEMAND_START);
        
        $this->service->setNssm($nssm);
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
        global $neardLang, $neardWinbinder, $neardHomepage;
        $boxTitle = sprintf($neardLang->getValue(Lang::CHECK_PORT_TITLE), $this->getName(), $port);
        
        if (!Util::isValidPort($port)) {
            Util::logError($this->getName() . ' port not valid: ' . $port);
            return false;
        }
        
        $headers = Util::getHttpHeaders('http' . ($ssl ? 's' : '') . '://localhost:' . $port . '/' . $neardHomepage->getResourcesPath() . '/ping.php');
        if (!empty($headers)) {
            foreach ($headers as $row) {
                if (Util::startWith($row, 'Server: ')) {
                    Util::logDebug($this->getName() . ' port ' . $port . ' is used by: ' . $this->getName() . ' ' . str_replace('Server: ', '', trim($row)));
                    if ($showWindow) {
                        $neardWinbinder->messageBoxInfo(
                            sprintf($neardLang->getValue(Lang::PORT_USED_BY), $port, str_replace('Server: ', '', trim($row))),
                            $boxTitle
                        );
                    }
                    return true;
                }
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
        global $neardBs, $neardLang, $neardBins, $neardWinbinder;
        
        if (!$this->enable) {
            return true;
        }
        
        $version = $version == null ? $this->version : $version;
        Util::logDebug(($sub > 0 ? str_repeat(' ', 2 * $sub) : '') . 'Update ' . $this->name . ' ' . $version . ' config...');
        
        $boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->getName(), $version);
        
        $conf = str_replace('apache' . $this->getVersion(), 'apache' . $version, $this->getConf());
        $neardConf = str_replace('apache' . $this->getVersion(), 'apache' . $version, $this->neardConf);
        
        $tsDll = $neardBins->getPhp()->getTsDll();
        $apachePhpModuleName = $tsDll !== false ? substr($tsDll, 0, 4) . '_module' : null;
        $apachePhpModule = $neardBins->getPhp()->getApacheModule($version);
        
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
        
        if ($tsDll === false || $apachePhpModule === false) {
            Util::logDebug($this->getName() . ' ' . $version . ' does not seem to be compatible with PHP ' . $neardBins->getPhp()->getVersion());
            if ($showWindow) {
                $neardWinbinder->messageBoxError(
                    sprintf($neardLang->getValue(Lang::APACHE_INCPT), $version, $neardBins->getPhp()->getVersion()),
                    $boxTitle
                );
            }
            return false;
        }
        
        // neard.conf
        $this->setVersion($version);
        
        // conf
        Util::replaceInFile($conf, array(
            // PHP module
            '/^#?PHPIniDir\s.*/' => ($neardBins->getPhp()->isEnable() ? '' : '#') . 'PHPIniDir "' . $neardBins->getPhp()->getCurrentPath() . '"',
            '/^#?LoadFile\s.*php.ts\.dll.*/' => ($neardBins->getPhp()->isEnable() ? '' : '#') . (!file_exists($neardBins->getPhp()->getCurrentPath() . '/' . $tsDll) ? '#' : '') . 'LoadFile "' . $neardBins->getPhp()->getCurrentPath() . '/' . $tsDll . '"',
            '/^#?LoadModule\sphp._module\s.*/' => ($neardBins->getPhp()->isEnable() ? '' : '#') . 'LoadModule ' . $apachePhpModuleName . ' "' . $apachePhpModule . '"',
            
            // Since Neard 1.0.22 remove SVN Apache module
            // FIXME: Remove this
            '/^LoadModule\sauthz_svn_module\s.*tools\/svn\/svn.*/' => '',
            '/^LoadModule\sdav_svn_module\s.*tools\/svn\/svn.*/' => '',
            
            // Port
            '/^Listen\s(\d+)/' => 'Listen ' . $this->port,
            '/^ServerName\s+([a-zA-Z0-9.]+):(\d+)/' => 'ServerName {{1}}:' . $this->port,
            '/^NameVirtualHost\s+([a-zA-Z0-9.*]+):(\d+)/' => 'NameVirtualHost {{1}}:' . $this->port,
            '/^<VirtualHost\s+([a-zA-Z0-9.*]+):(\d+)>/' => '<VirtualHost {{1}}:' . $this->port . '>'
        ));
    
        // vhosts
        foreach ($this->getVhosts() as $vhost) {
            Util::replaceInFile($neardBs->getVhostsPath() . '/' . $vhost . '.conf', array(
                '/^<VirtualHost\s+([a-zA-Z0-9.*]+):(\d+)>$/' => '<VirtualHost {{1}}:' . $this->port . '>$'
            ));
        }
    
        // www .htaccess
        Util::replaceInFile($neardBs->getWwwPath() . '/.htaccess', array(
            '/(.*)http:\/\/localhost(.*)/' => '{{1}}http://localhost' . ($this->port != 80 ? ':' . $this->port : '') . '/$1 [QSA,R=301,L]',
        ));
        
        return true;
    }
    
    public function getModules() {
        $fromFolder = $this->getModulesFromFolder();
        $fromConf = $this->getModulesFromConf();
        $result = array_merge($fromFolder, $fromConf);
        ksort($result);
        return $result;
    }
    
    public function getModulesFromConf() {
        $result = array();
        
        if (!$this->enable) {
            return $result;
        }
    
        $confContent = file($this->getConf());
        foreach ($confContent as $row) {
            $modMatch = array();
            if (preg_match('/^(#)?LoadModule\s*([a-z0-9_-]+)\s*"?(.*)"?/i', $row, $modMatch)) {
                $name = $modMatch[2];
                //$path = $modMatch[3];
                if (!Util::startWith($name, 'php')) {
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
    
    public function getModulesLoaded() {
        $result = array();
        foreach ($this->getModulesFromConf() as $name => $status) {
            if ($status == ActionSwitchApacheModule::SWITCH_ON) {
                $result[] = $name;
            }
        }
        return $result;
    }
    
    private function getModulesFromFolder() {
        $result = array();
        
        if (!$this->enable) {
            return $result;
        }
        
        $handle = @opendir($this->getModulesPath());
        if (!$handle) {
            return $result;
        }
        
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && Util::startWith($file, 'mod_') && (Util::endWith($file, '.so') || Util::endWith($file, '.dll'))) {
                $name = str_replace(array('mod_', '.so', '.dll'), '', $file) . '_module';
                $result[$name] = ActionSwitchApacheModule::SWITCH_OFF;
            }
        }
        
        closedir($handle);
        ksort($result);
        return $result;
    }
    
    public function getAlias() {
        global $neardBs;
        $result = array();
        
        $handle = @opendir($neardBs->getAliasPath());
        if (!$handle) {
            return $result;
        }
        
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && Util::endWith($file, '.conf')) {
                $result[] = str_replace('.conf', '', $file);
            }
        }
        
        closedir($handle);
        ksort($result);
        return $result;
    }
    
    public function getVhosts() {
        global $neardBs;
        $result = array();
        
        $handle = @opendir($neardBs->getVhostsPath());
        if (!$handle) {
            return $result;
        }
    
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && Util::endWith($file, '.conf')) {
                $result[] = str_replace('.conf', '', $file);
            }
        }
        
        closedir($handle);
        ksort($result);
        return $result;
    }
    
    public function getVhostsUrl() {
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
    
    public function getWwwDirectories() {
        global $neardBs;
        $result = array();
        
        $handle = @opendir($neardBs->getWwwPath());
        if (!$handle) {
            return $result;
        }
    
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && is_dir($neardBs->getWwwPath() . '/' . $file)) {
                $result[] = $file;
            }
        }
        
        closedir($handle);
        ksort($result);
        return $result;
    }
    
    public function getCmdLineOutput($cmd) {
        $result = array(
            'syntaxOk' => false,
            'content'  => null,
        );
        
        if (file_exists($this->getExe())) {
            $tmpResult = Batch::exec('apacheGetCmdLineOutput', '"' . $this->getExe() . '" ' . $cmd);
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
    
    private function getOnlineContent($version = null) {
        $version = $version != null ? $version : $this->getVersion();
        $result = self::TAG_START_SWITCHONLINE . PHP_EOL;
        
        if (Util::startWith($version, '2.4')) {
            $result .= 'Require all granted' . PHP_EOL;
        } else {
            $result .= 'Order Allow,Deny' . PHP_EOL .
                'Allow from all' . PHP_EOL;
        }
        
        return $result . self::TAG_END_SWITCHONLINE;
    }
    
    private function getOfflineContent($version = null) {
        $version = $version != null ? $version : $this->getVersion();
        $result = self::TAG_START_SWITCHONLINE . PHP_EOL;
    
        if (Util::startWith($version, '2.4')) {
            $result .= 'Require local' . PHP_EOL;
        } else {
            $result .= 'Order Deny,Allow' . PHP_EOL .
                'Deny from all' . PHP_EOL .
                'Allow from 127.0.0.1 ::1' . PHP_EOL;
        }
    
        return $result . self::TAG_END_SWITCHONLINE;
    }
    
    private function getRequiredContent($version = null) {
        global $neardConfig;
        return $neardConfig->isOnline() ? $this->getOnlineContent($version) : $this->getOfflineContent($version);
    }
    
    public function getAliasContent($name, $dest) {
        $dest = Util::formatUnixPath($dest);
        return 'Alias /' . $name . ' "' . $dest . '"' . PHP_EOL . PHP_EOL .
            '<Directory "' . $dest . '">' . PHP_EOL .
            '    Options Indexes FollowSymLinks MultiViews' . PHP_EOL .
            '    AllowOverride all' . PHP_EOL .
            $this->getRequiredContent() . PHP_EOL .
            '</Directory>' . PHP_EOL;
    }
    
    public function getVhostContent($serverName, $documentRoot) {
        global $neardBs;
        
        $documentRoot = Util::formatUnixPath($documentRoot);
        return '<VirtualHost *:' . $this->getPort() . '>' . PHP_EOL .
            '    ServerAdmin webmaster@' . $serverName . PHP_EOL .
            '    DocumentRoot "' . $documentRoot . '"' . PHP_EOL .
            '    ServerName ' . $serverName . PHP_EOL .
            '    ErrorLog "' . $neardBs->getLogsPath() . '/' . $serverName . '_error.log"' . PHP_EOL .
            '    CustomLog "' . $neardBs->getLogsPath() . '/' . $serverName . '_access.log" combined' . PHP_EOL . PHP_EOL .
            '    <Directory "' . $documentRoot . '">' . PHP_EOL .
            '        Options Indexes FollowSymLinks MultiViews' . PHP_EOL .
            '        AllowOverride all' . PHP_EOL .
            $this->getRequiredContent() . PHP_EOL .
            '    </Directory>' . PHP_EOL .
            '</VirtualHost>' . PHP_EOL . PHP_EOL .
            '<IfModule ssl_module>' . PHP_EOL .
            '<VirtualHost *:' . $this->getSslPort() . '> #SSL' . PHP_EOL .
            '    DocumentRoot "' . $documentRoot . '"' . PHP_EOL .
            '    ServerName ' . $serverName . PHP_EOL .
            '    ServerAdmin webmaster@' . $serverName . PHP_EOL .
            '    ErrorLog "' . $neardBs->getLogsPath() . '/' . $serverName . '_error.log"' . PHP_EOL .
            '    TransferLog "' . $neardBs->getLogsPath() . '/' . $serverName . '_access.log"' . PHP_EOL . PHP_EOL .
            '    SSLEngine on' . PHP_EOL .
            '    SSLProtocol all -SSLv2' . PHP_EOL .
            '    SSLCipherSuite HIGH:MEDIUM:!aNULL:!MD5' . PHP_EOL .
            '    SSLCertificateFile "' . $neardBs->getSslPath() . '/' . $serverName . '.crt"' . PHP_EOL .
            '    SSLCertificateKeyFile "' . $neardBs->getSslPath() . '/' . $serverName . '.pub"' . PHP_EOL .
            '    BrowserMatch "MSIE [2-5]" nokeepalive ssl-unclean-shutdown downgrade-1.0 force-response-1.0' . PHP_EOL .
            '    CustomLog "' . $neardBs->getLogsPath() . '/' . $serverName . '_sslreq.log" "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"' . PHP_EOL .PHP_EOL .
            '    <Directory "' . $documentRoot . '">' . PHP_EOL .
            '        SSLOptions +StdEnvVars' . PHP_EOL .
            '        Options Indexes FollowSymLinks MultiViews' . PHP_EOL .
            '        AllowOverride all' . PHP_EOL .
            $this->getRequiredContent() . PHP_EOL .
            '    </Directory>' . PHP_EOL .
            '</VirtualHost>' . PHP_EOL .
            '</IfModule>' . PHP_EOL;
    }
    
    public function refreshConf($putOnline) {
        if (!$this->enable) {
            return;
        }
        
        $onlineContent = $this->getOnlineContent();
        $offlineContent = $this->getOfflineContent();
        
        $conf = file_get_contents($this->getConf());
        Util::logTrace('refreshConf ' . $this->getConf());
        preg_match('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $conf, $matches);
        Util::logTrace(isset($matches[1]) ? print_r($matches[1], true) : 'N/A');
        
        if ($putOnline) {
            $conf = preg_replace('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $onlineContent, $conf, -1, $count);
        } else {
            $conf = preg_replace('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $offlineContent, $conf, -1, $count);
        }
        file_put_contents($this->getConf(), $conf);
        Util::logDebug('Refresh ' . $this->getConf() . ': ' . $count . ' occurrence(s) replaced');
        
        $sslConf = file_get_contents($this->getSslConf());
        Util::logTrace('refreshConf ' . $this->getSslConf());
        preg_match('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $sslConf, $matches);
        Util::logTrace(isset($matches[1]) ? print_r($matches[1], true) : 'N/A');
        
        if ($putOnline) {
            $sslConf = preg_replace('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $onlineContent, $sslConf, -1, $count);
        } else {
            $sslConf = preg_replace('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $offlineContent, $sslConf, -1, $count);
        }
        file_put_contents($this->getSslConf(), $sslConf);
        Util::logDebug('Refresh ' . $this->getSslConf() . ': ' . $count . ' occurrence(s) replaced');
    }
    
    public function refreshAlias($putOnline) {
        global $neardBs, $neardHomepage;
        
        if (!$this->enable) {
            return;
        }
        
        $onlineContent = $this->getOnlineContent();
        $offlineContent = $this->getOfflineContent();
        
        foreach ($this->getAlias() as $alias) {
            $aliasConf = file_get_contents($neardBs->getAliasPath() . '/' . $alias . '.conf');
            Util::logTrace('refreshAlias ' . $neardBs->getAliasPath() . '/' . $alias . '.conf');
            preg_match('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $aliasConf, $matches);
            Util::logTrace(isset($matches[1]) ? print_r($matches[1], true) : 'N/A');
            
            if ($putOnline) {
                $aliasConf= preg_replace('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $onlineContent, $aliasConf, -1, $count);
            } else {
                $aliasConf= preg_replace('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $offlineContent, $aliasConf, -1, $count);
            }
            file_put_contents($neardBs->getAliasPath() . '/' . $alias . '.conf', $aliasConf);
            Util::logDebug('Refresh ' . $neardBs->getAliasPath() . '/' . $alias . '.conf: ' . $count . ' occurrence(s) replaced');
        }
        
        // Homepage
        $neardHomepage->refreshAliasContent();
    }
    
    public function refreshVhosts($putOnline) {
        global $neardBs;
        
        if (!$this->enable) {
            return;
        }
        
        $onlineContent = $this->getOnlineContent();
        $offlineContent = $this->getOfflineContent();
        
        foreach ($this->getVhosts() as $vhost) {
            $vhostConf = file_get_contents($neardBs->getVhostsPath() . '/' . $vhost . '.conf');
            Util::logTrace('refreshVhost ' . $neardBs->getVhostsPath() . '/' . $vhost . '.conf');
            preg_match('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $vhostConf, $matches);
            Util::logTrace(isset($matches[1]) ? print_r($matches[1], true) : 'N/A');
            
            if ($putOnline) {
                $vhostConf= preg_replace('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $onlineContent, $vhostConf, -1, $count);
            } else {
                $vhostConf= preg_replace('/' . self::TAG_START_SWITCHONLINE . '(.*?)' . self::TAG_END_SWITCHONLINE . '/s', $offlineContent, $vhostConf, -1, $count);
            }
            file_put_contents($neardBs->getVhostsPath() . '/' . $vhost . '.conf', $vhostConf);
            Util::logDebug('Refresh ' . $neardBs->getVhostsPath() . '/' . $vhost . '.conf: ' . $count . ' occurrence(s) replaced');
        }
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
    
    public function setVersion($version) {
        global $neardConfig;
        $this->version = $version;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
    }

    public function getService() {
        return $this->service;
    }
    
    public function getModulesPath() {
        return $this->modulesPath;
    }
    
    public function getSslConf() {
        return $this->sslConf;
    }
    
    public function getAccessLog() {
        return $this->accessLog;
    }
    
    public function getRewriteLog() {
        return $this->rewriteLog;
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
    
    public function getSslPort() {
        return $this->sslPort;
    }
    
    public function setSslPort($sslPort) {
        $this->replace(self::LOCAL_CFG_SSL_PORT, $sslPort);
    }
    
    public function getOpensslExe() {
        return $this->opensslExe;
    }
}
