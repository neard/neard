<?php

class ActionQuit
{
    private $splash;
    
    const GAUGE_PROCESSES = 1;
    const GAUGE_OTHERS = 1;
    
    public function __construct($args)
    {
        global $neardCore, $neardLang, $neardBins, $neardWinbinder;
        
        // Start splash screen
        $this->splash = new Splash();
        $this->splash->init(
            $neardLang->getValue(Lang::QUIT),
            self::GAUGE_PROCESSES * count($neardBins->getServices()) + self::GAUGE_OTHERS,
            sprintf($neardLang->getValue(Lang::EXIT_LEAVING_TEXT), APP_TITLE . ' ' . $neardCore->getAppVersion())
        );
        
        $neardWinbinder->setHandler($this->splash->getWbWindow(), $this, 'processWindow', 2000);
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBins, $neardLang, $neardWinbinder;
        
        foreach ($neardBins->getServices() as $sName => $service) {
            $name = $neardBins->getApache()->getName() . ' ' . $neardBins->getApache()->getVersion();
            if ($sName == BinMysql::SERVICE_NAME) {
                $name = $neardBins->getMysql()->getName() . ' ' . $neardBins->getMysql()->getVersion();
            } elseif ($sName == BinMailhog::SERVICE_NAME) {
                $name = $neardBins->getMailhog()->getName() . ' ' . $neardBins->getMailhog()->getVersion();
            } elseif ($sName == BinMariadb::SERVICE_NAME) {
                $name = $neardBins->getMariadb()->getName() . ' ' . $neardBins->getMariadb()->getVersion();
            } elseif ($sName == BinPostgresql::SERVICE_NAME) {
                $name = $neardBins->getPostgresql()->getName() . ' ' . $neardBins->getPostgresql()->getVersion();
            } elseif ($sName == BinMailhog::SERVICE_NAME) {
                $name = $neardBins->getPostgresql()->getName() . ' ' . $neardBins->getPostgresql()->getVersion();
            } elseif ($sName == BinMemcached::SERVICE_NAME) {
                $name = $neardBins->getMemcached()->getName() . ' ' . $neardBins->getMemcached()->getVersion();
            } elseif ($sName == BinFilezilla::SERVICE_NAME) {
                $name = $neardBins->getFilezilla()->getName() . ' ' . $neardBins->getFilezilla()->getVersion();
            } elseif ($sName == BinSvn::SERVICE_NAME) {
                $name = $neardBins->getSvn()->getName() . ' ' . $neardBins->getSvn()->getVersion();
            }
            $name .= ' (' . $service->getName() . ')';
            
            $this->splash->incrProgressBar();
            $this->splash->setTextLoading(sprintf($neardLang->getValue(Lang::EXIT_REMOVE_SERVICE_TEXT), $name));
            $service->delete();
        }
        
        $this->splash->incrProgressBar();
        $this->splash->setTextLoading($neardLang->getValue(Lang::EXIT_STOP_OTHER_PROCESS_TEXT));
        Win32Ps::killBins(true);
        
        $neardWinbinder->destroyWindow($window);
    }
}
