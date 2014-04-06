<?php

class ActionStartup
{
    private $neardSplash;
    
    const GAUGE_SERVICES = 6;
    const GAUGE_OTHERS = 13;
    
    public function __construct($args)
    {
        global $neardConfig, $neardLang, $neardBins, $neardWinbinder;
        
        // Start splash screen
        $this->neardSplash = new Splash();
        $this->neardSplash->init(
            $neardLang->getValue(Lang::STARTUP),
            self::GAUGE_SERVICES * count($neardBins->getServices()) + self::GAUGE_OTHERS,
            sprintf($neardLang->getValue(Lang::STARTUP_STARTING_TEXT), APP_TITLE . ' ' . $neardConfig->getAppVersion()),
            Splash::IMG_STARTING
        );
        
        $neardWinbinder->setHandler($this->neardSplash->getWbWindow(), $this, 'processWindow', 1000);
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBs, $neardCore, $neardConfig, $neardLang, $neardBins, $neardApps, $neardTools, $neardWinbinder, $neardRegistry, $neardHomepage;
        $error = '';
        $startTime = Util::getMicrotime();
        $restart = false;
        
        // Kill old PHP instances
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_KILL_PHP_PROCS_TEXT));
        $this->neardSplash->incrProgressBar();
        $pids = Win32Ps::getListProcs();
        if ($pids !== false) {
            foreach ($pids as $aPid) {
                if (isset($aPid[Win32Ps::PID]) && isset($aPid[Win32Ps::EXE])) {
                    $unixExePath = Util::formatUnixPath($aPid[Win32Ps::EXE]);
                    if ($unixExePath == $neardCore->getPhpCliSilentExe() && $aPid[Win32Ps::PID] != Win32Ps::getCurrentPid()) {
                        Win32Ps::kill($aPid[Win32Ps::PID]);
                    }
                }
            }
        }
        
        // Refresh hostname
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_HOSTNAME_TEXT));
        $this->neardSplash->incrProgressBar();
        $neardConfig->replace(Config::CFG_HOSTNAME, gethostname());
        
        // Check default browser
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_CHECK_BROWSER_TEXT));
        $this->neardSplash->incrProgressBar();
        $currentBrowser = $neardConfig->getBrowser();
        if (empty($currentBrowser) || !file_exists($currentBrowser)) {
            $neardConfig->replace(Config::CFG_BROWSER, Vbs::getDefaultBrowser());
        }
        
        // Purge logs
        if ($neardConfig->getAppPurgeLogsOnStartup()) {
            $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_PURGE_LOGS_TEXT));
            $this->neardSplash->incrProgressBar();
            Util::clearFolder($neardBs->getLogsPath(), array('placeholder'));
            $this->writeLog('Purge logs');
        } else {
            $this->neardSplash->incrProgressBar();
        }
        
        // Refresh alias homepage
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_REFRESH_ALIAS_HOMEPAGE_TEXT));
        $this->neardSplash->incrProgressBar();
        $neardHomepage->refreshAliasContent();
        $this->writeLog('Refresh alias homepage');
        
        // Check path
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_CHECK_PATH_TEXT));
        $this->neardSplash->incrProgressBar();
        $currentPath = $neardBs->getRootPath();
        $oldPaths = Util::getAppPaths();
        $this->writeLog('Old app paths: ' . implode(' ; ', $oldPaths));
        
        // Scan folders
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::STARTUP_SCAN_FOLDERS_TEXT));
        $this->neardSplash->incrProgressBar();
        $filesToScan = Util::getFilesToScan();
        $this->writeLog('Files to scan: ' . count($filesToScan));
        
        // Change old paths
        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_CHANGE_OLD_PATHS_TEXT), $currentPath));
        $this->neardSplash->incrProgressBar();
        $unixCurrentPath = Util::formatUnixPath($currentPath);
        $windowsCurrentPath = Util::formatWindowsPath($currentPath);
        $countChangedOcc = 0;
        $countChangedFiles = 0;
        foreach ($filesToScan as $fileToScan) {
            $tmpCountChangedOcc = 0;
            $fileContentOr = file_get_contents($fileToScan);
            $fileContent = $fileContentOr;
            foreach ($oldPaths as $oldPath) {
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
        
        // Save current path
        if (!in_array($currentPath, $oldPaths)) {
            if (count($oldPaths) > 5) {
                unset($appPaths[0]);
            }
            $oldPaths[] = $currentPath;
            file_put_contents($neardCore->getAppPaths(), implode(PHP_EOL, $oldPaths));
            $this->writeLog('Save current path: ' . $currentPath);
        }
        
        // Check app path reg key
        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_TEXT), Registry::APP_PATH_REG_ENTRY));
        $this->neardSplash->incrProgressBar();
        
        $currentAppPathRegKey = Util::getAppPathRegKey();
        $genAppPathRegKey = Util::formatWindowsPath($neardBs->getRootPath());
        $this->writeLog('Current app path reg key: ' . $currentAppPathRegKey);
        $this->writeLog('Gen app path reg key: ' . $genAppPathRegKey);
        if ($currentAppPathRegKey != $genAppPathRegKey) {
            if (!Util::setAppPathRegKey($genAppPathRegKey)) {
                if (!empty($error)) {
                    $error .= PHP_EOL . PHP_EOL;
                }
                $error .= sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_ERROR_TEXT), Registry::APP_PATH_REG_ENTRY);
                $error .= PHP_EOL . $neardRegistry->getLatestError();
            } else {
                $restart = true;
            }
        }
        
        // Check app bins reg key
        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_TEXT), Registry::APP_BINS_REG_ENTRY));
        $this->neardSplash->incrProgressBar();
        
        $currentAppBinsRegKey = Util::getAppBinsRegKey();
        $genAppBinsRegKey = Util::getAppBinsRegKey(false);
        $this->writeLog('Current app bins reg key: ' . $currentAppBinsRegKey);
        $this->writeLog('Gen app bins reg key: ' . $genAppBinsRegKey);
        if ($currentAppBinsRegKey != $genAppBinsRegKey) {
            if (!Util::setAppBinsRegKey($genAppBinsRegKey)) {
                if (!empty($error)) {
                    $error .= PHP_EOL . PHP_EOL;
                }
                $error .= sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_ERROR_TEXT), Registry::APP_BINS_REG_ENTRY);
                $error .= PHP_EOL . $neardRegistry->getLatestError();
            } else {
                $restart = true;
            }
        }
        
        // Check system path reg key
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
                if (!empty($error)) {
                    $error .= PHP_EOL . PHP_EOL;
                }
                $error .= sprintf($neardLang->getValue(Lang::STARTUP_REGISTRY_ERROR_TEXT), Registry::SYSPATH_REG_ENTRY);
                $error .= PHP_EOL . $neardRegistry->getLatestError();
            } else {
                $restart = true;
            }
        }
        
        // Services
        if (!$restart) {
            foreach ($neardBins->getServices() as $sName => $service) {
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
                } elseif ($sName == BinXlight::SERVICE_NAME) {
                    $this->neardSplash->setImage(Splash::IMG_XLIGHT);
                    $bin = $neardBins->getXlight();
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
                    $restart = true;
                    $this->neardSplash->incrProgressBar(3);
                }
            
                if (!empty($serviceError)) {
                    if (!empty($error)) {
                        $error .= PHP_EOL . PHP_EOL;
                    }
                    $error .= sprintf($neardLang->getValue(Lang::STARTUP_SERVICE_ERROR), $name) . PHP_EOL . $serviceError;
                } else {
                    $this->writeLog($name . ' service installed in ' . round(Util::getMicrotime() - $startServiceTime, 3) . 's');
                }
            }
        } else {
            $this->neardSplash->incrProgressBar(self::GAUGE_SERVICES * count($neardBins->getServices()));
        }
        
        // Actions if everything OK
        if (!$restart && empty($error)) {
            // Refresh Git repos
            $this->neardSplash->setImage(Splash::IMG_GIT);
            $this->neardSplash->incrProgressBar();
            $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REFRESH_GIT_REPOS_TEXT), $name));
            $repos = $neardTools->getGit()->findRepos(false);
            $this->writeLog('Update GIT repos: ' . count($repos) . ' found');
            
            // Refresh SVN repos
            $this->neardSplash->setImage(Splash::IMG_SVN);
            $this->neardSplash->incrProgressBar();
            $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STARTUP_REFRESH_SVN_REPOS_TEXT), $name));
            $repos = $neardTools->getSvn()->findRepos(false);
            $this->writeLog('Update SVN repos: ' . count($repos) . ' found');
            
            $this->writeLog('Started in ' . round(Util::getMicrotime() - $startTime, 3) . 's');
        } else {
            $this->neardSplash->incrProgressBar(2);
        }
        
        if ($restart) {
            $this->writeLog('Restart App');
            $this->neardSplash->setTextLoading(sprintf(
                $neardLang->getValue(Lang::STARTUP_PREPARE_RESTART_TEXT),
                APP_TITLE . ' ' . $neardConfig->getAppVersion())
            );
            foreach ($neardBins->getServices() as $sName => $service) {
                $service->delete();
            }
            $neardCore->setExec(ActionExec::RESTART);
        }
        
        if (!empty($error)) {
            $this->writeLog('Error: ' . $error);
            foreach ($neardBins->getServices() as $sName => $service) {
                $service->delete();
            }
            $neardWinbinder->messageBoxError($error, $neardLang->getValue(Lang::STARTUP_ERROR_TITLE));
            $neardCore->setExec(ActionExec::QUIT);
        }
        
        Util::startLoading();
        $neardWinbinder->destroyWindow($window);
    }
    
    private function writeLog($log)
    {
        global $neardBs;
        Util::logDebug($log, $neardBs->getStartupLogFilePath());
    }
}
