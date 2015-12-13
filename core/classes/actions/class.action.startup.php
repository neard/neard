<?php

class ActionStartup
{
    private $splash;
    private $restart;
    private $startTime;
    private $error;
    
    private $rootPath;
    private $filesToScan;
    
    const GAUGE_SERVICES = 6;
    const GAUGE_OTHERS = 17;
    
    public function __construct($args)
    {
        global $neardBs, $neardCore, $neardLang, $neardBins, $neardWinbinder;
        $this->writeLog('Starting Neard...');
        
        // Init
        $this->splash = new Splash();
        $this->restart = false;
        $this->startTime = Util::getMicrotime();
        $this->error = '';
        
        $this->rootPath = $neardBs->getRootPath();
        $this->filesToScan = array();
        
        $gauge = self::GAUGE_SERVICES * count($neardBins->getServicesStartup());
        $gauge += self::GAUGE_OTHERS + 1;
        
        // Start splash screen
        $this->splash->init(
            $neardLang->getValue(Lang::STARTUP),
            $gauge,
            sprintf($neardLang->getValue(Lang::STARTUP_STARTING_TEXT), APP_TITLE . ' ' . $neardCore->getAppVersion())
        );
        
        $neardWinbinder->setHandler($this->splash->getWbWindow(), $this, 'processWindow', 1000);
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBs, $neardCore, $neardLang, $neardBins, $neardWinbinder;
        
        // Rotation logs
        $this->rotationLogs();
        
        // Clean
        $this->cleanTmpFolders();
        $this->cleanOldBehaviors();
        
        // List procs
        if ($neardBs->getProcs() !== false) {
            $this->writeLog('List procs:');
            $listProcs = array();
            foreach ($neardBs->getProcs() as $proc) {
                $unixExePath = Util::formatUnixPath($proc[Win32Ps::EXECUTABLE_PATH]);
                $listProcs[] = '-> ' . basename($unixExePath) . ' (PID ' . $proc[Win32Ps::PROCESS_ID] . ') in ' . $unixExePath;
            }
            sort($listProcs);
            foreach ($listProcs as $proc) {
                $this->writeLog($proc);
            }
        }
        
        // Kill old PHP instances
        $this->killPhpInstances();
        
        // Prepare Neard
        $this->refreshHostname();
        $this->checkLaunchStartup();
        $this->checkBrowser();
        $this->refreshAliases();
        $this->refreshVhosts();
        
        // Check Neard path
        $this->checkPath();
        $this->scanFolders();
        $this->changePath();
        $this->savePath();
        
        // Check NEARD_PATH, NEARD_BINS and System Path reg keys
        $this->checkPathRegKey();
        $this->checkBinsRegKey();
        $this->checkSystemPathRegKey();
        
        // Install
        $this->installServices();
        
        // Actions if everything OK
        if (!$this->restart && empty($this->error)) {
            $this->refreshGitRepos();
            $this->refreshSvnRepos();
            $this->writeLog('Started in ' . round(Util::getMicrotime() - $this->startTime, 3) . 's');
        } else {
            $this->splash->incrProgressBar(2);
        }
        
        if ($this->restart) {
            $this->writeLog('Neard have to be restarted');
            $this->splash->setTextLoading(sprintf(
                $neardLang->getValue(Lang::STARTUP_PREPARE_RESTART_TEXT),
                APP_TITLE . ' ' . $neardCore->getAppVersion())
            );
            foreach ($neardBins->getServices() as $sName => $service) {
                $service->delete();
            }
            $neardCore->setExec(ActionExec::RESTART);
        }
        
        if (!empty($this->error)) {
            $this->writeLog('Error: ' . $this->error);
            $neardWinbinder->messageBoxError($this->error, $neardLang->getValue(Lang::STARTUP_ERROR_TITLE));
        }
        
        Util::startLoading();
        $neardWinbinder->destroyWindow($window);
    }
    
