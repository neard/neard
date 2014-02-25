<?php

class ActionRestart
{
    const GAUGE_RESTART = 6;
    
    public function __construct($args)
    {
        global $neardBs, $neardCore, $neardConfig, $neardLang, $neardBins, $neardWinbinder;
        
        // Start splash screen
        $neardSplash = new Splash();
        $neardSplash->init(
            $neardLang->getValue(Lang::RESTART),
            self::GAUGE_RESTART,
            sprintf($neardLang->getValue(Lang::STARTUP_RESTARTING_TEXT), APP_TITLE . ' ' . $neardConfig->getAppVersion()),
            Splash::IMG_RESTART
        );
        
        for($i = 0; $i < self::GAUGE_RESTART; $i++) {
            $neardSplash->incrProgressBar();
            sleep(1);
        }
        
        $neardSplash->incrProgressBar();
        $neardWinbinder->destroyWindow($neardSplash->getWbWindow());
        $neardWinbinder->exec($neardBs->getExeFilePath());
        $neardWinbinder->reset();
    }
    
}
