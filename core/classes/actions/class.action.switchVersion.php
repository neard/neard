<?php

class ActionSwitchVersion
{
    private $neardSplash;
    
    private $version;
    private $currentVersion;
    private $bin;
    private $restart;
    private $service;
    private $changePort;
    private $boxTitle;
    
    const GAUGE_SERVICES = 1;
    const GAUGE_OTHERS = 5;
    
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardWinbinder;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            $this->version = $args[1];
            
            if ($args[0] == $neardBins->getApache()->getName()) {
                $this->currentVersion = CURRENT_APACHE_VERSION;
                $this->bin = $neardBins->getApache();
                $this->restart = true;
                $this->service = $neardBins->getApache()->getService();
                $this->changePort = true;
            } elseif ($args[0] == $neardBins->getPhp()->getName()) {
                $this->currentVersion = CURRENT_PHP_VERSION;
                $this->bin = $neardBins->getPhp();
                $this->restart = true;
                $this->service = $neardBins->getApache()->getService();
                $this->changePort = false;
            } elseif ($args[0] == $neardBins->getMysql()->getName()) {
                $this->currentVersion = CURRENT_MYSQL_VERSION;
                $this->bin = $neardBins->getMysql();
                $this->restart = true;
                $this->service = $neardBins->getMysql()->getService();
                $this->changePort = true;
            } elseif ($args[0] == $neardBins->getMariadb()->getName()) {
                $this->currentVersion = CURRENT_MARIADB_VERSION;
                $this->bin = $neardBins->getMariadb();
                $this->restart = true;
                $this->service = $neardBins->getMariadb()->getService();
                $this->changePort = true;
            } elseif ($args[0] == $neardBins->getNodejs()->getName()) {
                $this->currentVersion = CURRENT_NODEJS_VERSION;
                $this->bin = $neardBins->getNodejs();
                $this->restart = true;
                $this->service = null;
                $this->changePort = false;
            } elseif ($args[0] == $neardBins->getFilezilla()->getName()) {
                $this->currentVersion = CURRENT_FILEZILLA_VERSION;
                $this->bin = $neardBins->getFilezilla();
                $this->restart = true;
                $this->service = $neardBins->getFilezilla()->getService();
                $this->changePort = true;
            }
            
            $this->boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->bin->getName(), $this->version);
            
            // Start splash screen
            $this->neardSplash = new Splash();
            $this->neardSplash->init(
                $this->boxTitle,
                self::GAUGE_SERVICES * count($neardBins->getServices()) + self::GAUGE_OTHERS,
                $this->boxTitle
            );
            
            $neardWinbinder->setHandler($this->neardSplash->getWbWindow(), $this, 'processWindow', 1000);
            $neardWinbinder->mainLoop();
            $neardWinbinder->reset();
        }
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBs, $neardCore, $neardLang, $neardBins, $neardWinbinder;
        
        if ($this->version == $this->currentVersion) {
            $neardWinbinder->messageBoxWarning(sprintf($neardLang->getValue(Lang::SWITCH_VERSION_SAME_ERROR), $this->bin->getName(), $this->version), $this->boxTitle);
            $neardWinbinder->destroyWindow($window);
        }
        
        $this->neardSplash->incrProgressBar();
        if ($this->bin->switchVersion($this->version, true) === false) {
            $this->neardSplash->incrProgressBar(self::GAUGE_SERVICES * count($neardBins->getServices()) + self::GAUGE_OTHERS);
            $neardWinbinder->destroyWindow($window);
        }
        
        // remove service
        if ($this->service != null) {
            $binName = $this->bin->getName() == $neardLang->getValue(Lang::PHP) ? $neardLang->getValue(Lang::APACHE) : $this->bin->getName();
            $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::REMOVE_SERVICE_TITLE), $binName));
            $this->neardSplash->incrProgressBar();
            $this->service->delete();
        } else {
            $this->neardSplash->incrProgressBar();
        }
        
        // reload config
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::SWITCH_VERSION_RELOAD_CONFIG));
        $this->neardSplash->incrProgressBar();
        Bootstrap::loadConfig();
    
        // reload bins
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::SWITCH_VERSION_RELOAD_BINS));
        $this->neardSplash->incrProgressBar();
        $neardBins->reload();
        
        // change port
        if ($this->changePort) {
            $this->bin->reload();
            $this->bin->changePort($this->bin->getPort());
        }
    
        if (!$this->restart) {
            $this->neardSplash->incrProgressBar(self::GAUGE_SERVICES * count($neardBins->getServices()) + 1);
            
            $neardWinbinder->messageBoxInfo(
                sprintf($neardLang->getValue(Lang::SWITCH_VERSION_OK), $this->bin->getName(), $this->version),
                $this->boxTitle);
            
            $neardWinbinder->destroyWindow($window);
        }
            
        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::SWITCH_VERSION_REGISTRY), Registry::APP_BINS_REG_ENTRY));
        $this->neardSplash->incrProgressBar(2);
        Util::setAppBinsRegKey(Util::getAppBinsRegKey(false));

        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::SWITCH_VERSION_RESET_SERVICES));
        foreach ($neardBins->getServices() as $sName => $service) {
            $this->neardSplash->incrProgressBar();
            $service->delete();
        }

        $neardWinbinder->messageBoxInfo(
            sprintf($neardLang->getValue(Lang::SWITCH_VERSION_OK_RESTART), $this->bin->getName(), $this->version, APP_TITLE),
            $this->boxTitle);
        
        $neardCore->setExec(ActionExec::RESTART);

        $neardWinbinder->destroyWindow($window);
    }
    
}