    private function rotationLogs()
    {
        global $neardBs, $neardCore, $neardConfig, $neardLang, $neardBins;
    
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_ROTATION_LOGS_TEXT));
        $this->splash->incrProgressBar();
    
        $archivesPath = $neardBs->getLogsPath() . '/archives';
        if (!is_dir($archivesPath)) {
            mkdir($archivesPath, 0777, true);
            return;
        }
    
        $date = date('Y-m-d-His', time());
        $archiveLogsPath = $archivesPath . '/' . $date;
        $archiveScriptsPath = $archiveLogsPath . '/scripts';
    
        // Create archive folders
        mkdir($archiveLogsPath, 0777, true);
        mkdir($archiveScriptsPath, 0777, true);
    
        // Count archives
        $archives = array();
        $handle = @opendir($archivesPath);
        if (!$handle) {
            return;
        }
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $archives[] = $archivesPath . '/' . $file;
        }
        closedir($handle);
        sort($archives);
    
        // Remove old archives
        if (count($archives) > $neardConfig->getMaxLogsArchives()) {
            $total = count($archives) - $neardConfig->getMaxLogsArchives();
            for ($i = 0; $i < $total; $i++) {
                Util::deleteFolder($archives[$i]);
            }
        }
    
        // Logs
        $srcPath = $neardBs->getLogsPath();
        $handle = @opendir($srcPath);
        if (!$handle) {
            return;
        }
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..' || is_dir($srcPath . '/' . $file)) {
                continue;
            }
            copy($srcPath . '/' . $file, $archiveLogsPath . '/' . $file);
        }
        closedir($handle);
    
        // Scripts
        $srcPath = $neardCore->getTmpPath();
        $handle = @opendir($srcPath);
        if (!$handle) {
            return;
        }
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..' || is_dir($srcPath . '/' . $file)) {
                continue;
            }
            copy($srcPath . '/' . $file, $archiveScriptsPath . '/' . $file);
        }
        closedir($handle);
        
        // Purge logs
        Util::clearFolders($neardBins->getLogsPath(), array('placeholder'));
        Util::clearFolder($neardBs->getLogsPath(), array('archives', 'placeholder'));
    }
    
    private function cleanTmpFolders()
    {
        global $neardBs, $neardLang, $neardCore;
    
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_CLEAN_TMP_TEXT));
        $this->splash->incrProgressBar();
    
        $this->writeLog('Clear tmp folders');
        Util::clearFolder($neardBs->getTmpPath(), array('placeholder'));
        Util::clearFolder($neardCore->getTmpPath(), array('placeholder'));
    }
    
    private function cleanOldBehaviors()
    {
        global $neardLang, $neardRegistry;
        
        $this->writeLog('Clean old behaviors');
        
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_CLEAN_OLD_BEAHAVIORS_TEXT));
        $this->splash->incrProgressBar();
        
        // Neard >= 1.0.13
        $neardRegistry->deleteValue(
            Registry::HKEY_LOCAL_MACHINE,
            'SOFTWARE\Microsoft\Windows\CurrentVersion\Run',
            'Neard'
        );
    }
    
    private function killPhpInstances()
    {
        global $neardCore, $neardLang;
    
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_KILL_OLD_PROCS_TEXT));
        $this->splash->incrProgressBar();
        $procsKilled = Win32Ps::killBins();
        
        if (!empty($procsKilled)) {
            $this->writeLog('Procs killed:');
            $procsKilledSort = array();
            foreach ($procsKilled as $proc) {
                $unixExePath = Util::formatUnixPath($proc[Win32Ps::EXECUTABLE_PATH]);
                $procsKilledSort[] = '-> ' . basename($unixExePath) . ' (PID ' . $proc[Win32Ps::PROCESS_ID] . ') in ' . $unixExePath;
            }
            sort($procsKilledSort);
            foreach ($procsKilledSort as $proc) {
                $this->writeLog($proc);
            }
        }
    }
    
    private function refreshHostname()
    {
        global $neardConfig, $neardLang;
        
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_HOSTNAME_TEXT));
        $this->splash->incrProgressBar();
        $this->writeLog('Refresh hostname');
        
        $neardConfig->replace(Config::CFG_HOSTNAME, gethostname());
    }
    
    private function checkLaunchStartup()
    {
        global $neardConfig;
        
        $this->writeLog('Check launch startup');
        
        if ($neardConfig->isLaunchStartup()) {
            Util::enableLaunchStartup();
        } else {
            Util::disableLaunchStartup();
        }
    }
    
    private function checkBrowser()
    {
        global $neardConfig, $neardLang;
        
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_CHECK_BROWSER_TEXT));
        $this->splash->incrProgressBar();
        $this->writeLog('Check browser');
        
        $currentBrowser = $neardConfig->getBrowser();
        if (empty($currentBrowser) || !file_exists($currentBrowser)) {
            $neardConfig->replace(Config::CFG_BROWSER, Vbs::getDefaultBrowser());
        }
    }
    
    private function refreshAliases()
    {
        global $neardConfig, $neardLang, $neardBins;
        
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_ALIAS_TEXT));
        $this->splash->incrProgressBar();
        $this->writeLog('Refresh aliases');
        
        $neardBins->getApache()->refreshAlias($neardConfig->isOnline());
    }
    
    private function refreshVhosts()
    {
        global $neardConfig, $neardLang, $neardBins;
        
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_VHOSTS_TEXT));
        $this->splash->incrProgressBar();
        $this->writeLog('Refresh vhosts');
        
        $neardBins->getApache()->refreshVhosts($neardConfig->isOnline());
    }
    
    private function checkPath()
    {
        global $neardCore, $neardLang;
        
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_CHECK_PATH_TEXT));
        $this->splash->incrProgressBar();
        
        $this->writeLog('Last path: ' . $neardCore->getLastPathContent());
    }
    
    private function scanFolders()
    {
        global $neardLang;
        
        $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_SCAN_FOLDERS_TEXT));
        $this->splash->incrProgressBar();
        
        $this->filesToScan = Util::getFilesToScan();
        $this->writeLog('Files to scan: ' . count($this->filesToScan));
    }
    
    private function changePath()
    {
        global $neardCore, $neardLang;
        
        $this->splash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_CHANGE_PATH_TEXT), $this->rootPath));
        $this->splash->incrProgressBar();
        
        $unixOldPath = Util::formatUnixPath($neardCore->getLastPathContent());
        $windowsOldPath = Util::formatWindowsPath($neardCore->getLastPathContent());
        $unixCurrentPath = Util::formatUnixPath($this->rootPath);
        $windowsCurrentPath = Util::formatWindowsPath($this->rootPath);
        $countChangedOcc = 0;
        $countChangedFiles = 0;
        
        foreach ($this->filesToScan as $fileToScan) {
            $tmpCountChangedOcc = 0;
            $fileContentOr = file_get_contents($fileToScan);
            $fileContent = $fileContentOr;
            
            // old path
            preg_match('#' . $unixOldPath . '#i', $fileContent, $unixMatches);
            if (!empty($unixMatches)) {
                $fileContent = str_replace($unixOldPath, $unixCurrentPath, $fileContent, $countChanged);
                $tmpCountChangedOcc += $countChanged;
            }
            preg_match('#' . str_replace('\\', '\\\\', $windowsOldPath) . '#i', $fileContent, $windowsMatches);
            if (!empty($windowsMatches)) {
                $fileContent = str_replace($windowsOldPath, $windowsCurrentPath, $fileContent, $countChanged);
                $tmpCountChangedOcc += $countChanged;
            }
            
            // placeholders
            preg_match('#' . Core::PATH_LIN_PLACEHOLDER . '#i', $fileContent, $unixMatches);
            if (!empty($unixMatches)) {
                $fileContent = str_replace(Core::PATH_LIN_PLACEHOLDER, $unixCurrentPath, $fileContent, $countChanged);
                $tmpCountChangedOcc += $countChanged;
            }
            preg_match('#' . Core::PATH_WIN_PLACEHOLDER . '#i', $fileContent, $windowsMatches);
            if (!empty($windowsMatches)) {
                $fileContent = str_replace(Core::PATH_WIN_PLACEHOLDER, $windowsCurrentPath, $fileContent, $countChanged);
                $tmpCountChangedOcc += $countChanged;
            }
            
            if ($fileContentOr != $fileContent) {
                $countChangedFiles++;
                $countChangedOcc += $tmpCountChangedOcc;
                file_put_contents($fileToScan, $fileContent);
            }
        }
        
        $this->writeLog('Nb files changed: ' . $countChangedFiles);
        $this->writeLog('Nb occurences changed: ' . $countChangedOcc);
    }
    
    private function savePath()
    {
        global $neardCore;
        
        file_put_contents($neardCore->getLastPath(), $this->rootPath);
        $this->writeLog('Save current path: ' . $this->rootPath);
    }
    
    private function checkPathRegKey()
    {
        global $neardBs, $neardLang, $neardRegistry;
        
        $this->splash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_TEXT), Registry::APP_PATH_REG_ENTRY));
        $this->splash->incrProgressBar();
        
        $currentAppPathRegKey = Util::getAppPathRegKey();
        $genAppPathRegKey = Util::formatWindowsPath($neardBs->getRootPath());
        $this->writeLog('Current app path reg key: ' . $currentAppPathRegKey);
        $this->writeLog('Gen app path reg key: ' . $genAppPathRegKey);
        if ($currentAppPathRegKey != $genAppPathRegKey) {
            if (!Util::setAppPathRegKey($genAppPathRegKey)) {
                if (!empty($this->error)) {
                    $this->error .= PHP_EOL . PHP_EOL;
                }
                $this->error .= sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_ERROR_TEXT), Registry::APP_PATH_REG_ENTRY);
                $this->error .= PHP_EOL . $neardRegistry->getLatestError();
            } else {
                $this->restart = true;
            }
        }
    }
    
    private function checkBinsRegKey()
    {
        global $neardLang, $neardRegistry;
        
        $this->splash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_TEXT), Registry::APP_BINS_REG_ENTRY));
        $this->splash->incrProgressBar();
        
        $currentAppBinsRegKey = Util::getAppBinsRegKey();
        $genAppBinsRegKey = Util::getAppBinsRegKey(false);
        $this->writeLog('Current app bins reg key: ' . $currentAppBinsRegKey);
        $this->writeLog('Gen app bins reg key: ' . $genAppBinsRegKey);
        if ($currentAppBinsRegKey != $genAppBinsRegKey) {
            if (!Util::setAppBinsRegKey($genAppBinsRegKey)) {
                if (!empty($this->error)) {
                    $this->error .= PHP_EOL . PHP_EOL;
                }
                $this->error .= sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_ERROR_TEXT), Registry::APP_BINS_REG_ENTRY);
                $this->error .= PHP_EOL . $neardRegistry->getLatestError();
            } else {
                $this->restart = true;
            }
        }
    }
    
    private function checkSystemPathRegKey()
    {
        global $neardLang, $neardRegistry;
        
        $this->splash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_TEXT), Registry::SYSPATH_REG_ENTRY));
        $this->splash->incrProgressBar();
        
        $currentSysPathRegKey = Util::getSysPathRegKey();
        $this->writeLog('Current system PATH: ' . $currentSysPathRegKey);
        if (!Util::contains($currentSysPathRegKey, '%' . Registry::APP_BINS_REG_ENTRY . '%')) {
            if (!Util::endWith($currentSysPathRegKey, ';')) {
                $currentSysPathRegKey .= ';';
            }
            $currentSysPathRegKey .= '%' . Registry::APP_BINS_REG_ENTRY . '%';
            $this->writeLog('New system PATH: ' . $currentSysPathRegKey);
            if (!Util::setSysPathRegKey($currentSysPathRegKey)) {
                if (!empty($this->error)) {
                    $this->error .= PHP_EOL . PHP_EOL;
                }
                $this->error .= sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_ERROR_TEXT), Registry::SYSPATH_REG_ENTRY);
                $this->error .= PHP_EOL . $neardRegistry->getLatestError();
            } else {
                $this->restart = true;
            }
        } else {
            $this->writeLog('Refresh system PATH: ' . $currentSysPathRegKey);
            Util::setSysPathRegKey(str_replace('%' . Registry::APP_BINS_REG_ENTRY . '%', '', $currentSysPathRegKey));
            Util::setSysPathRegKey($currentSysPathRegKey);
        }
    }
    
    private function installServices()
    {
        global $neardLang, $neardBins;
        
        if (!$this->restart) {
            foreach ($neardBins->getServicesStartup() as $sName => $service) {
                $serviceError = '';
                $serviceRestart = false;
                $startServiceTime = Util::getMicrotime();
        
                $syntaxCheckCmd = null;
                if ($sName == BinApache::SERVICE_NAME) {
                    $bin = $neardBins->getApache();
                    $syntaxCheckCmd = BinApache::CMD_SYNTAX_CHECK;
                } elseif ($sName == BinMysql::SERVICE_NAME) {
                    $bin = $neardBins->getMysql();
                    $syntaxCheckCmd = BinMysql::CMD_SYNTAX_CHECK;
                } elseif ($sName == BinMariadb::SERVICE_NAME) {
                    $bin = $neardBins->getMariadb();
                    $syntaxCheckCmd = BinMariadb::CMD_SYNTAX_CHECK;
                } elseif ($sName == BinFilezilla::SERVICE_NAME) {
                    $bin = $neardBins->getFilezilla();
                }
        
                $name = $bin->getName() . ' ' . $bin->getVersion() . ' (' . $service->getName() . ')';
                $port = $bin->getPort();
        
                $this->splash->incrProgressBar();
                $this->splash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_INSTALL_SERVICE_TEXT), $name));
        
                $this->splash->incrProgressBar();
                if (!$service->delete()) {
                    $serviceRestart = true;
                }
        
                $this->splash->incrProgressBar();
                if ($bin->changePort($port) !== true) {
                    $serviceError .= sprintf($neardLang->getValue(Lang::STARTUP_PORT_ERROR), $port);
                }
        
                if (!$serviceRestart) {
                    $isPortInUse = Util::isPortInUse($port);
                    if ($isPortInUse === false) {
                        $this->splash->incrProgressBar();
                        if (!$service->create()) {
                            $serviceError .= sprintf($neardLang->getValue(Lang::STARTUP_SERVICE_CREATE_ERROR), $service->getError());
                        }
        
                        if ($sName == BinApache::SERVICE_NAME && !$neardBins->getApache()->existsSslCrt()) {
                            $this->splash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_GEN_SSL_CRT_TEXT), 'localhost'));
                            Batch::genSslCertificate('localhost');
                        } elseif ($sName == BinFilezilla::SERVICE_NAME && !$neardBins->getFilezilla()->existsSslCrt()) {
                            $this->splash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_GEN_SSL_CRT_TEXT), $neardLang->getValue(Lang::FILEZILLA)));
                            Batch::genSslCertificate(BinFilezilla::SERVICE_NAME);
                        }
        
                        $this->splash->incrProgressBar();
                        $this->splash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_START_SERVICE_TEXT), $name));
                        if (!$service->start()) {
                            if (!empty($serviceError)) {
                                $serviceError .= PHP_EOL;
                            }
                            $serviceError .= sprintf($neardLang->getValue(Lang::STARTUP_SERVICE_START_ERROR), $service->getError());
                            if (!empty($syntaxCheckCmd)) {
                                $cmdSyntaxCheck = $bin->getCmdLineOutput($syntaxCheckCmd);
                                if (!$cmdSyntaxCheck['syntaxOk']) {
                                    $serviceError .= PHP_EOL . sprintf($neardLang->getValue(Lang::STARTUP_SERVICE_SYNTAX_ERROR), $cmdSyntaxCheck['content']);
                                }
                            }
                        }
                        $this->splash->incrProgressBar();
                    } else {
                        if (!empty($serviceError)) {
                            $serviceError .= PHP_EOL;
                        }
                        $serviceError .= sprintf($neardLang->getValue(Lang::STARTUP_SERVICE_PORT_ERROR), $port, $isPortInUse);
                        $this->splash->incrProgressBar(3);
                    }
                } else {
                    $this->restart = true;
                    $this->splash->incrProgressBar(3);
                }
        
                if (!empty($serviceError)) {
                    if (!empty($this->error)) {
                        $this->error .= PHP_EOL . PHP_EOL;
                    }
                    $this->error .= sprintf($neardLang->getValue(Lang::STARTUP_SERVICE_ERROR), $name) . PHP_EOL . $serviceError;
                } else {
                    $this->writeLog($name . ' service installed in ' . round(Util::getMicrotime() - $startServiceTime, 3) . 's');
                }
            }
        } else {
            $this->splash->incrProgressBar(self::GAUGE_SERVICES * count($neardBins->getServicesStartup()));
        }
    }
    
    private function refreshGitRepos()
    {
        global $neardLang, $neardTools;
        
        $this->splash->incrProgressBar();
        if ($neardTools->getGit()->isScanStartup()) {
            $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_GIT_REPOS_TEXT));
            
            $repos = $neardTools->getGit()->findRepos(false);
            $this->writeLog('Update GIT repos: ' . count($repos) . ' found');
        }
    }
    
    private function refreshSvnRepos()
    {
        global $neardLang, $neardTools;
        
        $this->splash->incrProgressBar();
        if ($neardTools->getSvn()->isScanStartup()) {
            $this->splash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_SVN_REPOS_TEXT));
            
            $repos = $neardTools->getSvn()->findRepos(false);
            $this->writeLog('Update SVN repos: ' . count($repos) . ' found');
        }
    }
    
    private function writeLog($log)
    {
        global $neardBs;
        Util::logDebug($log, $neardBs->getStartupLogFilePath());
    }
}
