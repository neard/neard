<?php

class ActionQuit
{
    private $neardSplash;
    
    const GAUGE_LOADING = 1;
    
    public function __construct($args)
    {
        global $neardCore, $neardLang, $neardBins, $neardWinbinder;
        
        // Start splash screen
        $this->neardSplash = new Splash();
        $this->neardSplash->init(
            $neardLang->getValue(Lang::QUIT),
            self::GAUGE_LOADING * count($neardBins->getServices()),
            sprintf($neardLang->getValue(Lang::EXIT_LEAVING_TEXT), APP_TITLE . ' ' . $neardCore->getAppVersion()),
            Splash::IMG_EXIT
        );
        
        $neardWinbinder->setHandler($this->neardSplash->getWbWindow(), $this, 'processWindow', 2000);
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBs, $neardCore, $neardConfig, $neardBins, $neardLang, $neardWinbinder;
        
        foreach ($neardBins->getServices() as $sName => $service) {
            $name = $neardBins->getApache()->getName() . ' ' . $neardBins->getApache()->getVersion();
            $port = $neardBins->getApache()->getPort();
            if ($sName == BinMysql::SERVICE_NAME) {
                $name = $neardBins->getMysql()->getName() . ' ' . $neardBins->getMysql()->getVersion();
                $port = $neardBins->getMysql()->getPort();
            } elseif ($sName == BinMariadb::SERVICE_NAME) {
                $name = $neardBins->getMariadb()->getName() . ' ' . $neardBins->getMariadb()->getVersion();
                $port = $neardBins->getMariadb()->getPort();
            } elseif ($sName == BinFilezilla::SERVICE_NAME) {
                $name = $neardBins->getFilezilla()->getName() . ' ' . $neardBins->getFilezilla()->getVersion();
                $port = $neardBins->getFilezilla()->getPort();
            }
            $name .= ' (' . $service->getName() . ')';
            
            $this->neardSplash->incrProgressBar();
            $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::EXIT_REMOVE_SERVICE_TEXT), $name));
            $service->delete();
        }
        
        $neardWinbinder->destroyWindow($window);
    }
}
