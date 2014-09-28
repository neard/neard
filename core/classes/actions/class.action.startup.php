<?php

class ActionStartup
{
    private $neardSplash;
    private $procs;
    private $restart;
    private $startTime;
    private $error;
    
    private $oldPaths;
    private $rootPath;
    private $filesToScan;
    
    const GAUGE_SERVICES = 6;
    const GAUGE_OTHERS = 15;
    
    public function __construct($args)
    {
        global $neardBs, $neardCore, $neardLang, $neardBins, $neardWinbinder;
        
        // Init
        $this->neardSplash = new Splash();
        $this->procs = Win32Ps::getListProcs();
        $this->restart = false;
        $this->startTime = Util::getMicrotime();
        $this->error = '';
        
        $this->oldPaths = Util::getAppPaths();
        $this->rootPath = $neardBs->getRootPath();
        $this->filesToScan = array();
        
        $gauge = self::GAUGE_SERVICES * count($neardBins->getServicesStartup());
        $gauge += self::GAUGE_OTHERS + 1;
        
        // Start splash screen
        $this->neardSplash->init(
            $neardLang->getValue(Lang::STARTUP),
            $gauge,
            sprintf($neardLang->getValue(Lang::STARTUP_STARTING_TEXT), APP_TITLE . ' ' . $neardCore->getAppVersion()),
            Splash::IMG_STARTING
        );
        
        $neardWinbinder->setHandler($this->neardSplash->getWbWindow(), $this, 'processWindow', 1000);
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardCore, $neardLang, $neardBins, $neardWinbinder;
        
        // Clean
        $this->cleanTmpFolders();
        $this->purgeLogs();
        
        // List procs
        if ($this->procs !== false) {
            $this->writeLog('List procs:');
            $listProcs = array();
            foreach ($this->procs as $proc) {
                $unixExePath = Util::formatUnixPath($proc[Win32Ps::PATH]);
                $listProcs[] = '-> ' . basename($unixExePath) . ' (PID ' . $proc[Win32Ps::PID] . ') in ' . $unixExePath;
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
        
        // Check Neard path
        $this->checkPath();
        $this->scanFolders();
        $this->changeOldPaths();
        $this->savePaths();
        
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
            $this->neardSplash->incrProgressBar(2);
        }
        
        if ($this->restart) {
            $this->writeLog('Restart App');
            $this->neardSplash->setTextLoading(sprintf(
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
            /*foreach ($neardBins->getServices() as $sName => $service) {
                $service->delete();
            }*/
            $neardWinbinder->messageBoxError($this->error, $neardLang->getValue(Lang::STARTUP_ERROR_TITLE));
            //$neardCore->setExec(ActionExec::QUIT);
        }
        
        Util::startLoading();
        $neardWinbinder->destroyWindow($window);
        
        exit();
    }
    
    private function cleanTmpFolders()
    {
        global $neardBs, $neardLang, $neardCore;
    
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_CLEAN_TMP_TEXT));
        $this->neardSplash->incrProgressBar();
    
        $this->writeLog('Clear tmp folders');
        Util::clearFolder($neardBs->getTmpPath(), array('placeholder'));
        Util::clearFolder($neardCore->getTmpPath(), array('placeholder'));
    }
    
    private function purgeLogs()
    {
        global $neardBs, $neardConfig, $neardLang, $neardBins;
    
        $this->neardSplash->incrProgressBar();
    
        if ($neardConfig->isPurgeLogsOnStartup()) {
            $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_PURGE_LOGS_TEXT));
            Util::clearFolders($neardBins->getLogsPath(), array('placeholder'));
            Util::clearFolder($neardBs->getLogsPath(), array('placeholder'));
            $this->writeLog('Purge logs');
        }
    }
    
    private function killPhpInstances()
    {
        global $neardCore, $neardLang;
    
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_KILL_PHP_PROCS_TEXT));
        $this->neardSplash->incrProgressBar();
    
        if ($this->procs !== false) {
            foreach ($this->procs as $proc) {
                $unixExePath = Util::formatUnixPath($proc[Win32Ps::PATH]);
                if ($unixExePath == $neardCore->getPhpCliSilentExe() && $proc[Win32Ps::PID] != Win32Ps::getCurrentPid()) {
                    $this->writeLog('Kill process ' . basename($unixExePath) . ' (PID ' . $proc[Win32Ps::PID] . ')');
                    Win32Ps::kill($proc[Win32Ps::PID]);
                }
            }
        }
    }
    
    private function refreshHostname()
    {
        global $neardConfig, $neardLang;
        
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_HOSTNAME_TEXT));
        $this->neardSplash->incrProgressBar();
        $this->writeLog('Refresh hostname');
        
        $neardConfig->replace(Config::CFG_HOSTNAME, gethostname());
    }
    
    private function checkLaunchStartup()
    {
        global $neardConfig;
        
        $this->writeLog('Check launch startup');
        
        if ($neardConfig->isLaunchStartup()) {
            Util::setLaunchStartupRegKey();
        } else {
            Util::deleteLaunchStartupRegKey();
        }
    }
    
    private function checkBrowser()
    {
        global $neardConfig, $neardLang;
        
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_CHECK_BROWSER_TEXT));
        $this->neardSplash->incrProgressBar();
        $this->writeLog('Check browser');
        
        $currentBrowser = $neardConfig->getBrowser();
        if (empty($currentBrowser) || !file_exists($currentBrowser)) {
            $neardConfig->replace(Config::CFG_BROWSER, Vbs::getDefaultBrowser());
        }
    }
    
    private function refreshAliases()
    {
        global $neardConfig, $neardLang, $neardBins;
        
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_ALIAS_TEXT));
        $this->neardSplash->incrProgressBar();
        $this->writeLog('Refresh aliases');
        
        $neardBins->getApache()->refreshAlias($neardConfig->isOnline());
    }
    
    private function refreshVhosts()
    {
        global $neardConfig, $neardLang, $neardBins;
        
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_VHOSTS_TEXT));
        $this->neardSplash->incrProgressBar();
        $this->writeLog('Refresh vhosts');
        
        $neardBins->getApache()->refreshVhosts($neardConfig->isOnline());
    }
    
    private function checkPath()
    {
        global $neardBs, $neardLang;
        
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_CHECK_PATH_TEXT));
        $this->neardSplash->incrProgressBar();
        
        $this->writeLog('Old app paths: ' . implode(' ; ', $this->oldPaths));
    }
    
    private function scanFolders()
    {
        global $neardLang;
        
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_SCAN_FOLDERS_TEXT));
        $this->neardSplash->incrProgressBar();
        
        $this->filesToScan = Util::getFilesToScan();
        $this->writeLog('Files to scan: ' . count($this->filesToScan));
    }
    
    private function changeOldPaths()
    {
        global $neardLang;
        
        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_CHANGE_OLD_PATHS_TEXT), $this->rootPath));
        $this->neardSplash->incrProgressBar();
        
        $unixCurrentPath = Util::formatUnixPath($this->rootPath);
        $windowsCurrentPath = Util::formatWindowsPath($this->rootPath);
        $countChangedOcc = 0;
        $countChangedFiles = 0;
        foreach ($this->filesToScan as $fileToScan) {
            $tmpCountChangedOcc = 0;
            $fileContentOr = file_get_contents($fileToScan);
            $fileContent = $fileContentOr;
            foreach ($this->oldPaths as $oldPath) {
                $unixOldPath = Util::formatUnixPath($oldPath);
                $windowsOldPath = Util::formatWindowsPath($oldPath);
                preg_match('#' . $unixOldPath . '#i', $fileContent, $unixMatches);
                preg_match('#' . str_replace('\\', '\\\\', $windowsOldPath) . '#i', $fileContent, $windowsMatches);
                if (!empty($unixMatches)) {
                    $fileContent = str_replace($unixOldPath, $unixCurrentPath, $fileContent, $countChanged);
                    $tmpCountChangedOcc += $countChanged;
                }
                if (!empty($windowsMatches)) {
                    $fileContent = str_replace($windowsOldPath, $windowsCurrentPath, $fileContent, $countChanged);
                    $tmpCountChangedOcc += $countChanged;
                }
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
    
    private function savePaths()
    {
        global $neardCore;
        
        if (!in_array($this->rootPath, $this->oldPaths)) {
            if (count($this->oldPaths) > 5) {
                unset($appPaths[0]);
            }
            $oldPaths[] = $this->rootPath;
            file_put_contents($neardCore->getAppPaths(), implode(PHP_EOL, $oldPaths));
            $this->writeLog('Save current path: ' . $this->rootPath);
        }
    }
    
    private function checkPathRegKey()
    {
        global $neardBs, $neardLang, $neardRegistry;
        
        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_TEXT), Registry::APP_PATH_REG_ENTRY));
        $this->neardSplash->incrProgressBar();
        
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
        
        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_TEXT), Registry::APP_BINS_REG_ENTRY));
        $this->neardSplash->incrProgressBar();
        
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
        
        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_TEXT), Registry::SYSPATH_REG_ENTRY));
        $this->neardSplash->incrProgressBar();
        
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
                    $this->neardSplash->setImage(Splash::IMG_APACHE);
                    $bin = $neardBins->getApache();
                    $syntaxCheckCmd = BinApache::CMD_SYNTAX_CHECK;
                } elseif ($sName == BinMysql::SERVICE_NAME) {
                    $this->neardSplash->setImage(Splash::IMG_MYSQL);
                    $bin = $neardBins->getMysql();
                    $syntaxCheckCmd = BinMysql::CMD_SYNTAX_CHECK;
                } elseif ($sName == BinMariadb::SERVICE_NAME) {
                    $this->neardSplash->setImage(Splash::IMG_MARIADB);
                    $bin = $neardBins->getMariadb();
                    $syntaxCheckCmd = BinMariadb::CMD_SYNTAX_CHECK;
                } elseif ($sName == BinFilezilla::SERVICE_NAME) {
                    $this->neardSplash->setImage(Splash::IMG_FILEZILLA);
                    $bin = $neardBins->getFilezilla();
                }
        
                $name = $bin->getName() . ' ' . $bin->getVersion() . ' (' . $service->getName() . ')';
                $port = $bin->getPort();
        
                $this->neardSplash->incrProgressBar();
                $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_INSTALL_SERVICE_TEXT), $name));
        
                $this->neardSplash->incrProgressBar();
                if (!$service->delete()) {
                    $serviceRestart = true;
                }
        
                $this->neardSplash->incrProgressBar();
                if ($bin->changePort($port) !== true) {
                    $serviceError .= sprintf($neardLang->getValue(Lang::STARTUP_PORT_ERROR), $port);
                }
        
                if (!$serviceRestart) {
                    $isPortInUse = Batch::isPortInUse($port);
                    if ($isPortInUse === false) {
                        $this->neardSplash->incrProgressBar();
                        if (!$service->create()) {
                            $serviceError .= sprintf($neardLang->getValue(Lang::STARTUP_SERVICE_CREATE_ERROR), $service->getError());
                        }
        
                        if ($sName == BinApache::SERVICE_NAME && !$neardBins->getApache()->existsSslCrt()) {
                            $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_GEN_SSL_CRT_TEXT), 'localhost'));
                            Batch::genSslCertificate('localhost');
                        } elseif ($sName == BinFilezilla::SERVICE_NAME && !$neardBins->getFilezilla()->existsSslCrt()) {
                            $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_GEN_SSL_CRT_TEXT), $neardLang->getValue(Lang::FILEZILLA)));
                            Batch::genSslCertificate(BinFilezilla::SERVICE_NAME);
                        }
        
                        $this->neardSplash->incrProgressBar();
                        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_START_SERVICE_TEXT), $name));
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
                        $this->neardSplash->incrProgressBar();
                    } else {
                        if (!empty($serviceError)) {
                            $serviceError .= PHP_EOL;
                        }
                        $serviceError .= sprintf($neardLang->getValue(Lang::STARTUP_SERVICE_PORT_ERROR), $port, $isPortInUse);
                        $this->neardSplash->incrProgressBar(3);
                    }
                } else {
                    $this->restart = true;
                    $this->neardSplash->incrProgressBar(3);
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
            $this->neardSplash->incrProgressBar(self::GAUGE_SERVICES * count($neardBins->getServicesStartup()));
        }
    }
    
    private function refreshGitRepos()
    {
        global $neardLang, $neardTools;
        
        $this->neardSplash->setImage(Splash::IMG_GIT);
        $this->neardSplash->incrProgressBar();
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_GIT_REPOS_TEXT));
        
        $repos = $neardTools->getGit()->findRepos(false);
        $this->writeLog('Update GIT repos: ' . count($repos) . ' found');
    }
    
    private function refreshSvnRepos()
    {
        global $neardLang, $neardTools;
        
        $this->neardSplash->setImage(Splash::IMG_SVN);
        $this->neardSplash->incrProgressBar();
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_SVN_REPOS_TEXT));
        
        $repos = $neardTools->getSvn()->findRepos(false);
        $this->writeLog('Update SVN repos: ' . count($repos) . ' found');
    }
    
    private function writeLog($log)
    {
        global $neardBs;
        Util::logDebug($log, $neardBs->getStartupLogFilePath());
    }
}
